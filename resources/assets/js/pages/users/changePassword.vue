<template>
    <div>
        <!-- Modal starts -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample">
                        <div class="form-group">
                            <label>Old Password</label>
                            <input type="password" class="form-control" v-model="data.old" autocomplete>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" class="form-control" v-model="data.new" autocomplete>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" v-model="data.confirm" autocomplete>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-success" @click="save()">Save</button>
                <button type="button" class="btn btn-light" data-dismiss="modal" @click="cancel()">Close</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal Ends -->
    </div>
</template>

<script>
    export default {

        data() {
            return {
                data: {},
            }
        },
        methods: {
            save() {
                axios.post('change-password', this.data)
                    .then((response)=>{
                        this.$toasted.show("Password Changed", { 
                            theme: "bubble", 
                            position: "top-right", 
                            duration : 5000
                        });

                        this.data.vol = [];
                    })
            },

            cancel() {
                this.data = [];
            }
        }
        

    }
</script>