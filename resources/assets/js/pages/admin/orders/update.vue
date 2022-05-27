<template>
    <div>
        <!-- Modal starts -->
        <div
            class="modal fade"
            id="updateOrderModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div v-if="loading" class="ui inverted active dimmer">
                            <div class="ui active loader"></div>
                        </div>
                        <label
                            class="badge badge-danger"
                            v-for="details in data.order_details"
                            v-if="details.qty!=details.dispositions.length &&
                                    details.qty!=0 &&
                                    getTotalOrder[details.id]!=details.qty ||
                                    Object.keys(getTotalOrder).length !== 0 &&
                                    getTotalOrder.constructor === Object &&
                                    getTotalOrder[details.id]!=details.qty"
                        >
                            {{ details.qty }} -
                            <button
                                @click="addDisposition(details.qty, details.id)"
                            >{{ details.blood_type.description }} ({{ details.blood_component.description }})</button>
                        </label>

                        <form class="forms-sample">
                            <div v-if="setOrders">
                                <div class="row" v-for="(o,index) in orders">
                                    <div class="col-md-2">
                                        <span
                                             v-if="$v.data.$model.received_date == null"
                                            class="text-danger"
                                            style="cursor: pointer;"
                                            @click="removeDispositionOrder(index)"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <p>{{o.serial}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>{{o.type}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>{{o.component}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-for="(row, index) in $v.new_orders.$each.$iter">
                                <div class="col-md-10">
                                    <model-select
                                        v-model.trim="row.disposition_id.$model"
                                        :options="localDispositions"
                                        class="ui search dropdown form-control"
                                        v-if="setDispositions && addNewDisposition"
                                    >
                                
                                    </model-select>
                                </div>
                                <div class="col-md-2">
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        @click="removeDisposition(index)"
                                    >
                                        <i>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                              <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                            </svg>
                                        </i>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.delivery_date.$error }"
                                            v-model.trim="$v.data.delivery_date.$model"
                                        >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <input
                                            type="time"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.delivery_time.$error }"
                                            v-model.trim="$v.data.delivery_time.$model"
                                        >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="save()" v-if="$v.data.$model.received_date == null">Save</button>
                        <button
                            type="button"
                            class="btn btn-light"
                            data-dismiss="modal"
                            @click="cancel()"
                        >Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Ends -->
    </div>
</template>

<style>
.form-check .form-check-label .input-helper:before {
    border: 1px black solid;
}
</style>

<script>
import { ModelSelect } from "vue-search-select";
import { required, minLength, minValue } from "vuelidate/lib/validators";
export default {
    props: ["user", "data", "dispositions", "loading"],
    components: {
        ModelSelect
    },
    data() {
        return {
            orders: [],
            disposition_id: "",
            blood: [],
            components: [],
            totalBlood: 0,
            details: {},
            old: [],
            addNewDisposition: false,
            new_orders: [],
            final_orders: [],
            localDispositions: []
        };
    },
    mounted() {
         $(function(){
            $(".ui.search.dropdown").dropdown({
                fullTextSearch: true,
            });
        })
    },

    computed: {
        getTotalOrder: function() {
            let total = 0;
            let blood = [];
            let components = [];
            let old = [];

            _.forEach(this.data.order_details, function(e) {
                total += parseInt(e.qty);
                blood.push(e.blood_type_id);
                components.push(e.blood_component_id);
                _.forEach(e.dispositions, function(d) {
                    old.push({
                        order_detail_id: e.id,
                        disposition_id: d.id,
                    });
                });
            });

            this.components = components;
            this.blood = blood;
            this.totalBlood = total;
            this.old = old;

            this.final_orders = _.concat(this.orders, this.new_orders);

            var result = _.countBy(this.final_orders, "order_detail_id");

            return result;
        },

        setDispositions: function() {
            let disp = [];
            let blood = this.blood;
            let components = this.components;
            let dispositions = [];

            dispositions = _.filter(this.dispositions, function(e) {
                _.forEach(blood, function(element) {
                    if (element == e.blood_type_id) {
                        disp.push(e);
                    }
                });
            });
            this.localDispositions = disp;
            disp = [];

            dispositions = _.filter(this.localDispositions, function(e) {
                _.forEach(components, function(element) {
                    if (element == e.blood_component_id) {
                        
                        let dup = _.findIndex(disp, ["value", e.id])

                        if (dup == -1) {
                            disp.push({
                                value: e.id,
                                text: e.serial
                            });
                        }
                    }
                });
            });

            this.localDispositions = disp;

            return true;
        },

        setOrders: function() {
            let orders = [];
            let user = this.data.user;

            _.forEach(this.data.order_details, function(e) {
                _.forEach(e.dispositions, function(d) {
                    orders.push({
                        disposition_id: d.pivot.disposition_id,
                        order_detail_id: d.pivot.order_detail_id,
                        serial: d.serial,
                        type: e.blood_type.description,
                        component: e.blood_component.description,
                    });
                });
            });

            this.orders = orders;

            return true;
        }
    },

    methods: {
        save() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.$toasted.show("Fill up required fields.", {
                    type: "error",
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

                return false;
            }
            let self = this
            
            this.old = _.forEach(this.old, function(e) {
                e["blood_station_id"] = self.data.user.blood_station_id
            })

            this.final_orders = _.forEach(this.final_orders, function(e) {
                e["blood_station_id"] = self.data.user.blood_station_id
            })

            axios
                .patch("/serve/" + this.data.id, {
                    orders: this.final_orders,
                    details: this.data,
                    old: this.old
                })
                .then(response => {
                    this.$toasted.show("Successfully Added", {
                        theme: "bubble",
                        position: "top-right",
                        duration: 5000
                    });

                    this.$emit("refresh");
                });
        },

        addDisposition: function(qty, id) {
            this.new_orders.push({
                disposition_id: "",
                order_detail_id: id
            });

            this.addNewDisposition = true;
        },

        removeDisposition: function(index) {
            this.new_orders.splice(index, 1);
        },

        removeDispositionOrder: function(index) {
            this.orders.splice(index, 1);
        },

        cancel() {
            this.new_orders = [];
        }
    },
    validations: {
        data: {
            delivery_date: {
                required
            },
            delivery_time: {
                required
            }
        },
        new_orders: {
            $each: {
                disposition_id: {
                    required
                }
            }
        }
    }
};
</script>