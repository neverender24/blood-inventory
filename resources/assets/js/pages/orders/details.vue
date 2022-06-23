<template>
    <div>
        <!-- Modal starts -->
        <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Details</h5>
                    </div>
                    <div class="modal-body">
                        <div v-if="loading" class="ui inverted active dimmer">
                            <div class="ui active loader"></div>
                        </div>
                        <table class="table table-hover table-bordered table-sm">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Serial</td>
                                    <td>Component</td>
                                    <td>Type</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row, index in newDetails">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ row.serial }}</td>
                                    <td>{{ row.component }}</td>
                                    <td>{{ row.type }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <form class="forms-sample">
                            <hr />
                            <div class="row" v-if="user.role!='Administrator'">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Recieve Date</label>
                                        <input type="date" class="form-control"
                                            :class="{ 'is-invalid': $v.data.received_date.$error }"
                                            v-model.trim="$v.data.received_date.$model" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Receive Time</label>
                                        <input type="time" class="form-control"
                                            :class="{ 'is-invalid': $v.data.received_time.$error }"
                                            v-model.trim="$v.data.received_time.$model" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="save()" v-if="editable">Save</button>
                        <button type="button" class="btn btn-light" @click="cancel()">Close</button>
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
.modal-content {
    background-color: #fff;
}
</style>

<script>
import { required } from "vuelidate/lib/validators";
import { mapState } from "vuex";
export default {
    props: ["data", 'loading'],
    data() {
        return {
            editable: true
        };
    },
    mounted() {
        var vm = this

        $('#detailsModal').modal('show')

        if (vm.data.received_date != null) {
            vm.editable = false;
        } else {
            vm.editable = true;
        }
    },
    computed: {
        ...mapState(["user"]),

        newDetails: function() {
            var details = [];
            _.forEach(this.data.order_details, function(e) {
                _.forEach(e.dispositions, function(f) {
                    details.push({
                        serial: f.serial,
                        component: f.blood_component.description,
                        type: f.blood_type.description
                    });
                });
            });

            return details;
        }
    },
    methods: {
        save() {
            this.$v.$touch();
            if (this.$v.$invalid || this.newDetails == 0) {
                this.$toasted.show("Fix some errors", {
                    type: "error",
                    theme: "bubble",
                    position: "top-right",
                    duration: 5000
                });

                return false;
            }

            axios
                .patch("/receive/" + this.data.id, this.data)
                .then(response => {
                    this.$toasted.show("Successfully Added", {
                        theme: "bubble",
                        position: "top-right",
                        duration: 5000
                    });

                    this.cancel()
                    this.$emit("refresh");
                });
        },
        cancel() {
            $("#detailsModal").modal('hide')
            this.$emit("closeModal");
        }
    },

    validations: {
        data: {
            received_date: {
                required
            },
            received_time: {
                required
            }
        }
    }
};
</script>