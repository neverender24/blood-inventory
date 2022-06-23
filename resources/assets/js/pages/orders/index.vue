<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div v-if="loading" class="ui inverted active dimmer">
                    <div class="ui active loader"></div>
                </div>
                <h4 class="card-title">Orders</h4>

                <div class="row">
                    <div class="form-group col-1">
                        <button class="btn btn-success btn-block" data-toggle="modal" data-target="#orderModal"
                            @click="add()">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                                </svg>
                            </i>
                        </button>
                    </div>
                    <div class="form-group col-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                        </svg>
                                    </i>
                                </span>
                            </div>
                            <input type="date" class="form-control" v-model="tableData.date_ordered"
                                @input="getData()" />
                        </div>
                    </div>
                    <div class="form-group col-3">
                        <input type="text" class="form-control" v-model="tableData.transaction_code"
                            placeholder="Enter transaction code" @input="getData()" />
                    </div>
                    <div class="form-group col-2">
                        <select v-model="tableData.show" @change="getData()" class="form-control form-control-sm">
                            <option value="all">All</option>
                            <option value="Pending" selected>Pending</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                    <tbody>
                        <tr v-for="item in data" :key="item.serial">
                            <td>{{ item.transaction_code }}</td>
                            <td>{{ item.user.name }}</td>
                            <td>{{ item.order_date }}</td>
                            <td>{{ item.order_time }}</td>
                            <td>{{ item.delivery_date }}</td>
                            <td>{{ item.delivery_time }}</td>
                            <!-- Check Status -->
                            <td v-if="item.received_date != null">
                                <label class="badge badge-success">Completed</label>
                            </td>
                            <td v-else-if="item.delivery_date != null">
                                <label class="badge badge-info">Delivered</label>
                            </td>
                            <td v-else>
                                <label class="badge badge-warning">Pending</label>
                            </td>

                            <td v-if="item.routine == 1">
                                <label class="badge badge-danger">Yes</label>
                            </td>
                            <td v-else>
                                <label class="badge badge-primary">No</label>
                            </td>
                            <td v-if="item.delivery_date == null && item.received_date == null">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-primary"
                                        @click="edit(item.id, 'edit')">Edit</button>
                                    <button type="button" class="btn btn-outline-danger"
                                        @click="del(item.id)">Delete</button>
                                </div>
                            </td>
                            <td v-else>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-primary"
                                        @click="edit(item.id, 'details')">Details</button>
                                    <button type="button" class="btn btn-outline-primary"
                                        @click="printer(item.id)">Print</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </datatable>
                <pagination :pagination="pagination" @prev="getData(pagination.prevPageUrl)"
                    @next="getData(pagination.nextPageUrl)"></pagination>
            </div>
        </div>

        <edit :id="id" :data="editData" :user="user" @refresh="getData()" :loading="modalLoading"
            @closeModal="editModal = false" v-if="editModal"></edit>

        <create :user="user" @refresh="getData()" @closeModal="editModal = false" v-if="createModal"></create>

        <detail :user="user" :data="editData" @refresh="getData()" :loading="modalLoading"
            @closeModal="detailModal = false" v-if="detailModal"></detail>
    </div>
</template>

<script>
import Datatable from "../../helpers/datatable.vue";
import Pagination from "../../helpers/pagination.vue";
import Edit from "./edit";
import Create from "./create.vue";
import Detail from "./details.vue";
import { mapState } from "vuex";

export default {
    props: ["user"],
    components: {
        datatable: Datatable,
        pagination: Pagination,
        Edit,
        Create,
        Detail
    },
    data() {
        let sortOrders = {};

        let columns = [
            {
                width: "20%",
                label: "Transaction Code",
                name: "transaction_code"
            },
            { width: "20%", label: "Ordered By", name: "ordered_by" },
            { width: "20%", label: "Date Ordered", name: "date_ordered" },
            { width: "20%", label: "Time Ordered", name: "time_ordered" },
            { width: "20%", label: "Date Delivered", name: "date_delivered" },
            { width: "20%", label: "Time Delivered", name: "time_delivered" },
            { width: "20%", label: "Status", name: "status" },
            { width: "20%", label: "Routine", name: "routine" }
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
                date_ordered: "",
                transaction_code: "",
                column: 0,
                dir: "desc",
                serial: "",
                show: "all"
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
            modalLoading: false,
            loading: true,
            editModal: false,
            createModal: false,
            detailModal: false,
        };
    },

    mounted() {
        this.getData();
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

        getData: _.debounce(function (url = "orders") {
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

        /**
         * Editing button trigger.
         */
        edit(id, type) {
            this.id = id;
            this.modalLoading = true
            axios.get("/orders/" + this.id + "/edit").then(response => {
                this.editData = response.data;
                this.modalLoading = false

                if (type == 'edit') {
                    this.editModal = true
                } else {
                    this.detailModal = true
                }
            });
        },

        /**
         * Adding button trigger.
         */
        add() {
            this.createModal = true
        },

        /**
         * Deleting button trigger.
         */
        del(id) {
            this.$snotify.confirm("You are about to delete. Continue?", "", {
                pauseOnHover: true,
                position: "centerCenter",
                buttons: [
                    {
                        text: "Yes",
                        action: () => {
                            axios.delete("/orders/" + id).then(response => {
                                this.getData();
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

        /**
         * Printing
         */
        printer(index) {
            axios
                .post("printer", {
                    id: index
                })
                .then(response => {
                    // window.open("/pdf", "_blank")
                    this.dialogVisible = true;
                });
        }
    }
};
</script>
