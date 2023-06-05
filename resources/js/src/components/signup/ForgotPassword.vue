<template>
    <section class="py-4 py-md-5 mt-5">
        <div class="container py-md-5">
            <div class="row d-flex align-items-center">
                <div class="col-md-6 text-center">
                    <img class="img-fluid w-100" src="assets/img/illustrations/desk.svg">
                </div>
                <div class="col-md-5 col-xl-4 text-center text-md-start">
                    <h2 class="display-6 fw-bold mb-5">Forgot your <span class="underline pb-1">password?</span></h2>
                    <p class="text-muted">Enter the email associated with your account and we'll send you a reset link.</p>
                    <form @submit.prevent="requestResetPassword">
                        <div class="mb-3">
                            <input class="shadow form-control" type="email" name="email" placeholder="Email" v-model="email" required>
                        </div>
                        <div v-show="isError">
                            <p style="color: red;">{{ message }}</p>
                        </div>
                        <div v-show="!isError">
                            <p style="color: green;">{{ message }}</p>
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
                isError: false,
                message: null
            }
        },
        methods: {
            requestResetPassword() {
                let formData = new FormData()
                formData.append('email', this.email)
                this.axios({
                    url: '/api/reset-password',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Acces-Control-Allow-Origin': '*',
                    }
                }).then(response => {
                    this.isError = false,
                    this.$cookies.set('reset_email', this.email, '1min'),
                    this.message = 'Reset link sent to your email.'
                }).catch(err => {
                    console.log(err)
                })
            },
        }
    }
</script>