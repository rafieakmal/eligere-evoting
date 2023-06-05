<template>
    <section>
        <div class="container">
            <div class="row">
                <div class="col">
                    <hr class="custom-hr">
                </div>
                <div class="col text-end">
                    <h2>My Vote</h2>
                </div>
            </div>
        </div>
        <div v-if="data == 0" class="container">
            <h4 class="error-head text-center">No votes yet, create one?</h4>
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
                                <button class="btn btn-danger btn-sm" type="button"  @click="showMessage(item.slug)">
                                    Delete
                                    <i class="far fa-trash-alt"></i>
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
                val: false,
                loginType: '',
                isLoggedIn: false
            }
        },
        mounted() {
            this.setUser()
        },
        methods: {
            setUser() {
                this.user = this.$cookies.get('user')
                this.isLoggedIn = this.$cookies.get('token') != null
                this.getDataUsingID(this.user.id)
            },
            getDataUsingID(id) {
                axios.get(`/api/vote/data/u/${id}`)
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

            showMessage(slug) {
                this.value = ''
                this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this data.', {
                    title: 'Are You Sure?',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'YES',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                .then(value => {
                    if(value) {
                        this.deleteVote(slug)
                    }
                })
                .catch(err => {
                    // An error occurred
                })
            },
            deleteVote(slug) {
                console.log('tes 2')
                let formData = new FormData()
                formData.append('slug',slug)
                this.axios({
                    url: '/api/vote/delete',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                .then(response => {
                        console.log(response.data)
                        let i = this.data.map(j => j.slug).indexOf(slug)
                        this.data.splice(i, 1)
                        this.openNotification(null)
                    }    
                )
                .catch(err => console.log(err))
            },

            goToCreate() {
                this.$router.push('/create')
                window.scrollTo(0, 0)
            },

            openNotification(duration) {
                const notification = this.$vs.notification({
                    duration,
                    color: 'dark',
                    progress: 'auto',
                    title: 'Success to delete',
                })
            },
        }
    }
</script>