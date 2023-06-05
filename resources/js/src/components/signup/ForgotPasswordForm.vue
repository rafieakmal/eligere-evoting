<template>
    <section class="py-4 py-md-5 mt-5">
        <div class="container py-md-5">
            <div class="row d-flex align-items-center">
                <div class="col-md-6 text-center">
                    <img class="img-fluid w-100" src="/assets/img/illustrations/desk.svg">
                </div>
                <div class="col-md-5 col-xl-4 text-center text-md-start">
                    <h2 class="display-6 fw-bold mb-2">Reset <span class="underline pb-1">Password</span></h2>
                    <form @submit.prevent="requestResetPassword">
                        <div class="mb-3">
                            <input v-model="user.password" v-show="!showPassword" @input="validatePassword" minlength="8" class="shadow-sm form-control" type="password" name="password" placeholder="Password" required>
                            <input v-model="user.password" v-show="showPassword" @input="validatePassword" minlength="8" class="shadow-sm form-control" type="text" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <input v-model="user.password_confirmation" v-show="!showPassword" @input="validatePassword" minlength="8" class="shadow-sm form-control" type="password" name="password_repeat" placeholder="Repeat Password" required>
                            <input v-model="user.password_confirmation" v-show="showPassword" @input="validatePassword" minlength="8" class="shadow-sm form-control" type="text" name="password_repeat" placeholder="Repeat Password" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input v-model="showPassword" class="form-check-input" type="checkbox" value="true" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Show Password
                                </label>
                            </div>
                        </div>
                        <div v-if="error">
                            <p style="color: red;">{{ message }}</p>
                        </div>
                        <div class="mb-5">
                            <button class="btn btn-primary shadow" type="submit">Reset password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "forgot-password",
        metaInfo: {
            title: "Eligere - Forgot Password",
        },
        data() {
            return {
                email: null,
                error: null,
                message: null,
                showPassword: false,
                user: {
                    email: null,
                    password: null,
                    password_confirmation: null,
                }
            }
        },
        mounted(){
            this.user.email = this.$cookies.get('reset_email') != null ? this.$cookies.get('reset_email') : null
        },
        methods: {
            validatePassword() { 
                if (this.user.password !== this.user.password_confirmation) {
                    this.message = 'Passwords do not match'
                    this.error = true
                    return true
                }
                this.message = ''
                this.error = false
                return false
            },
            requestResetPassword() {
                let formData = new FormData()
                formData.append('email', this.user.email)
                formData.append('token', this.$route.params.token)
                formData.append('password', this.user.password)
                formData.append('password_confirmation', this.user.password_confirmation)
                this.axios({
                    url: '/api/confirm-reset-password',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Acces-Control-Allow-Origin': '*',
                    }
                    }).then(response => {
                        this.$cookies.remove('reset_email')
                        this.$router.push('/login')
                    }).catch(err => {
                        this.error = true
                        this.message = 'Failed to reset password, because your token is expired.'
                    })
            },
        }
    }
</script>