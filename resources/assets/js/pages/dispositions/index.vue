<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="py-3 d-flex flex-row align-items-center justify-content-between print_head">
                    <h4 class="card-title">Dispositions</h4>
                    <json-excel
                        class="btn btn-default"
                        :fetch="print"
                        :fields = "json_fields"
                        worksheet="My Worksheet"
                        name="filename.xls"
                    >
                        <button class="btn btn-primary btn-sm">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                  <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                  <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                                </svg>
                            </span>
                        </button>
                    </json-excel>
                </div>
                <div class="row">
                    <div class="form-group col-2">
                        <select v-model="tableData.show" @change="getData()" class="form-control">
                            <option value="all">All</option>
                            <option value="available" selected>Available</option>
                            <option value="near_expiry">Near Expiry</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>

                    <div class="form-group col-2">
                        <select
                            class="form-control"
                            @change="getData()"
                            v-model="tableData.blood_type_id"
                        >
                            <option value></option>
                            <option
                                v-for="option in bloodTypes"
                                v-bind:item="option"
                                :value="option.id"
                            >{{ option.description }}</option>
                        </select>
                    </div>

                    <div class="form-group col-2">
                        <select
                            class="form-control"
                            @change="getData()"
                            v-model="tableData.blood_component_id"
                        >
                            <option value></option>
                            <option
                                v-for="option in bloodComponents"
                                v-bind:item="option"
                                :value="option.id"
                            >{{ option.description }}</option>
                        </select>
                    </div>

                    <div class="form-group col-3">
                        <input
                            type="date"
                            class="form-control"
                            v-model="tableData.search"
                            @input="getData()"
                        />
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

                <datatable
                    :columns="columns"
                    :sortKey="sortKey"
                    :sortOrders="sortOrders"
                    @sort="sortBy"
                >
                    <tbody>
                        <tr v-for="item in data">
                            <td v-html="nearExpire(item.expiry)"></td>
                            <td>{{ item.serial }}</td>
                            <td>{{ item.blood_type.description }}</td>
                            <td v-html="identifyBloodComponent(item.blood_component.description)"></td>
                            <td>{{ item.vol }}</td>
                            <td>{{ item.date_extracted }}</td>
                            <td>{{ item.date_expiry }}</td>
                            <td>{{ item.order_details.length ? item.order_details[0].order.transaction_code : '' }}</td>
                            <td v-if="!item.order_details.length">
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

                <div class="py-3 d-flex flex-row align-items-center justify-content-between">
                    <pagination
                        :pagination="pagination"
                        @prev="getData(pagination.prevPageUrl)"
                        @next="getData(pagination.nextPageUrl)"
                    ></pagination>
                    <p>{{ pagination.from }} to {{pagination.to}} of {{ pagination.total }} entries</p>
                    <select
                        v-model="tableData.length"
                        @change="getData()"
                        class="form-control col-lg-1"
                    >
                        <option value="15" selected="selected">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>
        <edit-disposition
            :id="id"
            :data="editData"
            @refresh="getData()"
            @notify="refreshNotification()"
        ></edit-disposition>
        <create-disposition @refresh="getData()"></create-disposition>
    </div>
</template>
<style>
.print_head {
    padding: 0px !important;
}
</style>

<script>
import Datatable from "../../helpers/datatable.vue";
import Pagination from "../../helpers/pagination.vue";
import EditDisposition from "./edit";
import CreateDisposition from "./create.vue";
import { mapState } from "vuex";
import JsonExcel from "vue-json-excel";

export default {
    components: {
        datatable: Datatable,
        pagination: Pagination,
        EditDisposition,
        CreateDisposition,
        JsonExcel
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
            { width: "20%", label: "Transaction", name: "Transaction" }
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
                blood_component_id: "",
                blood_type_id: ""
            },
            json_fields: {
                'Serial': 'serial',
                'Type': 'blood_type.description',
                'Component': 'blood_component.description',
                'Expiry': 'date_expiry',
                'Extracted': 'date_extracted',
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
            printData: [],
            id: "",
            editData: {}
        };
    },

    mounted() {
        // this.$store.dispatch("toggleLoading", true);
        this.getData();

        this.$store.dispatch("loadBloodTypes");
        this.$store.dispatch("loadBloodComponents");

        setTimeout(() => {
            this.$emit("notify");
        }, 1000);
    },

    computed: {
        ...mapState(["bloodTypes", "bloodComponents"])
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

        getData(url = "dispositions") {
            this.$store.dispatch("toggleLoading", true);
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
                    this.configPagination(data.data);
                }
                this.$store.dispatch("toggleLoading", false);

                this.tableData.print = false;
            });
        },

        edit(id, data) {
            this.id = id;

            axios.get("/dispositions/" + this.id + "/edit").then(response => {
                this.editData = response.data;
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

        identifyBloodComponent(desc) {
            if (desc == "Platelet") {
                return (
                    '<label class="badge badge-primary">' + desc + "</label>"
                );
            } else if (desc == "Whole Blood") {
                return '<label class="badge badge-info">' + desc + "</label>";
            } else if (desc == "Plasma") {
                return (
                    '<label class="badge badge-success">' + desc + "</label>"
                );
            } else if (desc == "Packed RBC") {
                return (
                    '<label class="badge badge-warning">' + desc + "</label>"
                );
            }
        },

        async print(url = "dispositions") {
            this.tableData.print = true;
            this.$store.dispatch("toggleLoading", true);
            const response = await axios.get(url, { params: this.tableData })
            this.$store.dispatch("toggleLoading", false);
            this.tableData.print = false;
            return response.data.data

        }
    }
};
</script>
