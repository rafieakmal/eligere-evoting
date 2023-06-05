<template>
    <section>
        <div class="container">
            <div class="row">
                <div class="col">
                    <hr class="custom-hr">
                </div>
                <div class="col text-end">
                    <h2>History</h2>
                </div>
            </div>
        </div>
        <div v-if="history == 0" class="container">
            <h4 class="error-head text-center">No history yet, have you visit a vote once?</h4>
        </div>
        <div v-else class="container py-4 py-xl-5" >
            <div class="card" v-for="(item, index) in history" :key="item.id">
                <div class="card-body">
                    <div class="row">
                        <div class="col-1">
                            <i class="fas fa-stream fs-5 text-info"></i></div>
                        <div class="col">
                            <h4>{{item.title}}</h4>
                        </div>
                        <div class="col-3 text-end">
                            <button class="btn btn-dark btn-sm" type="button" @click="goToPage(item.slug)">
                                Details 
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.card {
    padding-top: 5px;
    margin-bottom: 10px;
}

.custom-hr {
    border-radius: 8px;
    border-width: 5px;
    border-color: var(--bs-blue);
}
.fas.fa-stream {
    margin-top: 5px;
}
.error-head {
    margin-top: 10px;
    margin-bottom: 100px;
}

</style>

<script>
    export default {
        data() {
            return {
                history: null,
                user: [],
                loginType: '',
                isLoggedIn: false
            }
        },
        mounted() {
            this.setUser()
            this.history = JSON.parse(localStorage.getItem("history") || '[]')
        },
        methods: {
            setUser() {
                this.user = JSON.parse(localStorage.getItem('user'))
                this.isLoggedIn = localStorage.getItem('token') != null
            },
            goToPage(slug) {
                this.$router.push('/vote/' + slug)
                window.scrollTo(0, 0)
            },

            goToCreate() {
                this.$router.push('/create')
                window.scrollTo(0, 0)
            },
        }
    }
</script>