<template>
    <section>
        <NavBar/>
        <div class="container">
            <MyResult/>
        </div>
        <MyFooter/>
    </section>
</template>

<script>
    import MyResult from '../components/voting/Result.vue'
    import NavBar from '../components/public/Navbar.vue'
    import MyFooter from '../components/public/Footer.vue'
    export default {
        metaInfo: {
            title: "Eligere - Vote Result",
        },
        components: { NavBar, MyFooter, MyResult },
        name: 'vote-result',
    }
</script>