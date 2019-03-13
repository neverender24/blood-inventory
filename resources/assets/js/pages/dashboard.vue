<template>
    <div>
        <div class="content-wrapper">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                    <div class="card card-statistics">
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
                            <p class="mt-1 mb-0" v-for="(row, item) in actualMon">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                {{ item }} - {{row.length}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                    <div class="card card-statistics">
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
                            <p class="mt-1 mb-0" v-for="(row, item) in actualMar">
                                <i class="fa" fa-check aria-hidden="true"></i>
                                {{ item }} - {{row.length}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                    <div class="card card-statistics">
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
                            <p class="mt-1 mb-0" v-for="(row, item) in actualLaa">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                {{ item }} - {{row.length}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                    <div class="card card-statistics">
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
                            <p class="mt-1 mb-0" v-for="(row, item) in actualPan">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                {{ item }} - {{row.length}}
                            </p>
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
                                {{ item }} - {{row.length}}
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
                            <h4>Returned</h4>
                            <div class="clearfix">
                                <div class="float-left">
                                    <i class="fa fa-arrow-circle-left text-danger icon-lg"></i>
                                </div>
                                <div class="float-right">
                                    <p class="mb-0 text-right">Stock</p>
                                    <div class="fluid-container">
                                        <h3 class="font-weight-medium text-right mb-0">0</h3>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mt-3 mb-0">
                                <!-- <i class="mdi mdi-reload mr-1" aria-hidden="true"></i> Product-wise sales -->
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <create-disposition></create-disposition>
    </div>
</template>
<style>
.small {
    max-width: 600px;
    margin: 150px auto;
}
</style>
<script>
import CreateDisposition from "./dispositions/create.vue";
import LineChart from "./LineChart.js";
import { mapState } from 'vuex';

export default {
    props: ["pending_orders", "near_expire", "actual_expire"],
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
            datacollection: null
        };
    },
     computed: {
        ...mapState(["user", "pendingOrders"])
    },

    mounted() {

        this.$store.dispatch('countPendingOrders')

        axios.get("all-stocks").then(response => {
            let stocks = response.data;
            let self = this;

            _.forEach(stocks, function(s) {
                if (s.prefix == "MON") {
                    //montevista
                    self.mont = s.dispositions_count;
                    self.actualMon = _.groupBy(
                        s.dispositions,
                        "blood_type.description"
                    );
                } else if (s.prefix == "LAA") {
                    //laak
                    self.laak = s.dispositions_count;
                    self.actualLaa = _.groupBy(
                        s.dispositions,
                        "blood_type.description"
                    );
                }
                if (s.prefix == "MAR") {
                    //maragusan
                    self.mara = s.dispositions_count;
                    self.actualMar = _.groupBy(
                        s.dispositions,
                        "blood_type.description"
                    );
                }
                if (s.prefix == "PAN") {
                    //pantukan
                    self.pant = s.dispositions_count;
                    self.actualPan = _.groupBy(
                        s.dispositions,
                        "blood_type.description"
                    );
                }
            });
        });

        setTimeout(() => {
            axios.post("total-stocks", this.user).then(response => {
                this.stock = response.data;
                this.actualStocks = _.groupBy(
                    this.stock,
                    "blood_type.description"
                );

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
            });
            this.$emit("notify");
        }, 2000);
    },
    methods: {
        fillData(chart) {
            this.datacollection = {
                labels: [""],
                datasets: chart,
                tooltips: false
            };
        }
    }
};
</script>
