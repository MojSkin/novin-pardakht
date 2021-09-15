<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" :class="errors && errors.name?'is-invalid':''" v-model="name" required autocomplete="name" autofocus>
                                <span class="invalid-feedback" role="alert" v-if="errors && errors.name && errors.name.length">
                                    <strong v-for="message in errors.email">{{ message }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" :class="errors && errors.email?'is-invalid':''" v-model="email" required autocomplete="email">
                                <span class="invalid-feedback" role="alert" v-if="errors && errors.email && errors.email.length">
                                    <strong v-for="message in errors.email">{{ message }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" :class="errors && errors.password?'is-invalid':''" v-model="password" required autocomplete="new-password">
                                <span class="invalid-feedback" role="alert" v-if="errors && errors.password && errors.password.length">
                                    <strong v-for="message in errors.password">{{ message }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" v-model="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button :disabled="loading" class="btn btn-primary" @click="submitForm">
                                    <span v-if="loading"><i class="fa fa-circle-notch fa-spin"></i></span>
                                    <span v-else><i class="fa fa-sign-in"></i></span>
                                    <span class="ml-2">Register</span>
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
    name: "RegisterComponent",

    props: [""],

    data() {
        return {
            routes: {
                register:  process.env.MIX_APP_URL+'/register',
            },
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
            errors: {
                name: null,
                email: null,
                password: null,
            },
            loading: false,
        }
    },

    methods: {
        submitForm() {
            if (!this.loading) {
                this.loading = true;
                axios.post(this.routes.register, {name: this.name, email: this.email, password: this.password, password_confirmation: this.password_confirmation}).then(res => {
                    if (res && res.data) {
                        if (res.data.status) {
                            this.$helpers.setToken(res.data.token)
                            location.reload(true)
                        } else {
                            this.errors.email = ['']
                            this.errors.password = ['Can not create account.']
                        }
                    } else {
                        this.$helpers.notify('Unexpected error while processing REGISTER request', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                    }
                }).catch(error => {
                    if (error.response) {
                        switch (error.response.status) {
                            case 401:
                                this.errors.email = ['']
                                this.errors.password = [error.response.data.message]
                                break
                            case 419:
                                this.$helpers.notify('Page is expired.', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                                break
                            case 422:
                                if (error.response.data) {
                                    this.errors.name = error.response.data.errors.name?error.response.data.errors.name:null
                                    this.errors.email = error.response.data.errors.email?error.response.data.errors.email:null
                                    this.errors.password = error.response.data.errors.password?error.response.data.errors.password:null
                                }
                                break
                            default:
                                this.$helpers.notify('Unexpected error while processing REGISTER request', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                        }
                    } else {
                        this.$helpers.notify('Unexpected error while processing REGISTER request', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                    }
                }).then(res => {
                    this.loading = false
                })
            }
        }
    }
}
</script>
<style scoped>

</style>
