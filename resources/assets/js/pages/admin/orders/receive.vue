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
                        <label class="badge badge-dark">
                            <button
                                type="button"
                                v-for="row,index in data.order_details"
                                class="btn btn-primary btn-fw"
                                @click="filterDisposition(row.id, row.qty, row.blood_component_id, row.blood_type_id)"
                            >({{ row.qty }}) {{ row.blood_type.description }} ({{ row.blood_component.description }})</button>
                        </label>
                        <label class="badge badge-dark">
                            <h6>TOTAL: {{ totalOrder }}</h6>
                        </label>
                        <form class="forms-sample">
                            <div class="form-group">
                                <div class="row" v-if="selectDisposition.length">
                                    <div class="col-10">
                                        <model-select
                                            :options="selectDisposition"
                                            v-model="disposition_id"
                                            placeholder="select item"
                                        ></model-select>
                                    </div>
                                    <div class="col-2">
                                        <button
                                            class="btn btn-icons btn-rounded btn-success"
                                            @click="getSerial()"
                                        >
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row" v-if="dispositionList.length">
                                    <div class="col-12">
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <td>Serial</td>
                                                <td>Component</td>
                                                <td>Type</td>
                                                <td>Action</td>
                                            </tr>
                                            <tr v-for="row,index in dispositionList">
                                                <td>{{row.serial}}</td>
                                                <td>{{row.component}}</td>
                                                <td>{{row.blood}}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-danger"
                                                        @click="removeDisposition(index)"
                                                    >Remove</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
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
            details: {
                delivery_date: "",
                delivery_time: ""
            },
            disposition_id: 0,
            order_detail_id: 0,
            dispositionList: [],
            localDispositions: [],
            counter: []
        };
    },

    computed: {
        totalOrder: function() {
            return _.sumBy(this.data.order_details, "qty");
        },

        /**
         * Select unique and available dispositions from list. We need this to re-display serial in selectbox
         */
        selectDisposition: function() {
            var self = this;
            var select = [];

            _.forEach(_.uniqBy(self.localDispositions, "serial"), function(e) {
                select.push({
                    value: e.id,
                    text: e.serial
                });
            });

            return select;
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
                    orders: this.dispositionList,
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

        filterDisposition(id, qty, component, type) {
            this.order_detail_id = id;

            this.counter = {
                component: component,
                type: type,
                qty: qty
            };

            this.localDispositions = _.filter(this.dispositions, {
                blood_component_id: component,
                blood_type_id: type
            });
        },

        removeDisposition: function(index) {
            this.dispositionList.splice(index, 1);
        },

        getSerial() {
            var self = this;

            var value = _.filter(this.localDispositions, {
                id: this.disposition_id
            });

            var tot = 1;
            _.forEach(self.dispositionList, function(f) {
                if (
                    f.component_id == self.counter.component &&
                    f.type == self.counter.type
                ) {
                    tot++;
                }
            });

            if (this.counter.qty < tot) {
                this.$toasted.show("Maximum order reached", {
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

                return false;
            }

            var duplicateDisposition = _.filter(this.dispositionList, {
                disposition_id: this.disposition_id
            });

            if (duplicateDisposition.length != 0) {
                this.$toasted.show("Already added", {
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

                return false;
            }

            return this.dispositionList.push({
                serial: value[0].serial,
                component: value[0].blood_component.description,
                component_id: value[0].blood_component.id,
                type: value[0].blood_type.description,
                disposition_id: value[0].id,
                order_detail_id: this.order_detail_id,
                type: value[0].blood_type.id,
                blood: value[0].blood_type.description
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