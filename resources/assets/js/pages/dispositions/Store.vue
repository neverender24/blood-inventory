<template>
    <div>
        <!-- Modal starts -->
        <div
            class="modal fade"
            id="storeModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Disposition</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date Received</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.date_received.$error }"
                                            v-model.trim="$v.data.date_received.$model"
                                        />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Received By</label>
                                        <!-- <input
                                            type="text"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.received_by.$error }"
                                            v-model.trim="$v.data.received_by.$model"
                                        /> -->
                                         <input list="encodings" class="form-control" :class="{ 'is-invalid': $v.data.received_by.$error }"
                                        v-model.trim="$v.data.received_by.$model">
                                        <datalist id="encodings">
                                            <option :value="item.released_by" v-for="item in receivedBy">{{ item.released_by }}</option>
                                        </datalist>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Serial Number</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.serial.$error }"
                                            v-model.trim="$v.data.serial.$model"
                                        />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Source</label>
                                        <select
                                            class="form-control"
                                            v-model="data.blood_station_id"
                                        >
                                            <option
                                                v-for="option in bloodStations"
                                                v-bind:item="option"
                                                :value="option.id"
                                            >{{ option.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Group</label>
                                        <select
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.blood_type_id.$error }"
                                            v-model.trim="$v.data.blood_type_id.$model"
                                        >
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
                                        <label>Product</label>
                                        <select
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.blood_component_id.$error }"
                                            v-model.trim="$v.data.blood_component_id.$model"
                                        >
                                            <option
                                                v-for="option in bloodComponents"
                                                v-bind:item="option"
                                                :value="option.id"
                                            >{{ option.description }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Volume</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.vol.$error }"
                                            v-model.trim="$v.data.vol.$model"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Extracted</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.date_extracted.$error }"
                                            v-model.trim="$v.data.date_extracted.$model"
                                        />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Expiry</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.date_expiry.$error }"
                                            v-model.trim="$v.data.date_expiry.$model"
                                        />
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
                            @click="cancel()"
                        >Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Ends -->
    </div>
</template>

<script>
import { required, minLength, minValue } from "vuelidate/lib/validators";

export default {
    data() {
        return {
            bloodTypes: {},
            bloodComponents: {},
            bloodStations: {},
            data: {
                date_received: "",
                received_by: "",
                serial: "",
                blood_type_id: "",
                blood_component_id: "",
                vol: 0,
                date_extracted: "",
                date_expiry: ""
            },
            receivedBy: []
        };
    },
    mounted() {
        axios.get("blood-types").then(response => {
            this.bloodTypes = response.data;
        });

        axios.get("blood-components").then(response => {
            this.bloodComponents = response.data;
        });

        axios.get("blood-stations").then(response => {
            this.bloodStations = response.data;
        });

        this.getAllReceivedBy()


    },

    methods: {
        getAllReceivedBy() {
            axios.post('get-released-by').then( response => {
                this.receivedBy = response.data
            })
        },

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

            axios.post("store-client", this.data).then(response => {
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
            }).catch( error => {
                this.$toasted.show("Error: " + error.response.data.message, {
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });
            });
        },

        cancel() {
            this.data = [];
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