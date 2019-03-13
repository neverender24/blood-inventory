<template>
    <div>
        <!-- Modal starts -->
        <div
            class="modal fade"
            id="detailsModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample">
                            <div class="form-group">
                                <div v-for="d in data.order_details">
                                    <p
                                        v-for="disp in d.dispositions"
                                    >{{ disp.serial }} - {{ disp.blood_component.description }} - {{ disp.blood_type.description }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row" v-if="user.role!='Administrator'">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Recieve Date</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.received_date.$error }"
                                            v-model.trim="$v.data.received_date.$model"
                                        >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Receive Time</label>
                                        <input
                                            type="time"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.data.received_time.$error }"
                                            v-model.trim="$v.data.received_time.$model"
                                        >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-success"
                            @click="save()"
                            v-if="editable"
                        >Save</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
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
import { required, minValue } from "vuelidate/lib/validators";
import { mapState } from 'vuex'
export default {
    props: ["data"],
    data() {
        return {
            editable: true
        };
    },
    watch: {
        data: function() {
            let self = this;

            if (self.data.received_date != null) {
                self.editable = false;
            } else {
                self.editable = true;
            }
        }
    },
    computed: {
        ...mapState(["user"])
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

            axios
                .patch("/receive/" + this.data.id, this.data)
                .then(response => {
                    this.$toasted.show("Successfully Added", {
                        theme: "bubble",
                        position: "top-right",
                        duration: 5000
                    });

                    this.$emit("refresh");
                });
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