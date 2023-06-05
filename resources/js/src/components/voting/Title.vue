<template>
    <header class="pt-5">
        <div class="container pt-4 pt-xl-5">
            <div class="row">
                <div class="col">
                    <h1 class="text-center">{{title}}</h1>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
    export default {
        name: "vote-title",
        props: {
            title: String,
        },
    }
</script>