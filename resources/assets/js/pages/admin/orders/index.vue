<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Orders</h4>
                <div class="row">
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
                            <!-- end status -->
                            <td v-if="item.routine==1">
                                <label class="badge badge-danger">Yes</label>
                            </td>
                            <td v-else>
                                <label class="badge badge-primary">No</label>
                            </td>
                            <td v-if="item.delivery_date==null">
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-xs btn-block"
                                    data-toggle="modal"
                                    data-target="#receiveOrderModal"
                                    @click="serve(item.id)"
                                >Serve</button>
                            </td>
                            <td v-else>
                                <button
                                    type="button"
                                    class="btn btn-outline-info btn-xs btn-block"
                                    data-toggle="modal"
                                    data-target="#updateOrderModal"
                                    @click="serve(item.id)"
                                    :disabled="item.received_date!=null"
                                >Update</button>
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
        <receive
            :user="user"
            @refresh="getData()"
            :data="details"
            :dispositions="dispositions"
            @notify="refreshNotification()"
        ></receive>
        <update :user="user" @refresh="getData()" :data="details" :dispositions="dispositions"></update>
        <create-disposition @refresh="getData()"></create-disposition>
    </div>
</template>

<script>
import Datatable from "../../../helpers/datatable.vue";
import Pagination from "../../../helpers/pagination.vue";
import Receive from "./receive";
import Update from "./update";
import CreateDisposition from "../../dispositions/create";

export default {
    props: ["user"],
    components: {
        datatable: Datatable,
        pagination: Pagination,
        Receive,
        Update,
        CreateDisposition
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
                search: "",
                column: 0,
                dir: "desc",
                transaction_code: ""
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
            details: {},
            dispositions: []
        };
    },

    mounted() {
        // this.$store.dispatch("toggleLoading", true);
        this.getData();

        axios.get("get-no-dispositions").then(response => {
            this.dispositions = response.data;
        });

        setTimeout(() => {
            this.$emit("notify");
        }, 2000);
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
            this.$store.dispatch("toggleLoading", true);
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
                    this.configPagination(data.data);
                }
                this.$store.dispatch("toggleLoading", false);
            });
        },

        serve(id) {
            this.id = id;

            axios.get("/orders/" + this.id + "/edit").then(response => {
                this.details = response.data;
            });
        },

        refreshNotification() {
            this.$emit("notify");
        }
    }
};
</script>
