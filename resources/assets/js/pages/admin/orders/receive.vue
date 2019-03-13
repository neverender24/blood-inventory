<template>
    <div>
        <!-- Modal starts -->
        <div
            class="modal fade"
            id="receiveOrderModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Serve Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <label class="badge badge-danger" v-for="d in data.order_details">
                            {{ d.qty }} -
                            <button
                                v-if="d.qty!=getTotalOrder[d.id] && d.qty!=0"
                                @click="addDisposition(d.qty, d.id)"
                            >{{ d.blood_type.description }} ({{ d.blood_component.description }})</button>
                        </label>
                        {{ totalBlood }}
                        <form class="forms-sample">
                            <div class="form-group" v-for="(o, index) in $v.orders.$each.$iter">
                                <select
                                    v-model.trim="o.disposition_id.$model"
                                    class="form-control"
                                    :class="{ 'is-invalid': o.disposition_id.$error }"
                                >
                                    <option value="0">-- Select --</option>
                                    <option
                                        v-for="(value,key) in local_dispositions"
                                        :value="value.id"
                                    >{{ value.serial }}</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.details.delivery_date.$error }"
                                            v-model.trim="$v.details.delivery_date.$model"
                                        >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <input
                                            type="time"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.details.delivery_time.$error }"
                                            v-model.trim="$v.details.delivery_time.$model"
                                        >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="save()">Save</button>
                        <button
                            type="button"
                            class="btn btn-light"
                            data-dismiss="modal"
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
import { required, minLength, minValue } from "vuelidate/lib/validators";
export default {
    props: ["user", "data", "dispositions"],
    data() {
        return {
            orders: [],
            disposition_id: "",
            blood: [],
            components: [],
            totalBlood: 0,
            details: {
                delivery_date: "",
                delivery_time: ""
            },
            local_dispositions: []
        };
    },

    computed: {
        getTotalOrder: function() {
            let total = 0;
            let blood = [];
            let components = [];

            _.forEach(this.data.order_details, function(e) {
                total += parseInt(e.qty);
                blood.push(e.blood_type_id);
                components.push(e.blood_component_id);
            });

            this.components = components;
            this.blood = blood;
            this.totalBlood = total;

            var result = _.countBy(this.orders, "order_detail_id");
            return result;
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

            axios
                .post("serve", {
                    orders: this.orders,
                    id: this.data.id,
                    details: this.details
                })
                .then(response => {
                    this.$toasted.show("Successfully Added", {
                        theme: "bubble",
                        position: "top-right",
                        duration: 5000
                    });
                    this.$emit("notify");
                    this.$emit("refresh");
                });
        },

        addDisposition: function(qty, id) {
            let disp = [];
            let blood = this.blood;
            let components = this.components;

            this.local_dispositions = _.filter(this.dispositions, function(e) {
                _.forEach(blood, function(element) {
                    if (element == e.blood_type_id) {
                        disp.push(e);
                    }
                });
            });
            this.local_dispositions = disp;
            disp = [];

            this.local_dispositions = _.filter(
                this.local_dispositions,
                function(e) {
                    _.forEach(components, function(element) {
                        if (element == e.blood_component_id) {
                            disp.push(e);
                        }
                    });
                }
            );
            this.local_dispositions = disp;

            this.orders.push({
                disposition_id: "",
                order_detail_id: id
            });
        },

        removeDisposition: function(index) {
            this.orders.splice(index, 1);
        }
    },
    validations: {
        details: {
            delivery_date: {
                required
            },
            delivery_time: {
                required
            }
        },
        orders: {
            $each: {
                disposition_id: {
                    required
                }
            }
        }
    }
};
</script>