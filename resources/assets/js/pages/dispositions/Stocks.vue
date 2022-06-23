<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div v-if="loading" class="ui inverted active dimmer">
                    <div class="ui active loader"></div>
                </div>
                <h4 class="card-title">Stocks</h4>
                <div class="row">
                    <div class="form-group col-2">
                        <button
                            type="button"
                            class="btn btn-outline-primary btn-sm btn-block"
                            @click="store()"
                        >Store</button>
                    </div>
                    <div class="form-group col-2">
                        <button
                            type="button"
                            class="btn btn-outline-primary btn-sm btn-block"
                            @click="release()"
                            data-toggle="modal"
                            data-target="#releaseModal"
                        >Release</button>
                    </div>
                    <div class="form-group col-2">
                        <select v-model="tableData.show" @change="getData()" class="form-control">
                            <option value="all">All</option>
                            <option value="available" selected>Available</option>
                            <option value="near_expiry">Near Expiry</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-filter"></i>
                                </span>
                            </div>
                            <input
                                type="date"
                                class="form-control"
                                v-model="tableData.search"
                                @input="getData()"
                            />
                        </div>
                    </div>
                    <div class="form-group col-3">
                        <input
                            type="text"
                            class="form-control"
                            v-model="tableData.serial"
                            placeholder="Enter serial"
                            @input="getData()"
                        />
                    </div>
                </div>
                <div class="row text-center" v-if="loading">
                    <div class="col-md-12">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <datatable
                    :columns="columns"
                    :sortKey="sortKey"
                    :sortOrders="sortOrders"
                    @sort="sortBy"
                >
                    <tbody>
                        <tr v-for="item in data" :key="item.serial">
                            <td v-html="nearExpire(item.expiry)"></td>
                            <td>{{ item.serial }}</td>
                            <td>{{ item.blood_type.description }}</td>
                            <td v-html="identifyBloodType(item.blood_component.description)"></td>
                            <td>{{ item.vol }}</td>
                            <td>{{ item.date_extracted }}</td>
                            <td>{{ item.date_expiry }}</td>
                            <td>{{ item.order_details.length ? item.order_details[0].order.transaction_code : '' }}</td>
                            <td v-if="user.id == item.user_id">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        @click="edit(item.id)"
                                        data-toggle="modal"
                                        data-target="#editModal"
                                    >Edit</button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        @click="del(item.id)"
                                    >Delete</button>
                                </div>
                            </td>
                            <td v-else class="bg-light">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        disabled
                                    >Edit</button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        disabled
                                    >Delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </datatable>
                <pagination
                    :pagination="pagination"
                    @prev="getData(pagination.prevPageUrl)"
                    @next="getData(pagination.nextPageUrl)"
                ></pagination>
            </div>
        </div>
        <releases 
            :dispositions="dispositions" 
            v-if="releaseModal" 
            @closeModal=" 
            releaseModal=false"
        ></releases>

        <edit-disposition
            :id="id"
            :data="editData"
            @refresh="getData()"
            @notify="refreshNotification()"
            @closeModal="editModal=false" 
             v-if="editModal"
        ></edit-disposition>
        <store v-if="storeModal" @closeModal="storeModal=false" ></store>
    </div>
</template>

<script>
import Datatable from "../../helpers/datatable.vue";
import Pagination from "../../helpers/pagination.vue";
import Releases from "./Releases.vue";
import Store from "./Store.vue";
import EditDisposition from "./edit";
import { mapState } from "vuex";

