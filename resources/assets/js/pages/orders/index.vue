<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Orders</h4>

                <div class="row">
                    <div class="form-group col-1">
                        <button
                            class="btn btn-success btn-block"
                            data-toggle="modal"
                            data-target="#orderModal"
                            @click="add()"
                        >
                            <i class="mdi mdi-plus"></i>
                        </button>
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
                                v-model="tableData.date_ordered"
                                @input="getData()"
                            />
                        </div>
                    </div>
                    <div class="form-group col-3">
                        <input
                            type="text"
                            class="form-control"
                            v-model="tableData.transaction_code"
                            placeholder="Enter transaction code"
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
                        <tr v-for="item in data" :key="item.serial">
                            <td>{{ item.transaction_code }}</td>
                            <td>{{ item.user.name }}</td>
                            <td>{{ item.order_date }}</td>
                            <td>{{ item.order_time }}</td>
                            <td>{{ item.delivery_date }}</td>
                            <td>{{ item.delivery_time }}</td>
                            <!-- Check Status -->
                            <td v-if="item.received_date!=null">
                                <label class="badge badge-success">Completed</label>
                            </td>
                            <td v-else-if="item.delivery_date!=null">
                                <label class="badge badge-info">Delivered</label>
                            </td>
                            <td v-else>
                                <label class="badge badge-warning">Pending</label>
                            </td>

                            <td v-if="item.routine==1">
                                <label class="badge badge-danger">Yes</label>
                            </td>
                            <td v-else>
                                <label class="badge badge-primary">No</label>
                            </td>
                            <td v-if="item.delivery_date==null">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        @click="edit(item.id)"
                                        data-toggle="modal"
                                        data-target="#editOrderModal"
                                    >Edit</button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger"
                                        @click="del(item.id)"
                                    >Delete</button>
                                </div>
                            </td>
                            <td v-else>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        @click="edit(item.id)"
                                        data-toggle="modal"
                                        data-target="#detailsModal"
                                    >Details</button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        @click="printer(item.id)"
                                    >Print</button>
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

        <edit :id="id" :data="editData" :user="user" @refresh="getData()"></edit>

        <create
            :user="user"
            :current_date="current_date"
            :current_time="current_time"
            :code="code"
            @refresh="getData()"
        ></create>

        <detail :user="user" :data="editData" @refresh="getData()"></detail>
    </div>
</template>

<script>
import Datatable from "../../helpers/datatable.vue";
import Pagination from "../../helpers/pagination.vue";
import Edit from "./edit";
import Create from "./create.vue";
import Detail from "./details.vue";
import { mapActions } from "vuex";

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
            current_date: "",
            current_time: "",
            code: ""
        };
    },

    mounted() {
        this.$store.dispatch("loadBloodTypes");
        this.$store.dispatch("loadBloodComponents");

        this.getData();
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

        getData(url = "orders") {
            this.getDateTime();

            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
                    this.configPagination(data.data);
                }
            });
        },

        /**
         * Editing button trigger.
         */
        edit(id, data) {
            this.id = id;

            axios.get("/orders/" + this.id + "/edit").then(response => {
                this.editData = response.data;
            });
        },

        /**
         * Adding button trigger.
         */
        add() {
            axios.post("generate-code").then(response => {
                this.code = response.data;
            });
            this.getDateTime();
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
         * getting the current date and time.
         * This will be used as default values to create inputs.
         */
        getDateTime() {
            var date = new Date();
            this.current_date = date.toISOString().slice(0, 10);
            this.current_time =
                this.leadZero(date.getHours()) +
                ":" +
                this.leadZero(date.getMinutes()) +
                ":00";
        },

        /**
         * Add leading zero to time to be accepted with the browser as default.
         */
        leadZero(time) {
            if (parseInt(time) < 10) {
                return "0" + time;
            }

            return time;
        },

        /**
         * Printing
         */
        printer(index) {
            this.loading = !this.loading;
            axios
                .post("printer", {
                    id: index
                })
                .then(response => {
                    this.loading = !this.loading;
                    // window.open("/pdf", "_blank")
                    this.dialogVisible = true;
                });
        }
    }
};
</script>
