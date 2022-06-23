<template>
    <div>
        <!-- Modal starts -->
        <div class="modal fade" id="releaseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Release Stock</h5>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample">
                            <div class="form-group">
                                <label>Released By</label>
                                <!-- <input
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': $v.data.released_by.$error }"
                                    v-model.trim="$v.data.released_by.$model"
                                /> -->
                                <input list="encodings" class="form-control"
                                    :class="{ 'is-invalid': $v.data.released_by.$error }"
                                    v-model.trim="$v.data.released_by.$model">
                                <datalist id="encodings">
                                    <option :value="item.released_by" v-for="item in releasedBy">{{ item.released_by }}
                                    </option>
                                </datalist>
                            </div>

                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea type="text" class="form-control"
                                    :class="{ 'is-invalid': $v.data.remarks.$error }"
                                    v-model.trim="$v.data.remarks.$model"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" :class="{ 'is-invalid': $v.data.type.$error }"
                                    v-model.trim="$v.data.type.$model">
                                    <option value></option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Transfuse">Transfuse</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Disposition</label>
                                <div v-for="(row, index) in $v.data.dispositions.$each.$iter">
                                    <!-- <select
                                            class="form-control"
                                            :class="{ 'is-invalid': row.disposition_id.$error }"
                                            v-model.trim="row.disposition_id.$model"
                                        >
                                            <option
                                                v-for="(option, index) in dispositions"
                                                v-bind:item="option"
                                                :value="option.id"
                                            >{{ option.serial }}</option>
                                    </select>-->
                                    <model-select :options="searchOfficeSelect" v-model.trim="row.disposition_id.$model"
                                        placeholder="select item"></model-select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control"
                                            :class="{ 'is-invalid': $v.data.released_date.$error }"
                                            v-model.trim="$v.data.released_date.$model" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <input type="time" class="form-control"
                                            :class="{ 'is-invalid': $v.data.released_time.$error }"
                                            v-model.trim="$v.data.released_time.$model" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="addDisposition()"
                            v-if="data.dispositions.length<dispositions.length">Add Disposition</button>
                        <button type="button" class="btn btn-success" @click="save()">Save</button>
                        <button type="button" class="btn btn-light"
                            @click="cancel()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Ends -->
    </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { ModelSelect } from "vue-search-select";

export default {
    props: ["dispositions"],
    data() {
        return {
            data: {
                released_by: "",
                released_time: "",
                released_date: "",
                type: "",
                remarks: "",
                dispositions: [],
            },
            releasedBy:[]
        };
    },
    mounted() {
        $("#releaseModal").modal('show')

        this.getAllReleasedBy()
    },
    components: {
        ModelSelect
    },
    computed: {
        searchOfficeSelect: function() {
            var self = this;
            var select = [];

            _.forEach(self.dispositions, function(e) {
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

            axios.post("releases", this.data).then(response => {
                this.$toasted.show("Successfully Added", {
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

                this.data.serial = "";
                this.data.blood_type_id = "";
                this.data.blood_component_id = "";
                this.data.vol = "";

                this.cancel()
                this.$emit("refresh");
            });
        },

        getAllReleasedBy() {
            axios.post('get-released-by').then( response => {
                this.releasedBy = response.data
            })
        },

        addDisposition(index) {
            this.data.dispositions.push({
                disposition_id: index
            });
        },

        cancel() {
            $("#releaseModal").modal('hide')
            this.$emit("closeModal");
        }
    },

    validations: {
        data: {
            released_date: {
                required
            },
            released_by: {
                required
            },
            released_time: {
                required
            },
            remarks: {
                required
            },
            type: {
                required
            },
            dispositions: {
                $each: {
                    disposition_id: {
                        required
                    }
                }
            }
        }
    }
};
</script>