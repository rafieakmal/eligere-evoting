<template>
    <nav class="navbar navbar-light navbar-expand-md navbar-shrink py-3" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="/assets/img/logo/svg/logo-no-background.svg" width="100" height="30" class="d-inline-block align-top" alt="">
            </a>
            <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1">
                <span class="visually-hidden">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" hidden></a>
                    </li>
                </ul>
                <div v-if="isLoggedIn">
                    <a class="btn btn-danger shadow" role="button" @click="logout">Logout</a>
                </div>
                <div v-else>
                    <a class="btn btn-primary shadow" role="button" href="/sign-up">Sign up</a>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: 'my-navbar',
        data() {
            return {
                user: [],
                loginType: '',
                isLoggedIn: false
            }
        },
        methods: {
            setUser() {
                this.user = this.$cookies.get('user')
                this.isLoggedIn = this.$cookies.get('token') != null
            },
            logout() {
                this.$cookies.remove('user')
                this.$cookies.remove('token')
                this.setUser()

                this.$router.push('/login')
                window.scrollTo(0, 0)
            }
        },
        mounted() {
            this.setUser()
        },



    }
</script>

