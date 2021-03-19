<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div v-if="loading" class="ui inverted active dimmer">
                        <div class="ui active loader"></div>
                    </div>
                    <div class="card-body">
                        <h4>Montevista</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="fa fa-hospital-o text-primary icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0" v-text="mont"></h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in actualMon">
                                <p class="mt-1 mb-0">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <hr />
                        <small class="text-danger">Near Expiry</small>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in nearExpiryMon">
                                <p class="mt-1 mb-0">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showNearExpiryDetails('mon')"
                            type="button"
                            class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#expiryModal"
                        >Details</button>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div v-if="loading" class="ui inverted active dimmer">
                        <div class="ui active loader"></div>
                    </div>
                    <div class="card-body">
                        <h4>Maragusan</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="fa fa-hospital-o text-warning icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0" v-text="mara"></h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in actualMar">
                                <p class="mt-1 mb-0">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <hr />
                        <small class="text-danger">Near Expiry</small>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in nearExpiryMar">
                                <p class="mt-1 mb-0">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showNearExpiryDetails('mar')"
                            type="button"
                            class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#expiryModal"
                        >Details</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div v-if="loading" class="ui inverted active dimmer">
                        <div class="ui active loader"></div>
                    </div>
                    <div class="card-body">
                        <h4>Laak</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="fa fa-hospital-o text-success icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ laak }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in actualLaa">
                                <p class="mt-1 mb-0">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <hr />
                        <small class="text-danger">Near Expiry</small>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in nearExpiryLaa">
                                <p class="mt-1 mb-0">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showNearExpiryDetails('laa')"
                            type="button"
                            class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#expiryModal"
                        >Details</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div v-if="loading" class="ui inverted active dimmer">
                        <div class="ui active loader"></div>
                    </div>
                    <div class="card-body">
                        <h4>Pantukan</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="fa fa-hospital-o text-info icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ pant }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in actualPan">
                                <p class="mt-1 mb-0">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <hr />
                        <small class="text-danger">Near Expiry</small>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in nearExpiryPan">
                                <p class="mt-1 mb-0">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showNearExpiryDetails('pan')"
                            type="button"
                            class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#expiryModal"
                        >Details</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <h4>Near Expiry</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="fa fa-clock-o text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3
                                        class="font-weight-medium text-right mb-0"
                                    >{{ near_expire.length }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 mb-0" v-for="(row, item) in actual_expire">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            {{ item }} ({{row.length}})
                        </p>
                    </div>
                </div>
            </div>

            <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                    <div class="card card-statistics">
                        <div class="card-body">
                            <h4>Expired</h4>
                            <div class="clearfix">
                                <div class="float-left">
                                    <i class="fa fa-clock-o text-danger icon-lg"></i>
                                </div>
                                <div class="float-right">
                                    <p class="mb-0 text-right">Stock</p>
                                    <div class="fluid-container">
                                        <h3
                                            class="font-weight-medium text-right mb-0"
                                        >{{ expired.length }}</h3>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-1 mb-0" v-for="(row, item) in actual_near_expire">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                {{ item }} ({{row.length}})
                            </p>
                        </div>
                    </div>
            </div>-->
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <h4>Pending Orders</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="fa fa-list text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3
                                        class="font-weight-medium text-right mb-0"
                                        v-text="pendingOrders"
                                    ></h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <!-- <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Weekly Sales -->
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <h4>Total</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="fa fa-tint text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3
                                        class="font-weight-medium text-right mb-0"
                                        v-text="stock.length"
                                    ></h3>
                                </div>
                            </div>
                        </div>
                        <!-- <p class="mt-1 mb-0" v-for="(row, item) in actualStocks">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                {{ item }} - {{row.length}}
                        </p>-->
                        <line-chart :chart-data="datacollection"></line-chart>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="expiryModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Near Expiry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <td>Type</td>
                                    <td>Component</td>
                                    <td>Serial</td>
                                    <td>Expiry Date</td>
                                </tr>
                            </thead>
                            <tbody v-for="(row1, item1) in showExpiries">
                                <tr v-for="(row2, item2) in row1">
                                    <td>{{ row2.blood_type.description }}</td>
                                    <td>{{ row2.blood_component.description }}</td>
                                    <td>{{row2.serial}}</td>
                                    <td>{{row2.date_expiry}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <create-disposition></create-disposition>
    </div>
</template>
<script>
import CreateDisposition from "./dispositions/create.vue";
import LineChart from "./LineChart.js";
import { mapState } from "vuex";

export default {
    props: [
        "pending_orders",
        "near_expire",
        "actual_expire",
        "expired",
        "actual_near_expire"
    ],
    components: {
        CreateDisposition,
        LineChart
    },
    data() {
        return {
            mont: 0,
            laak: 0,
            mara: 0,
            pant: 0,
            stock: 0,
            showExpiries: [],
            nearExpiryMon: [],
            nearExpiryLaa: [],
            nearExpiryMar: [],
            nearExpiryPan: [],
            actualMon: [],
            actualLaa: [],
            actualMar: [],
            actualPan: [],
            actualStocks: [],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    scales: {
                        xAxes: [{ ticks: { mirror: true } }]
                    }
                }
            },
            datacollection: null,
            loading: false
        };
    },
    computed: {
        ...mapState(["user", "pendingOrders"])
    },

    async mounted() {
        this.$store.dispatch("toggleLoading", true);
        this.$store.dispatch("countPendingOrders");
        this.loading = !this.loading;
        await axios.get("all-stocks").then(response => {
            let stocks = response.data;
            let self = this;
            var nearExpiryMon = [];
            var nearExpiryLaa = [];
            var nearExpiryMar = [];
            var nearExpiryPan = [];

            _.forEach(stocks, function(stock) {
                if (stock.prefix == "MON") {
                    //montevista
                    self.mont = stock.dispositions_count;
                    self.actualMon = _.groupBy(
                        stock.dispositions,
                        "blood_type.description"
                    );
                    _.forEach(stock.dispositions, function(dispositions) {
                        if (self.nearExpire(dispositions.date_expiry)) {
                            nearExpiryMon.push(dispositions);
                        }
                    });
                    self.nearExpiryMon = _.groupBy(
                        nearExpiryMon,
                        "blood_type.description"
                    );
                } else if (stock.prefix == "LAA") {
                    //laak
                    self.laak = stock.dispositions_count;
                    self.actualLaa = _.groupBy(
                        stock.dispositions,
                        "blood_type.description"
                    );
                    _.forEach(stock.dispositions, function(dispositions) {
                        if (self.nearExpire(dispositions.date_expiry)) {
                            nearExpiryLaa.push(dispositions);
                        }
                    });
                    self.nearExpiryLaa = _.groupBy(
                        nearExpiryLaa,
                        "blood_type.description"
                    );
                } else if (stock.prefix == "MAR") {
                    //maragusan
                    self.mara = stock.dispositions_count;
                    self.actualMar = _.groupBy(
                        stock.dispositions,
                        "blood_type.description"
                    );
                    _.forEach(stock.dispositions, function(dispositions) {
                        if (self.nearExpire(dispositions.date_expiry)) {
                            nearExpiryMar.push(dispositions);
                        }
                    });
                    self.nearExpiryMar = _.groupBy(
                        nearExpiryMar,
                        "blood_type.description"
                    );
                } else if (stock.prefix == "PAN") {
                    //pantukan
                    self.pant = stock.dispositions_count;
                    self.actualPan = _.groupBy(
                        stock.dispositions,
                        "blood_type.description"
                    );
                    _.forEach(stock.dispositions, function(dispositions) {
                        if (self.nearExpire(dispositions.date_expiry)) {
                            nearExpiryPan.push(dispositions);
                        }
                    });
                    self.nearExpiryPan = _.groupBy(
                        nearExpiryPan,
                        "blood_type.description"
                    );
                }
            });
            this.loading = !this.loading;
        });

        await axios.post("total-stocks", this.user).then(response => {
            this.stock = response.data;
            this.actualStocks = _.groupBy(this.stock, "blood_type.description");

            let self = this;
            let chart = [];

            var dynamicColors = function() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                return "rgb(" + r + "," + g + "," + b + ")";
            };

            _.forEach(this.actualStocks, function(value, key) {
                chart.push({ 
                    label: key,
                    backgroundColor: dynamicColors(),
                    data: [value.length]
                });
            });

            this.fillData(chart);
            this.$emit("notify");
        });
    },
    methods: {
        fillData(chart) {
            this.datacollection = {
                labels: [""],
                datasets: chart,
                tooltips: false
            };
        },

        nearExpire(date) {
            const startDate = date;
            const endDate = new Date();
            const timeDiff = new Date(startDate) - endDate;
            const days = timeDiff / (1000 * 60 * 60 * 24);

            if (days <= 10 && days >= 0) {
                return true;
            }

            return false;
        },

        showNearExpiryDetails(details) {
            if (details == "mon") {
                this.showExpiries = this.nearExpiryMon;
            } else if (details == "mar") {
                this.showExpiries = this.nearExpiryMar;
            } else if (details == "laa") {
                this.showExpiries = this.nearExpiryLaa;
            } else if (details == "pan") {
                this.showExpiries = this.nearExpiryPan;
            }
        }
    }
};
</script>
