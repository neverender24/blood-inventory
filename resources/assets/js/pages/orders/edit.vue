<template>
    <div>
        <!-- Modal starts -->
        <div
            class="modal fade"
            id="editOrderModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="loading" class="ui inverted active dimmer">
                            <div class="ui active loader"></div>
                        </div>
                        <form
                            class="forms-sample needs-validation"
                            @submit.prevent="submit"
                            novalidate
                        >
                            <div class="form-group">
                                <label>Transaction Code</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': $v.list.transaction_code.$error }"
                                    v-model.trim="$v.list.transaction_code.$model"
                                />
                            </div>
                            <div class="row" v-if="user.role!='Administrator'">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Order Date</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.list.order_date.$error }"
                                            v-model.trim="$v.list.order_date.$model"
                                        />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Order Time</label>
                                        <input
                                            type="time"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.list.order_time.$error }"
                                            v-model.trim="$v.list.order_time.$model"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            checked
                                            v-model="list.routine"
                                            value="1"
                                        /> Routine
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </div>

                            <hr />
                            <div class="row">
                                <div class="col-3 text-center">
                                    <label>Group</label>
                                </div>
                                <div class="col-4 text-center">
                                    <label>Product</label>
                                </div>
                                <div class="col-3 text-center">
                                    <label>Qty</label>
                                </div>
                                <div class="col-1 text-center">
                                    <label></label>
                                </div>
                            </div>
                            <div
                                class="row"
                                v-for="(row, index) in $v.list.order_details.$each.$iter"
                            >
                                <div class="col-3">
                                    <div class="form-group">
                                        <select
                                            class="form-control form-control"
                                            :class="{ 'is-invalid': row.blood_type_id.$error }"
                                            v-model.trim="row.blood_type_id.$model"
                                        >
                                            <option value></option>
                                            <option
                                                v-for="option in bloodTypes"
                                                v-bind:item="option"
                                                :value="option.id"
                                            >{{ option.description }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <select
                                            class="form-control form-control"
                                            :class="{ 'is-invalid': row.blood_component_id.$error }"
                                            v-model.trim="row.blood_component_id.$model"
                                        >
                                            <option value></option>
                                            <option
                                                v-for="option in bloodComponents"
                                                v-bind:item="option"
                                                :value="option.id"
                                            >{{ option.description }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <input
                                            type="number"
                                            class="form-control"
                                            :class="{ 'is-invalid': row.qty.$error }"
                                            v-model.trim="row.qty.$model"
                                        />
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-group">
                                        <svg @click="removeDetails(index)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x-circle text-danger" viewBox="0 0 16 16">
                                          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                          <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="addDetails()">Add</button>
                        <button type="button" class="btn btn-success" @click="save()">Save</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Ends -->
    </div>
</template>

<script>
import { required, minValue } from "vuelidate/lib/validators";
import { mapState } from "vuex";

export default {
    props: ["id", "data", 'loading'],
    data() {
        return {
            list: {
                order_date: "",
                order_time: "",
                order_details: [
                    {
                        blood_type_id: "",
                        blood_component_id: "",
                        qty: 0
                    }
                ],
                transaction_code: ""
            },
            loads: false
        };
    },

    watch: {
        data: function() {
            let self = this;
            self.list = this.data;
        },
        loading(value) {
            console.log(value)
            this.loads = value
            // return loads
        }
    },

    computed: {
        ...mapState(["bloodTypes", "bloodComponents", "user"])
    },

    methods: {
        save() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.$toasted.show("Fix some errors", {
                    type: "error",
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

                return false;
            }

            axios.patch("/orders/" + this.id, this.list).then(response => {
                this.$toasted.show("Successfully Updated", {
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });
                this.list = response.data;
                this.$emit("refresh");
            });
        },

        addDetails() {
            this.list.order_details.push({
                blood_type_id: "",
                blood_component_id: "",
                qty: ""
            });
        },

        removeDetails(index) {
            this.list.order_details.splice(index, 1);
        }
    },

    validations: {
        list: {
            transaction_code: {
                required
            },
            order_date: {
                required
            },
            order_time: {
                required
            },
            order_details: {
                $each: {
                    blood_type_id: {
                        required
                    },
                    blood_component_id: {
                        required
                    },
                    qty: {
                        required,
                        minValue: minValue(1)
                    }
                }
            }
        },
        loads: false
    }
};
</script>