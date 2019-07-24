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
                        <!-- new -->
                        <!-- <label class="badge badge-danger" v-for="row, index in getOrderCount">
                            {{ row.qty }} -
                            <button
                                v-if="row.qty!=getTotalOrder[row.id] && row.qty!=0"
                                @click="addDisposition(row.qty, row.order_id, row.component_id, row.blood_type_id)"
                            >{{ row.blood_type_description }} ({{ row.component_description }})</button>
                        </label>-->

                        <!-- old -->
                        <label class="badge badge-danger" v-for="d,index in data.order_details">
                            <button
                                class="btn btn-primary"
                                v-if="d.qty!=getTotalOrder[d.id] && d.qty!=0"
                                @click="addDisposition(d.qty, d.id, d.blood_component.id, d.blood_type.id, index)"
                            >({{ d.qty }}) {{ d.blood_type.description }} ({{ d.blood_component.description }})</button>
                        </label>
                        {{ totalBlood }}
                        <form class="forms-sample">
                            <!-- <div class="form-group" v-for="(o, index) in $v.orders.$each.$iter">
                                <model-select
                                    :options="selectDisposition"
                                    v-model.trim="o.disposition_id.$model"
                                    placeholder="select item"
                                ></model-select>
                            </div>-->

                            <div class="form-group">
                                <model-select
                                    :options="selectDisposition"
                                    v-model="dispositions2"
                                    placeholder="select item"
                                ></model-select>
                                <i class="fa fa-check" @click="getSerial()"></i>
                                <table
                                    class="table table-bordered table-sm"
                                    v-if="dispositionList.length"
                                >
                                    <tr>
                                        <td>Serial</td>
                                        <td>Component</td>
                                        <td>Type</td>
                                    </tr>
                                    <tr v-for="row,index in dispositionList">
                                        <td>{{row.serial}}</td>
                                        <td>{{row.component}}</td>
                                        <td>{{row.type}}</td>
                                    </tr>
                                </table>
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
                                        />
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
                                        />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="save()">Save</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
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
import { ModelSelect } from "vue-search-select";

export default {
    props: ["user", "data", "dispositions"],
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
            details: {
                delivery_date: "",
                delivery_time: ""
            },
            local_dispositions: [],
            dispositions2: "",
            dispositionList: [],
            order_detail_id: 0
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

            var result = _.countBy(this.dispositionList, "order_detail_id");
            return result;
        },
        /**
         * Select unique and available dispositions from list
         */
        selectDisposition: function() {
            var self = this;
            var select = [];

            _.forEach(_.uniqBy(self.local_dispositions, "serial"), function(e) {
                select.push({
                    value: e.id,
                    text: e.serial
                });
            });

            return select;
        },
        /**
         *
         */
        getOrderCount: function() {
            var buttons = [];

            _.forEach(this.data.order_details, function(e) {
                buttons.push({
                    component_id: e.blood_component_id,
                    component_description: e.blood_component.description,
                    blood_type_id: e.blood_type_id,
                    blood_type_description: e.blood_type.description,
                    qty: e.qty,
                    order_id: e.id
                });
            });

            return buttons;
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

        addDisposition: function(qty, id, component, type) {
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

            //console.log(component, type);
            var test = _.filter(this.local_dispositions, {
                blood_component_id: component,
                blood_type_id: type
            });

            this.local_dispositions = _.uniqBy(test, "serial");

            this.order_detail_id = id;

            this.orders.push({
                disposition_id: "",
                order_detail_id: id
            });
        },

        removeDisposition: function(index) {
            this.orders.splice(index, 1);
        },

        getSerial() {
            this.selectDisposition = [];

            var value = _.filter(this.local_dispositions, {
                id: this.dispositions2
            });

            return this.dispositionList.push({
                serial: value[0].serial,
                component: value[0].blood_component.description,
                type: value[0].blood_type.description,
                disposition_id: value[0].id,
                order_detail_id: this.order_detail_id
            });
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