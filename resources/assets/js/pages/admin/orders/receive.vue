<template>
    <div>
        <!-- Modal starts -->
        <div class="modal fade" id="receiveOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Serve Order</h5>
                    </div>

                    <div class="modal-body">
                        <div v-if="loading" class="ui inverted active dimmer">
                            <div class="ui active loader"></div>
                        </div>
                        <label class="badge badge-dark" v-for="row, index in data.order_details">
                            <button type="button" class="btn btn-primary btn-fw"
                                @click="filterDisposition(row.id, row.qty, row.blood_component_id, row.blood_type_id)">({{
                                row.qty
                                }}) {{ row.blood_type.description }} ({{ row.blood_component.description
                                }})</button>
                        </label>
                        <label class="badge badge-dark">
                            <h6>TOTAL: {{ totalOrder }}</h6>
                        </label>
                        <div class="form-group">
                            <div class="row" v-if="selectDisposition.length">
                                <div class="col-md-10">
                                    <model-select :options="selectDisposition" v-model="disposition_id"
                                        placeholder="select item"></model-select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success" @click="getSerial()">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <hr>
                            <div class="row" v-if="dispositionList.length">
                                <div class="col-12">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <td>Serial</td>
                                            <td>Component</td>
                                            <td>Type</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr v-for="row, index in dispositionList">
                                            <td>{{ row.serial }}</td>
                                            <td>{{ row.component }}</td>
                                            <td>{{ row.blood }}</td>
                                            <td>
                                                <span class="badge badge-danger" @click="removeDisposition(index)"
                                                    style="cursor: pointer;">Remove</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div v-else>
                                <div class="row">
                                    <div class="col-md-12 text-center text-danger">
                                        No dispositions added
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control"
                                        :class="{ 'is-invalid': $v.details.delivery_date.$error }"
                                        v-model.trim="$v.details.delivery_date.$model" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" class="form-control"
                                        :class="{ 'is-invalid': $v.details.delivery_time.$error }"
                                        v-model.trim="$v.details.delivery_time.$model" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="save()">Save</button>
                        <button type="button" class="btn btn-light" @click="closeModal()">Close</button>
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
import { required } from "vuelidate/lib/validators";
import { ModelSelect } from "vue-search-select";

export default {
    props: ["user", "data"],
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
            counter: [],
            loading: false,
            dispositions: []
        };
    },

    mounted() {
        $('#receiveOrderModal').modal('show')

        axios.post("get-no-dispositions").then(response => {
            this.dispositions = response.data;
        });
    },

    computed: {
        totalOrder: function () {
            return _.sumBy(this.data.order_details, "qty");
        },

        /**
         * Select unique and available dispositions from list. We need this to re-display serial in selectbox
         */
        selectDisposition: function () {
            var self = this;
            var select = [];

            _.forEach(_.uniqBy(self.localDispositions, "serial"), function (e) {
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

            if (this.$v.$invalid || this.dispositionList.length == 0) {
                this.$toasted.show("Fill up required fields.", {
                    type: "error",
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

                return false;
            }
            this.loading = true
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
                    this.loading = false
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

        removeDisposition: function (index) {
            this.dispositionList.splice(index, 1);
        },

        closeModal() {
            this.$emit("closeModal");
            $('#receiveOrderModal').modal('hide')
        },

        getSerial() {
            var self = this;

            var value = _.filter(this.localDispositions, {
                id: this.disposition_id
            });

            var tot = 1;
            _.forEach(self.dispositionList, function (f) {
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
        dispositionList: {
            $each: {
                disposition_id: {
                    required
                }
            }
        }
    }
};
</script>