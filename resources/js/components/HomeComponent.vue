<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
<!--
                        Here we show BOOKS and AUTHORS list
                        if ACTION has value of -1, it means we are in SHOW mode
-->
                        <div class="row" v-if="action===-1">
                            <div class="col-12 col-md-6">
                                <h3>
                                    <span>Books</span>
                                    <button class="ml-2 btn btn-success" @click="clearData(); action = 1">Add Book</button>
                                </h3>
                                <table class="table striped borderless hover" v-if="booksList.length">
                                    <thead>
                                        <tr>
                                            <th>Book Name</th>
                                            <th>ISBN</th>
                                            <th>Author</th>
                                            <th style="width:70px; max-width: 70px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="data in booksList">
                                            <td>{{ data.name }}</td>
                                            <td>{{ data.isbn }}</td>
                                            <td>

                                                <span v-if="data.author">{{ data.author.f_name }} {{ data.author.l_name }}</span>
                                                <span v-else class="badge badge-light">Author is not found</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm font-size-10">
                                                    <button @click="editRecord('book', data)" class="btn btn-primary py-1 px-2">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button @click="deleteRecord('book', data)" class="btn btn-danger py-1 px-2">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="alert alert-dark" role="alert" v-if="booksList.length===0">
                                    Books list is empty!
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <h3>
                                    <span>Authors</span>
                                    <button class="ml-2 btn btn-success" @click="clearData(); action = 2">Add Author</button>
                                </h3>
                                <table class="table striped borderless hover" v-if="authorsList.length">
                                    <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th style="width:70px; max-width: 70px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="data in authorsList">
                                        <td>{{ data.f_name }}</td>
                                        <td>{{ data.l_name }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm font-size-10">
                                                <button @click="editRecord('author', data)" class="btn btn-primary py-1 px-2">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <button @click="deleteRecord('author', data)" class="btn btn-danger py-1 px-2">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <button @click="showBooks(data)" class="btn btn-success py-1 px-2" v-if="data.books && data.books.length">
                                                    <i class="fa fa-books"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="alert alert-dark" role="alert" v-if="authorsList.length===0">
                                    Authors list is empty!
                                </div>
                            </div>
                        </div>
<!--
                        Here we show BOOKS add or edit form
                        if ACTION has value of 1, it means we are in add or edit mode for BOOKS
                        if ACTION has value of 2, it means we are in add or edit mode for AUTHORS
-->
                        <div class="row" v-if="action>0">
                            <div class="col-12">
                                <h3>
                                    <span v-if="editingRecord && editingRecord.id">Edit</span>
                                    <span v-else>Add</span>
                                    <span v-if="action===1">Book</span>
                                    <span v-if="action===2">Author</span>
                                </h3>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12">
                                <div class="row" v-if="action===1">
                                    <div class="form-group col-12 col-md-6 col-lg-4">
                                        <label for="name">Book name</label>
                                        <input id="name" type="text" v-model="book.name" class="form-control" :class="errors && errors.name?'is-invalid':''" required autofocus>
                                        <span class="invalid-feedback" role="alert" v-if="errors && errors.name && errors.name.length">
                                        <strong v-for="message in errors.name">{{ message }}</strong>
                                    </span>
                                    </div>
                                    <div class="form-group col-12 col-md-6 col-lg-4">
                                        <label for="isbn">I S B N</label>
                                        <input id="isbn" type="text" v-model="book.isbn" maxlength="11" class="form-control" :class="errors && errors.isbn?'is-invalid':''" required>
                                        <span class="invalid-feedback" role="alert" v-if="errors && errors.isbn && errors.isbn.length">
                                        <strong v-for="message in errors.isbn">{{ message }}</strong>
                                    </span>
                                    </div>
                                    <div class="form-group col-12 col-md-12 col-lg-4">
                                        <label for="author_id">Author</label>
                                        <select id="author_id" v-model="book.author_id" class="form-control" :class="errors && errors.author_id?'is-invalid':''" required>
                                            <option v-for="author in authorsList" :value="author.id" :selected="book.author_id===author.id">{{ author.l_name }}, {{ author.f_name }}</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert" v-if="errors && errors.author_id && errors.author_id.length">
                                        <strong v-for="message in errors.author_id">{{ message }}</strong>
                                    </span>
                                    </div>
                                </div>
                                <div class="row" v-if="action===2">
                                    <div class="form-group col-6 col-lg-4">
                                        <label for="f_name">Author`s first name</label>
                                        <input id="f_name" type="text" v-model="author.f_name" class="form-control" :class="errors && errors.f_name?'is-invalid':''" required autofocus>
                                        <span class="invalid-feedback" role="alert" v-if="errors && errors.f_name && errors.f_name.length">
                                            <strong v-for="message in errors.f_name">{{ message }}</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-6 col-lg-4">
                                        <label for="l_name">Author`s last name</label>
                                        <input id="l_name" type="text" v-model="author.l_name" class="form-control" :class="errors && errors.l_name?'is-invalid':''" required autofocus>
                                        <span class="invalid-feedback" role="alert" v-if="errors && errors.l_name && errors.l_name.length">
                                            <strong v-for="message in errors.l_name">{{ message }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12 d-flex">
                                <button class="btn btn-light ml-auto" @click="clearData"><i class="fa fa-times"></i> <span class="ml-2">Cancel</span></button>
                                <button class="btn btn-success ml-4" @click="saveData(action===1?'book':'author')">
                                    <i class="fa fa-save" v-if="!loading"></i>
                                    <span v-if="loading"><i class="fa fa-circle-notch fa-spin"></i></span>
                                    <span class="ml-2">Save {{ action===1?'book':'author' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "HomeComponent",

    data() {
        return {
            routes: {
                book: {
                    list: process.env.MIX_APP_URL+'/api/books/list',
                    store: process.env.MIX_APP_URL+'/api/books/store',
                    edit: process.env.MIX_APP_URL+'/api/books/edit',
                    destroy: process.env.MIX_APP_URL+'/api/books/destroy',
                },
                author: {
                    list: process.env.MIX_APP_URL+'/api/authors/list',
                    store: process.env.MIX_APP_URL+'/api/authors/store',
                    edit: process.env.MIX_APP_URL+'/api/authors/edit',
                    destroy: process.env.MIX_APP_URL+'/api/authors/destroy',
                },
            },
            authorsHeading: [
                {label: 'First Name', key: 'f_name', sortable: true},
                {label: 'Last Name', key: 'l_name', sortable: true},
                {label: '', key: 'action', thStyle: {width: '100px'}},
            ],
            booksHeading: [
                {label: 'ISBN', key: 'isbn', sortable: true},
                {label: 'Book Name', key: 'name', sortable: true},
                {label: 'Author', key: 'author', sortable: false},
                {label: '', key: 'action', thStyle: {width: '100px'}},
            ],
            booksList: [],
            authorsList: [],
            loading: false,
            book: {
                isbn: null,
                name: null,
                author_id: null,
            },
            author: {
                f_name: null,
                l_name: null,
            },
            action: -1,
            editingRecord: null,
            errors: {
                name: null,
                isbn: null,
                author_id: null,
                f_name: null,
                l_name: null,
            }
        }
    },

    created() {
        this.refreshTable('book')
        this.refreshTable('author')
    },

    methods: {
        refreshTable(tableName) {
            let token = this.$helpers.getToken()
            axios.post(this.routes[tableName].list,null, { headers: {"Authorization" : `Bearer ${token}`} }).then(res => {
                if (res && res.data) {
                    if (res.data.status) {
                        this[tableName+'sList'] = res.data.result
                    } else {
                        this.$helpers.notify('Error while fetching '+tableName.toUpperCase()+'S list', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                    }
                } else {
                    this.$helpers.notify('Unexpected error while fetching '+tableName.toUpperCase()+'S list', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                }
            }).catch(error => {
                if (error.response) {
                    switch (error.response.status) {
                        case 401:
                            this.$helpers.notify('Unauthenticated.', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                            break
                        case 419:
                            this.$helpers.notify('Page is expired.', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                            break
                        default:
                            this.$helpers.notify('Unexpected error while processing fetching data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                    }
                } else {
                    this.$helpers.notify('Unexpected error while processing fetching data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                }
            })
        },

        saveData(tableName) {
            if (!this.loading) {
                let token = this.$helpers.getToken()
                this.loading = true
                let formData = this[tableName]
                if (this.editingRecord && this.editingRecord.id) {
                    formData.id = this.editingRecord.id
                }
                axios.post((this.editingRecord && this.editingRecord.id)?this.routes[tableName].edit:this.routes[tableName].store, formData, { headers: {"Authorization" : `Bearer ${token}`} }).then(res => {
                    if (res && res.data) {
                        if (res.data.status) {
                            this.$helpers.notify(res.data.message);
                            if (this.editingRecord && this.editingRecord.id) {
                                for (let i = 0; i < this[tableName+'sList'].length; i++) {
                                    if (this[tableName+'sList'][i].id === res.data.result.id) {
                                        this[tableName+'sList'][i] = res.data.result
                                        break
                                    }
                                }
                            } else {
                                this[tableName+'sList'].push(res.data.result)
                            }
                            this[tableName+'sList'].sort((a, b) => {
                                return a.name > b.name;
                            })
                            if (tableName==='book') {
                                this.authorsList = res.data.authors
                            }
                            this.clearData()
                        } else {
                            this.$helpers.notify(res.data.message?res.data.message:'Error while store/edit '+tableName.toUpperCase()+'`s data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                        }
                    } else {
                        this.$helpers.notify('Unexpected error while store/edit '+tableName.toUpperCase()+'`s data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                    }
                }).catch(error => {
                    if (error.response) {
                        switch (error.response.status) {
                            case 401:
                                this.$helpers.notify('Unauthenticated.', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                                break
                            case 419:
                                this.$helpers.notify('Page is expired.', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                                break
                            case 422:
                                if (error.response.data) {
                                    this.errors.name = error.response.data.errors.name?error.response.data.errors.name:null
                                    this.errors.isbn = error.response.data.errors.isbn?error.response.data.errors.isbn:null
                                    this.errors.author_id = error.response.data.errors.author_id?error.response.data.errors.author_id:null
                                    this.errors.f_name = error.response.data.errors.f_name?error.response.data.errors.f_name:null
                                    this.errors.l_name = error.response.data.errors.l_name?error.response.data.errors.l_name:null
                                }
                                break
                            default:
                                this.$helpers.notify('Unexpected error while store/edit '+tableName.toUpperCase()+'`s data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                        }
                    } else {
                        this.$helpers.notify('Unexpected error while store/edit '+tableName.toUpperCase()+'`s data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                    }
                }).then(res => {
                    this.loading = false
                })
            }

        },

        editRecord(tableName, item) {
            this.editingRecord = item
            if (tableName==='book') {
                this.action = 1
                this.book.name = item.name
                this.book.isbn = item.isbn
                this.book.author_id = item.author_id
            } else {
                this.action = 2
                this.author.f_name = item.f_name
                this.author.l_name = item.l_name
            }
        },

        deleteRecord(tableName, item) {
            if (!this.loading) {
                this.$swal({
                    title: "Delete "+tableName+"`s data",
                    text: "You are trying to delete selected "+tableName+"`s data."+(tableName==='author'?" Deleting author`s data will also delete all book`s belongs to that author!?":"")+" Continue deleting?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    showCloseButton: true,
                }).then(result => {
                    let token = this.$helpers.getToken()
                    this.loading = true
                    if (result.value) {
                        this.loading = true
                        axios.post(this.routes[tableName].destroy, {id: item.id}, { headers: {"Authorization" : `Bearer ${token}`} }).then(res => {
                            if (res && res.data) {
                                if (res.data.status) {
                                    this.$helpers.notify(res.data.message);
                                    for (let i = 0; i < this[tableName+'sList'].length; i++) {
                                        if (this[tableName+'sList'][i].id === item.id) {
                                            this[tableName+'sList'].splice(i, 1)
                                            break
                                        }
                                    }
                                    if (tableName === 'author') {
                                        for (let i = this.booksList.length-1; i >= 0; i--) {
                                            if (this.booksList[i].author_id == item.id) {
                                                this.booksList.splice(i, 1)
                                            }
                                        }
                                    }
                                } else {
                                    this.$helpers.notify(res.data.message?res.data.message:'Error while deleting '+tableName.toUpperCase()+'`s data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                                }
                            } else {
                                this.$helpers.notify(res.data.message?res.data.message:'Unexpected error while deleting '+tableName.toUpperCase()+'`s data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                            }
                        }).catch(error => {
                            if (error.response) {
                                switch (error.response.status) {
                                    case 401:
                                        this.$helpers.notify('Unauthenticated.', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                                        break
                                    case 419:
                                        this.$helpers.notify('Page is expired.', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                                        break
                                    default:
                                        this.$helpers.notify('Unexpected error while deleting '+tableName.toUpperCase()+'`s data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                                }
                            } else {
                                this.$helpers.notify('Unexpected error while deleting '+tableName.toUpperCase()+'`s data', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                            }
                        }).then(res => {
                            this.loading = false
                        })
                    }
                });
            }
        },

        showBooks(data) {
            let books = data.books
            let message = '<div class="text-left"><table class="table striped borderless"><thead><tr><th>Book Name</th><th>ISBN</th></tr></thead><tbody>'
            for (let i = 0; i < books.length; i++) {
                message += '<tr><td>'+books[i].name+'</td><td>'+books[i].isbn+'</td></tr>'
            }
            message += '</tbody></div>'
            this.$swal({
                title: data.f_name+' '+data.l_name+"`s books:",
                html: message,
                showCancelButton: false,
                confirmButtonText: "I got it!",
                showCloseButton: true,
            })
        },

        clearData() {
            this.book = {
                isbn: null,
                name: null,
                author_id: null,
            }
            this.author = {
                f_name: null,
                l_name: null,
            }
            this.errors = {
                name: null,
                isbn: null,
                author_id: null,
                f_name: null,
                l_name: null,
            }
            this.action = -1
            this.editingRecord = null
        }
    }
}
</script>
<style scoped>

</style>
