<template>
    <div>
        <!-- Modal starts -->
        <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Register User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" v-model="data.name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" v-model="data.email">
                        </div>
                        <div class="form-group">
                            <label>Station</label>
                            <select class="form-control form-control" v-model="data.blood_station_id">
                                <option v-for="option in bloodStations" v-bind:item="option" :value="option.id">{{ option.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <select class="form-control form-control" v-model="data.position">
                                <option v-for="option in positions" v-bind:item="option" :value="option">{{ option }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control form-control" v-model="data.role">
                                <option v-for="option in roles" v-bind:item="option" :value="option">{{ option }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" v-model="data.password" autocomplete>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" v-model="data.password_confirmation" autocomplete>
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

        props:['bloodStations'],
        data() {
            return {
                data: {},
                roles: [
                    'Administrator',
                    'Hospitals'
                ],
                positions: [
                    'Medical Technologist I',
                    'Medical Technologist II',
                    'Medical Technologist III',
                    'Medical Technologist IV',
                ]
            }
        },

        methods: {
            save() {
                axios.post('register-user', this.data)
                    .then((response)=>{
                        this.$toasted.show("Successfully Added", { 
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