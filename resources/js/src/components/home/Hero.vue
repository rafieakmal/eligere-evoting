<template>
    <section class="py-4 py-xl-5">
        <div class="container">
            <div class="border rounded border-0 d-flex flex-column justify-content-center align-items-center p-4 py-5">
                <div class="row">
                    <div class="col-md-10 col-xl-8 text-start d-flex d-sm-flex d-md-flex justify-content-center align-items-center mx-auto justify-content-md-start align-items-md-center justify-content-xl-center">
                        <div>
                            <h1 class="text-uppercase fw-bold text-start mb-3 text-light">Create your Vote in seconds</h1>
                            <button class="btn btn-info fs-5 me-2 py-2 px-4 text-light" type="button" @click="goToCreate()">Create a Vote</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.border {
    background: linear-gradient(rgba(0,123,255,0.2), rgba(0,123,255,0.2)), url("https://i.ibb.co/sC8LrSM/ilgmyzin-S9-Bn-B9-Fkan-E-unsplash.jpg") center / cover;
    height: 500px;
}
</style>

<script>
    export default {
        methods: {
            goToCreate() {
                this.$router.push('/create')
                window.scrollTo(0, 0)
            },
        }
    }
</script>