<template>
    <section>
        <div class="container">
            <div class="row">
                <div class="col">
                    <hr class="custom-hr">
                </div>
                <div class="col text-end">
                    <h2>New Vote</h2>
                </div>
            </div>
        </div>
        <div v-if="data == 0" class="container">
            <h4 class="error-head text-center">No votes yet, please check again later.</h4>
        </div>
        <div v-else class="container py-4 py-xl-5">
            <div class="card" v-for="(item, index) in data" :key="item.id">
                <div class="card-body">
                    <div class="row">
                        <div class="col-1 custom-text">
                            <!-- <i class="fas fa-stream fs-5 text-info"></i> -->
                            <h4>{{ index+1 }}</h4>
                        </div>
                        <div class="col">
                            <h4>{{item.title}}</h4>
                        </div>
                        <div class="col-3 text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-dark btn-sm" type="button" @click="goToPage(item.slug)">
                                    Detail
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
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

.col-1.custom-text {
    color: rgb(32, 121, 222);
}

</style>

<script>
    export default {
        data() {
            return {
                data: null,
                user: [],
                loginType: '',
                isLoggedIn: false
            }
        },
        mounted() {
            this.getData()
        },
        methods: {
            getData() {
                axios.get('/api/vote/show-all')
                .then(response => {
                    this.data = response.data
                })
                .catch(error => {
                    console.log(error)
                });

            },
            goToPage(slug) {
                this.$router.push('/vote/' + slug)
                window.scrollTo(0, 0)
            },
        }
    }
</script>