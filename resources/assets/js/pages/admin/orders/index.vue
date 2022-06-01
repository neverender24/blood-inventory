<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div v-if="loading" class="ui inverted active dimmer">
                    <div class="ui active loader"></div>
                </div>
                <h4 class="card-title">Orders</h4>
                <div class="row">
                    <div class="form-group col-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                          <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                                        </svg>
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
                            <td v-if="item.received_date==null">
                                <span
                                    type="button"
                                    data-toggle="modal"
                                    data-target="#receiveOrderModal"
                                    @click="serve(item.id)"
                                ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                                  <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg></span>
                                <span
                                    type="button"
                                    @click="edit(item.id)"
                                    data-toggle="modal"
                                    data-target="#editOrderModal"
                                ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg></span>
                                <span
                                    type="button"
                                    class="text-danger"
                                    @click="del(item.id)"
                                ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                </svg></span>
                            </td>
                            <td v-else>
                                <span
                                    type="button"
                                    class="tezxt-danger"
                                    data-toggle="modal"
                                    data-target="#updateOrderModal"
                                    @click="serve(item.id)"
                                    :disabled="item.received_date!=null"
                                ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg></span>
                                <!-- <span
                                    type="button"
                                    @click="del(item.id)"
                                ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                </svg></span> -->
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
        <update :user="user" @refresh="getData()" :data="details" :dispositions="dispositions" :loading="modalLoading"></update>
        <create-disposition @refresh="getData()"></create-disposition>
        <edit :id="id" :data="editData" :user="user" @refresh="getData()" :loading="modalLoading"></edit>
    </div>
</template>

<script>
import Datatable from "../../../helpers/datatable.vue";
import Pagination from "../../../helpers/pagination.vue";
import Receive from "./receive";
import Update from "./update";
import CreateDisposition from "../../dispositions/create";
import Edit from "../../orders/edit";

export default {
    props: ["user"],
    components: {
        datatable: Datatable,
        pagination: Pagination,
        Receive,
        Update,
        CreateDisposition,
        Edit
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
            dispositions: [],
            editData: {},
            loading: false,
            modalLoading: false,
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

        getData: _.debounce(function(url = "orders") {

            this.loading = true
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
                    this.configPagination(data.data);
                }
                this.loading = false
            });
        }, 500),

        /**
         * Editing button trigger.
         */
        edit(id, data) {
            this.id = id;
            this.modalLoading = true
            axios.get("/orders/" + this.id + "/edit").then(response => {
                this.editData = response.data;
                this.modalLoading = false
            });
        },

        serve(id) {
            this.id = id;
            this.modalLoading = true
            axios.get("/orders/" + this.id + "/edit").then(response => {
                this.details = response.data;
                this.modalLoading = false
            });
        },

        refreshNotification() {
            this.$emit("notify");
        },

        /**
         * Deleting button trigger.
         */
        del(id) {
             let text = "WARNING!\nAre you sure you want to delete the order? This will delete all details attached to it.";
              if (confirm(text) == true) {
                 axios.delete("/orders/" + id).then(response => {
                    this.getData();
                    this.$toasted.show("Successfully Deleted", {
                        type: "success",
                        theme: "bubble",
                        position: "top-right",
                        duration: 5000
                    });
                });
            }
        },
        
    }
};
</script>
