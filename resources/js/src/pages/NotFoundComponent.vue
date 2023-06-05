<template>
    <section>
        <NavBar/>
        <NotFound/>
        <MyFooter/>
    </section>
</template>

<style scoped>
.container {
    padding-top: 60px;
}

</style>
<script>
    import NotFound from '../components/404/NotFound.vue'
    import NavBar from '../components/public/Navbar.vue'
    import MyFooter from '../components/public/Footer.vue'
    export default {
        metaInfo: {
            title: "Eligere",
        },
        components: { NavBar, MyFooter, NotFound },
        name: 'not-found-page'
    }
</script>
