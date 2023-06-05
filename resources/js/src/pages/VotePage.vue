<template>
    <section>
        <NavBar/>
        <div class="container">
            <MyTitle
                :title="data.title || 'Loading...'"
            />
            <Countdown 
                :year="time.year"
                :month="time.month"
                :date="time.date"
                :hour="time.hour"
                :minute="time.minute"
                :second="time.second"
            />
            <Item/>
            <Accordion/>
        </div>
        <MyFooter/>
    </section>
</template>

<script>
    import Countdown from '../components/voting/Countdown.vue'
    import Item from '../components/voting/Item.vue'
    import Accordion from '../components/voting/Accordion.vue'
    import MyTitle from '../components/voting/Title.vue'
    import NavBar from '../components/public/Navbar.vue'
    import MyFooter from '../components/public/Footer.vue'
    export default {
        metaInfo: {
            title: "Eligere - Vote Page",
        },
        components: { NavBar, MyFooter, Countdown, Item, MyTitle, Accordion },
        name: 'vote-page',
        data() {
            return {
                data: {},
                time: {},
                history: null,
                loaded: false
            }
        },
        mounted() {
            this.getDataUsingSlug(this.$route.params.slug)
            this.history = JSON.parse(localStorage.getItem("history") || '[]')
        },
        methods: {
            historyIsPresent(newId) {
                return this.history.some(current => current.id === newId)
            },
            async insertHistory(data) {
                let available = await this.historyIsPresent(data.id)
                if(available) {
                    console.log('Data Exists')
                } else {
                    await this.history.push(data)
                    localStorage.setItem("history", JSON.stringify(this.history))
                }
            },
            getDataUsingSlug(param) {
                axios.get(`/api/vote/find/${param}`)
                .then(response => {
                    this.data = response.data
                    this.loaded = true
                    this.$store.commit("addVoteData", response.data)
                    // this.insertHistory(response.data)
                    this.parseDateTIme()
                })
                .catch(error => {
                    console.log(error)
                });

            },
            parseDateTIme() {
                const date = this.data.date.split("-")
                this.time.year = parseInt(date[0], 10)
                this.time.month = parseInt(date[1], 10) - 1
                this.time.date = parseInt(date[2], 10)

                const time = this.data.time.split(":")
                this.time.hour = parseInt(time[0], 10)
                this.time.minute = parseInt(time[1], 10)
                this.time.second = parseInt(time[2], 10)
            }
        }
    }
</script>
