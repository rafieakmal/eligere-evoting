<template>
    <section>
        <NavBar/>
        <div class="container">
            <Form/>
        </div>
        <MyFooter/>
    </section>
</template>

<script>
    import Form from '../components/voting/Form.vue'
    import NavBar from '../components/public/Navbar.vue'
    import MyFooter from '../components/public/Footer.vue'
    export default {
        metaInfo: {
            title: "Eligere - Create Vote",
        },
        components: { NavBar, MyFooter, Form},
        name: 'vote-form',
    }
</script>