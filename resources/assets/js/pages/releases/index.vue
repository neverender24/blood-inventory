<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Releases</h4>
                <div class="row">
                    <div class="form-group col-2">
                        <select v-model="tableData.show" @change="getData()" class="form-control">
                            <option value="All">All</option>
                            <option value="Transfer" selected>Transfered</option>
                            <option value="Transfuse" selected>Transfused</option>
                            <option value="Sell" selected>Sold</option>
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
                        <tr v-for="item in data" :key="item.id">
                            <td>{{ item.released_by }}</td>
                            <td>{{ item.released_date }}</td>
                            <td>{{ item.released_time }}</td>
                            <td v-if="item.type == 'Transfer'">
                                <label class="badge badge-primary">{{ item.type }}</label>
                            </td>
                            <td v-if="item.type == 'Transfuse'">
                                <label class="badge badge-info">{{ item.type }}</label>
                            </td>
                            <td v-if="item.type == 'Sell'">
                                <label class="badge badge-success">{{ item.type }}</label>
                            </td>
                            <td>{{ item.remarks }}</td>
                            <td>{{ item.disposition.serial }}</td>
                            <td>{{ item.disposition.blood_type.description }}</td>
                            <td>{{ item.disposition.blood_component.description }}</td>
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
        <!-- <releases :dispositions="data"></releases> -->
    </div>
</template>

<script>
import Datatable from "../../helpers/datatable.vue";
import Pagination from "../../helpers/pagination.vue";

// import Releases from "../releases/index";

export default {
    components: {
        datatable: Datatable,
        pagination: Pagination
        // Releases
    },
    data() {
        let sortOrders = {};

        let columns = [
            { width: "10%", label: "Released By", name: "released_by" },
            { width: "10%", label: "Date", name: "released_date" },
            { width: "10%", label: "Time", name: "released_time" },
            { width: "10%", label: "Type", name: "type" },
            { width: "20%", label: "Remarks", name: "remarks" },
            { width: "20%", label: "Serial", name: "serial" },
            { width: "5%", label: "Blood Type", name: "btype" },
            { width: "5%", label: "Component", name: "component" }
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
                show: "All",
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
            editData: {}
        };
    },

    mounted() {
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

        getData(url = "get-releases") {
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

        release() {},

        edit(id, data) {
            this.id = id;

            axios.get("/dispositions/" + this.id + "/edit").then(response => {
                this.editData = response.data;
            });
        },

        del(id) {
            this.$snotify.confirm("Example body content", "Example title", {
                timeout: 5000,
                showProgressBar: true,
                closeOnClick: false,
                pauseOnHover: true,
                buttons: [
                    {
                        text: "Yes",
                        action: () => {
                            axios
                                .delete("/dispositions/" + id)
                                .then(response => {
                                    this.getData();
                                });
                        },
                        bold: false
                    },
                    { text: "No", action: () => console.log("Clicked: No") }
                ]
            });
        },

        nearExpire(date) {
            const startDate = date;
            const endDate = new Date();
            const timeDiff = new Date(startDate) - endDate;
            const days = timeDiff / (1000 * 60 * 60 * 24);

            if (days <= 10) {
                return true;
            } else {
                return false;
            }
        }
    }
};
</script>
