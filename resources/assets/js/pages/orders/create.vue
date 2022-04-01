<template>
    <div>
        <!-- Modal starts -->
        <div
            class="modal fade"
            id="orderModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                                <div class="col-4 text-center">
                                    <label>Group</label>
                                </div>
                                <div class="col-4 text-center">
                                    <label>Product</label>
                                </div>
                                <div class="col-4 text-center">
                                    <label>Qty</label>
                                </div>
                            </div>
                            <div
                                class="row"
                                v-for="(row, index) in $v.list.order_details.$each.$iter"
                            >
                                <div class="col-4">
                                    <div class="form-group">
                                        <select
                                            class="form-control"
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
                                            class="form-control"
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
                                        <i
                                            class="text-danger"
                                            @click="removeDetails(index)"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                              <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                              <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                            </svg>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="addDetails()">Add</button>
                        <button type="button" class="btn btn-success" @click="save()">Save</button>
                        <button
                            type="button"
                            class="btn btn-light"
                            data-dismiss="modal"
                            @click="cancel()"
                        >Close</button>
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
import { mapState } from "vuex";

export default {
    props: ["current_date", "current_time", "code"],
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
            }
        };
    },
    computed: {
        ...mapState(["bloodTypes", "bloodComponents", "user"])
    },

    watch: {
        current_date: function() {
            let self = this;
            self.list.order_date = this.current_date;
        },
        current_time: function() {
            let self = this;
            self.list.order_time = this.current_time;
        },
        code: function() {
            let self = this;
            self.list.transaction_code = this.code;
        }
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

            axios.post("orders", this.list).then(response => {
                this.$toasted.show("Successfully Added", {
                    type: "success",
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

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
        },

        cancel() {}
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
        }
    }
};
</script>