<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" v-model="email" class="form-control" :class="errors && errors.email?'is-invalid':''" name="email" required autocomplete="false" autofocus>
                                    <span class="invalid-feedback" role="alert" v-if="errors && errors.email && errors.email.length">
                                        <strong v-for="message in errors.email">{{ message }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" v-model="password" class="form-control" :class="errors && errors.password?'is-invalid':''" name="password" required autocomplete="current-password">
                                    <span class="invalid-feedback" role="alert" v-if="errors && errors.password && errors.password.length">
                                        <strong v-for="message in errors.password">{{ message }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button :disabled="loading" class="btn btn-primary" @click="submitForm">
                                        <span v-if="loading"><i class="fa fa-circle-notch fa-spin"></i></span>
                                        <span v-else><i class="fa fa-sign-in"></i></span>
                                        <span class="ml-2">Login</span>
                                    </button>
                                </div>
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
    name: "LoginComponent",

    data() {
        return {
            routes: {
                login:  process.env.MIX_APP_URL+'/login',
            },
            email: null,
            password: null,
            errors: {
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
                axios.post(this.routes.login, {email: this.email, password: this.password}).then(res => {
                    if (res && res.data) {
                        if (res.data.status) {
                            this.$helpers.setToken(res.data.token)
                            location.reload(true)
                        } else {
                            this.errors.email = ['']
                            this.errors.password = ['Invalid credentials.']
                        }
                    } else {
                        this.$helpers.notify('Unexpected error while processing L O G I N request', {type: 'error', icon: 'fa fa-exclamation-triangle'});
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
                                    this.errors.email = error.response.data.errors.email?error.response.data.errors.email:null
                                    this.errors.password = error.response.data.errors.password?error.response.data.errors.password:null
                                }
                                break
                            default:
                                this.$helpers.notify('Unexpected error while processing LOGIN request', {type: 'error', icon: 'fa fa-exclamation-triangle'});
                        }
                    } else {
                        this.$helpers.notify('Unexpected error while processing LOGIN request', {type: 'error', icon: 'fa fa-exclamation-triangle'});
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
