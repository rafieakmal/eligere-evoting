<template>
    <section>
        <NavBar/>
        <div class="container">
            <div v-show="!isUser">
                <Hero/>
            </div>
            <div v-show="isUser">
                <HeroUser/>
            </div>
            <div v-show="!isUser">
                <Vote/>
            </div>
            <div v-show="isUser">
                <VoteUser/>
            </div>
        </div>
        <MyFooter/>
    </section>
</template>

<script>
    import Hero from '../components/home/Hero.vue'
    import HeroUser from '../components/home/HeroUser.vue'
    import Vote from '../components/home/Vote.vue'
    import VoteUser from '../components/home/VoteUser.vue'
    // import History from '../components/home/History.vue'
    import NavBar from '../components/public/Navbar.vue'
    import MyFooter from '../components/public/Footer.vue'
    export default {
        metaInfo: {
            title: "Eligere - Home",
        },
        components: { NavBar, MyFooter, Hero, HeroUser, Vote, VoteUser },
        name: 'vote-form',
        data() {
            return {
                user: {},
                isLoggedIn: false,
                isUser: false,
            }
        },
        created () {
            this.setUser()
        },
        methods: {
            setUser() {
                this.user = this.$cookies.get('user')
                this.isLoggedIn = this.$cookies.get('token') != null
                if(this.user && this.isLoggedIn) {
                    if(this.user.role != "admin") {
                        this.$router.push('/')
                        this.isUser = true
                    } else {
                        this.isUser = false
                    }
                } else {
                    this.$router.push('/login')
                }
            },
        }
    }
</script>
