<template>
    <div>
        <!-- Modal starts -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Disposition</h5>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date Received</label>
                                        <input type="date" class="form-control"
                                            :class="{ 'is-invalid': $v.data.date_received.$error }"
                                            v-model.trim="$v.data.date_received.$model" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Received By</label>
                                        <input type="text" class="form-control"
                                            :class="{ 'is-invalid': $v.data.received_by.$error }"
                                            v-model.trim="$v.data.received_by.$model" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Serial Number</label>
                                <input type="text" class="form-control" :class="{ 'is-invalid': $v.data.serial.$error }"
                                    v-model.trim="$v.data.serial.$model" />
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Group</label>
                                    <select class="form-control" :class="{ 'is-invalid': $v.data.blood_type_id.$error }"
                                        v-model.trim="$v.data.blood_type_id.$model">
                                        <option v-for="option in bloodTypes" v-bind:item="option" :value="option.id">{{
                                            option.description }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Product</label>
                                    <select class="form-control" v-model.trim="data.blood_component_id">
                                        <option v-for="option in bloodComponents" v-bind:item="option"
                                            :value="option.id">{{ option.description }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Volume</label>
                                    <input type="number" class="form-control"
                                        :class="{ 'is-invalid': $v.data.vol.$error }"
                                        v-model.trim="$v.data.vol.$model" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Extracted</label>
                                        <input type="date" class="form-control"
                                            :class="{ 'is-invalid': $v.data.date_extracted.$error }"
                                            v-model.trim="$v.data.date_extracted.$model" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Expiry</label>
                                        <input type="date" class="form-control"
                                            :class="{ 'is-invalid': $v.data.date_expiry.$error }"
                                            v-model.trim="$v.data.date_expiry.$model" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="save()">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                            @click="cancel()">Cancel</button>
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
    data() {
        return {
            data: {
                date_received: "",
                received_by: "",
                serial: "",
                blood_type_id: "",
                blood_component_id: "",
                vol: 0,
                date_extracted: "",
                date_expiry: "",
                options: ["valid@gmail.com", "invalid email address"]
            }
        };
    },
    computed: {
        ...mapState(["bloodTypes", "bloodComponents", "user"])
    },

    mounted() {
        $("#exampleModal").modal('show')
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

            axios.post("dispositions", this.data).then(response => {
                this.$toasted.show("Successfully Added", {
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

                this.data.serial = "";
                this.data.blood_type_id = "";
                this.data.blood_component_id = "";
                this.data.vol = "";

                this.$emit("refresh");
            });
        },

        cancel() {
            this.data = [];
            $("#exampleModal").modal('hide')
            this.$emit("closeModal");
        }
    },

    validations: {
        data: {
            date_received: {
                required
            },
            received_by: {
                required
            },
            serial: {
                required
            },
            blood_type_id: {
                required
            },
            blood_component_id: {
                required
            },
            vol: {
                required,
                minValue: minValue(1)
            },
            date_extracted: {
                required
            },
            date_expiry: {
                required
            }
        }
    }
};
</script>