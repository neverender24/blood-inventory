<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Dispositions</h4>
                <div class="row">
                    <div class="form-group col-2">
                        <select v-model="tableData.show" @change="getData()" class="form-control">
                            <option value="all">All</option>
                            <option value="available" selected>Available</option>
                            <option value="near_expiry">Near Expiry</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>

                    <div class="form-group col-4">
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
                    <div class="form-group col-4">
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
                            <td>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" />
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </td>
                            <td v-html="nearExpire(item.date_expiry)"></td>
                            <td>{{ item.serial }}</td>
                            <td>{{ item.blood_type.description }}</td>
                            <td v-html="identifyBloodComponent(item.blood_component.description)"></td>
                            <td>{{ item.vol }}</td>
                            <td>{{ item.date_extracted }}</td>
                            <td>{{ item.date_expiry }}</td>
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
                <pagination
                    :pagination="pagination"
                    @prev="getData(pagination.prevPageUrl)"
                    @next="getData(pagination.nextPageUrl)"
                ></pagination>
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

<script>
import Datatable from "../../helpers/datatable.vue";
import Pagination from "../../helpers/pagination.vue";
import EditDisposition from "./edit";
import CreateDisposition from "./create.vue";

export default {
    components: {
        datatable: Datatable,
        pagination: Pagination,
        EditDisposition,
        CreateDisposition
    },
    data() {
        let sortOrders = {};

        let columns = [
            { width: "1%", label: "", name: "flag" },
            { width: "20%", label: "Serial", name: "serial" },
            { width: "20%", label: "Group", name: "group" },
            { width: "20%", label: "Product", name: "product" },
            { width: "20%", label: "Volume", name: "volume" },
            { width: "20%", label: "Extracted", name: "extracted" },
            { width: "20%", label: "Expiry", name: "expiry" }
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
                show: "available"
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
            editData: {}
        };
    },

    mounted() {
        this.getData();

        this.$store.dispatch("loadBloodTypes");
        this.$store.dispatch("loadBloodComponents");

        setTimeout(() => {
            this.$emit("notify");
        }, 1000);
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
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
                    this.configPagination(data.data);
                }
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

        nearExpire(date) {
            const startDate = date;
            const endDate = new Date();
            const timeDiff = new Date(startDate) - endDate;
            const days = timeDiff / (1000 * 60 * 60 * 24);

            if (days <= 10 && days >= 0) {
                return '<span class="fa fa-exclamation text-danger"></span>';
            } else if (days <= 0) {
                return '<span class="fa fa-close text-danger"></span>';
            } else {
                return '<span class="fa fa-check text-success"></span>';
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
        }
    }
};
</script>
