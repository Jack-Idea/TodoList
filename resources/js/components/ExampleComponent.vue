<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <button class="btn btn-secondary btn-sm uk-margin" @click.prevent="visibility = !visibility"><span v-if="visibility">Cancel <span uk-icon="icon: minus; ratio: 0.7;"></span></span><span v-if="!visibility">Add new <span uk-icon="icon: plus; ratio: 0.7;"></span></span></button>
                <form v-if="visibility == true" class="uk-animation-slide-right">
                    <div class="uk-margin">
                        <span class="d-block">
                            Status:  
                            <span v-if="!title" class="uk-animation-shake" uk-icon="icon: ban; ratio: 0.7;" style="color: red;"></span>
                            <span v-if="title" class="uk-animation-shake" uk-icon="icon: check; ratio: 0.9;" style="color: green;"></span>
                        </span>
                        <div class="uk-inline">
                            <input class="form-control" type="text" name="title" v-model="title">
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="uk-inline">
                           <button class="btn btn-sm btn-success" :disabled="!title || spinner == true" @click.prevent="makeForm()"><span v-if="spinner == false">Сохранить</span><span v-if="spinner == true"><div uk-spinner="ratio: 0.3" style="width: 70px; height: 13px"></div></span></button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-md-8">
                <button class="btn btn-sm btn-success" @click.prevent="allDone()">Done</button>
                <button class="btn btn-sm btn-danger" @click.prevent="allNotDone()">Not Ready</button>
                <button class="btn btn-sm btn-secondary" @click.prevent="clearDone()">Clear</button>
                <table class="table uk-margin">
                  <thead>
                    <tr>
                      <th scope="col">Title</th>
                      <th scope="col">Status</th>
                      <th scope="col">Buttons</th>
                    </tr>
                  </thead>
                  <tbody v-if="titleForm == false">
                    <tr v-for=" d in data ">
                      <td v-if=" d.flag == true && doneFilter == true">
                        <span v-if="d.editMode !== true">{{ d.title }}</span>
                        <input v-if="d.editMode == true" type="text" name="title" v-model="d.title">
                      </td>
                      <td v-if=" d.flag == true && doneFilter == true">
                        <span v-if="d.flag == 0" class="uk-animation-shake btn-sm btn-danger">
                          Not Ready
                        </span><span v-if="d.flag == 1" class="uk-animation-shake btn-sm btn-success">Done</span></td>
                      <td v-if=" d.flag == true && doneFilter == true"> 
                        <div v-if="d.editMode == !true">
                            <span class="btn btn-sm btn-secondary" @click.prevent="editTitle(d)">Edit</span>  
                            <span v-if="d.flag == 1" class="btn btn-sm btn-danger" @click.prevent="removeCard(d.id)">Delete</span>  
                            <span v-if="d.flag == 0" class="btn btn-sm btn-success" @click.prevent="doneFlag(d.id)">Ready</span>  
                            <span v-if="d.flag == 1" class="btn btn-sm btn-info" @click.prevent="workFlag(d.id)">To Work</span> 
                        </div>
                        <div v-if="d.editMode == true">
                            <span class="btn btn-sm btn-success" @click.prevent="editTitle(d)">Save</span>  
                            <span class="btn btn-sm btn-secondary" @click.prevent="cancelEdit(d)">Cancel</span>
                        </div>
                      </td>
                      <td v-if=" d.flag == false && notDoneFilter == true">
                        <span v-if="d.editMode !== true">{{ d.title }}</span>
                        <input v-if="d.editMode == true" type="text" name="title" v-model="d.title">
                      </td>
                      <td v-if=" d.flag == false && notDoneFilter == true">
                        <span v-if="d.flag == 0" class="uk-animation-shake btn-sm btn-danger">
                          Not Ready
                        </span><span v-if="d.flag == 1" class="uk-animation-shake btn-sm btn-success">Done</span></td>
                      <td v-if=" d.flag == false && notDoneFilter == true"> 
                        <div v-if="d.editMode == !true">
                            <span class="btn btn-sm btn-secondary" @click.prevent="editTitle(d)">Edit</span>  
                            <span v-if="d.flag == 1" class="btn btn-sm btn-danger" @click.prevent="removeCard(d.id)">Delete</span>  
                            <span v-if="d.flag == 0" class="btn btn-sm btn-success" @click.prevent="doneFlag(d.id)">Ready</span>  
                            <span v-if="d.flag == 1" class="btn btn-sm btn-info" @click.prevent="workFlag(d.id)">To Work</span> 
                        </div>
                        <div v-if="d.editMode == true">
                            <span class="btn btn-sm btn-success" @click.prevent="editTitle(d)">Save</span>  
                            <span class="btn btn-sm btn-secondary" @click.prevent="cancelEdit(d)">Cancel</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                title: '',
                flag: 0,
                data: [],
                visibility: false,
                spinner: true,
                titleForm: false,
                doneFilter: true,
                notDoneFilter: true
            }
        },
        mounted() {
            axios
                .get('api/data')
                .then(response => (this.data = response.data.data,  this.spinner = false, this.addEditMode()))
        },
        methods: {
            makeForm() {
                this.spinner = true
                Object.assign(axios.defaults.headers, {
                    'X-Requested-With': 'XMLHttpRequest',
                });
                axios.post('api/task/submit', {
                    title: this.title,
                    flag: this.flag
                })
                .then(response => {
                    this.fetchData()

                        this.title = ''
                        this.visibility = false
                })
                .catch(function (error) {
                    console.log(error)
                })
                
            },
            removeCard(id) {
                let index = this.data.findIndex(d => d.id === id)
                axios.delete('api/welcome/' + id)
                this.data.splice(index, 1)
            },
            doneFlag(id) {
                let index = this.data.findIndex(d => d.id === id)
                axios.post('api/welcome/' + id + '/edit', {
                    flag: 1
                })
                .then(response => {
                    this.fetchData()
                })
            },
            workFlag(id) {
                let index = this.data.findIndex(d => d.id === id)
                axios.post('api/welcome/' + id + '/edit', {
                    flag: 0
                })
                .then(response => {
                    this.fetchData()
                })
            },
            editTitle(d) {

                if(d.editMode == true) {
                    d.editMode = false
                    this.pushTitle(d)
                }

                else {
                    d.editMode = true
                }

            },
            cancelEdit(d) {
                if(d.editMode == true) {
                    d.editMode = false
                }
            },
            addEditMode() {
                this.data.forEach(function(item) {
                    item.editMode = false
                })
            },
            pushTitle(d) {
                axios.post('api/welcome/' + d.id + '/title', {
                    title: d.title
                })
                .then(response => {
                    this.fetchData()
                })
            },
            fetchData() {
                axios
                    .get('api/data')
                    .then(response => (this.data = response.data.data, this.spinner = false, this.addEditMode()))
            },
            allDone() {
                this.notDoneFilter = false
                this.doneFilter = true
            },
            allNotDone() {
                this.notDoneFilter = true
                this.doneFilter = false
            },
            clearDone() {
                this.notDoneFilter = true
                this.doneFilter = true
            }
        }
    };
</script>

<style scoped>
    


</style>