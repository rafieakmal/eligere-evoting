<template>
    <section class="py-4 py-md-5 my-5">
        <div class="container py-md-5">
            <div class="row">
                <div class="col-md-6 text-center"><img class="img-fluid w-100" src="assets/img/illustrations/login.svg"></div>
                <div class="col-md-5 col-xl-4 text-center text-md-start">
                    <h2 class="display-6 fw-bold mb-5"><span class="underline pb-1">Login<br></span></h2>
                    <form @submit.prevent="loginForm">
                        <div class="mb-3">
                            <input v-model="email" class="shadow form-control" type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input v-model="password" v-show="!showPassword" class="shadow form-control" type="password" name="password" placeholder="Password" minlength="8" required>
                            <input v-model="password" v-show="showPassword" class="shadow form-control" type="text" name="password" placeholder="Password" minlength="8" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input v-model="showPassword" class="form-check-input" type="checkbox" value="true" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Show Password
                                </label>
                            </div>
                        </div>
                        <vue-hcaptcha v-show="!isVerified"
                            sitekey="e0502708-ec0c-4265-8385-640e3662cc6c"
                            @verify="onVerify"
                            @expired="onExpire"
                            @challengeExpired="onChallengeExpire"
                            @error="onError"
                        />
                        <div v-if="error">
                            <p style="color: red;">{{ error }}</p>
                        </div>
                        <div class="mb-5">
                            <button v-show="isVerified" class="btn btn-primary shadow" type="submit">Login</button>
                            <!-- <button v-show="!isVerified" class="btn btn-primary shadow disabled" type="submit">Login</button> -->
                        </div>
                        <p class="text-muted">Forgot your password? 
                            <a href="/forgot-password">Reset here&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-arrow-narrow-right">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <line x1="15" y1="16" x2="19" y2="12"></line>
                            <line x1="15" y1="8" x2="19" y2="12"></line>
                            </svg>
                        </a>&nbsp;</p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import VueHcaptcha from '@hcaptcha/vue-hcaptcha';
    export default {
        metaInfo: {
            title: "Eligere - Login",
        },
        components: { VueHcaptcha },
        data() {
            return {
                email: '',
                password: '',
                error: null,
                showPassword: false,
                message: '',
                isVerified: false
            }
        },
        methods: {
            onVerify(token, ekey) {
                this.isVerified = true
            },
            onExpire() {
                this.isVerified = false
                console.log('Expired')
            },
            onChallengeExpire() {
                this.isVerified = false
                console.log(`Challenge expired`)
            },
            onError(err) {
                this.isVerified = false
                console.log(`Error: ${err}`)
            },
            loginForm() {
                axios.post('/api/login-account', {
                    email: this.email,
                    password: this.password
                })
                .then(response => {
                    this.$cookies.set('user', JSON.stringify(response.data.user), '7d')
                    this.$cookies.set('token', response.data.token, '7d')
                    // localStorage.setItem('user', JSON.stringify(response.data.user))
                    // localStorage.setItem('token', response.data.token)
                    this.$emit('loggedIn')

                    this.$router.push('/')
                })
                .catch(error => {
                    this.error = "Email or password do not match. Please try again";
                });
            }
        }
    }
</script>