export default {
    components: {
        datatable: Datatable,
        pagination: Pagination,
        Releases,
        Store,
        EditDisposition
    },
    data() {
        let sortOrders = {};

        let columns = [
            { width: "1%", label: "", name: "flag" },
            { width: "20%", label: "Serial", name: "serial" },
            { width: "5%", label: "Group", name: "group" },
            { width: "20%", label: "Product", name: "product" },
            { width: "5%", label: "Volume", name: "volume" },
            { width: "20%", label: "Extracted", name: "extracted" },
            { width: "20%", label: "Expiry", name: "expiry" },
            { width: "20%", label: "Transaction", name: "Transaction" },
        ];

        columns.forEach(column => {
            sortOrders[column.name] = -1;
        });

        return {
            columns: columns,
            sortKey: "serial",
            sortOrders: sortOrders,
            tableData: {
                draw: 0,
                length: 15,
                search: "",
                column: 0,
                dir: "desc",
                show: "available",
                serial: ""
            },
            pagination: {
                lastPage: "",
                currentPage: "",
                total: "",
                lastPageUrl: "",
                nextPageUrl: "",
                prevPageUrl: "",
                from: "",
                to: ""
            },
            data: [],
            id: "",
            editData: {},
            loading: false,
            dispositions: [],
            storeModal: false,
            editModal: false,
            releaseModal: false,
        };
    },

    mounted() {
        this.getData();
    },

    computed: {
        ...mapState(["user"])
    },

    methods: {
        configPagination(data) {
            this.pagination.lastPage = data.last_page;
            this.pagination.currentPage = data.current_page;
            this.pagination.total = data.total;
            this.pagination.lastPageUrl = data.last_page_url;
            this.pagination.prevPageUrl = data.prev_page_url;
            this.pagination.nextPageUrl = data.next_page_url;
            this.pagination.from = data.from;
            this.pagination.to = data.to;
        },

        sortBy(key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
            this.tableData.column = this.getIndex(this.columns, "name", key);
            this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
            this.getData();
        },

        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value);
        },

        getData: _.debounce(function(url = "client-dispositions") {
            this.loading = true
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
                    this.configPagination(data.data);
                }
                this.loading = false
            });
        }, 1000),

        release() {
            axios.post("/get-available-dispositions-client").then( response => {
                this.dispositions = response.data

                this.releaseModal = true
            })
        },

        store() {
            this.storeModal = true
        },

        edit(id, data) {
            this.id = id;

            axios.get("/dispositions/" + this.id + "/edit").then(response => {
                this.editData = response.data;

                this.editModal = true
            });
        },

        del(id) {
            this.$snotify.confirm("You are about to delete. Continue?", "", {
                pauseOnHover: true,
                position: "centerCenter",
                buttons: [
                    {
                        text: "Yes",
                        action: () => {
                            axios
                                .delete("/dispositions/" + id)
                                .then(response => {
                                    this.getData();
                                    this.$emit("notify");
                                    this.$toasted.show("Successfully Deleted", {
                                        type: "success",
                                        theme: "bubble",
                                        position: "top-right",
                                        duration: 5000
                                    });
                                });

                            this.$snotify.remove();
                        },
                        bold: false
                    },
                    { text: "No", action: () => this.$snotify.remove() }
                ]
            });
        },

        nearExpire(expiry) {
            if (expiry == "Near Expiry") {
                return '<span class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-lg" viewBox="0 0 16 16"><path d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0L7.005 3.1ZM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"/></svg></span>';
            } else if (expiry == "Expired") {
                return '<span class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/><path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/></svg></span>';
            } else {
                return '<span class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/></svg></span>';
            }
        },

        refreshNotification() {
            this.$emit("notify");
        },

        identifyBloodType(bloodType) {
            if (bloodType == "Platelet") {
                return (
                    "<label class='badge badge-primary'>" +
                    bloodType +
                    "</label>"
                );
            } else if (bloodType == "Whole Blood") {
                return (
                    "<label class='badge badge-info'>" + bloodType + "</label>"
                );
            } else if (bloodType == "Plasma") {
                return (
                    "<label class='badge badge-success'>" +
                    bloodType +
                    "</label>"
                );
            } else if (bloodType == "Packed RBC") {
                return (
                    "<label class='badge badge-warning'>" +
                    bloodType +
                    "</label>"
                );
            }
        }
    }
};
</script>
