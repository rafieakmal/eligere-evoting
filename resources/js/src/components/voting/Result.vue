<template>
    <Bar
        v-if="loaded"
        id="my-chart-id"
        :options="chartOptions"
        :data="chartData"
  />
</template>

<script>
    import { Bar } from 'vue-chartjs'
    import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
    ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

    export default {
        components: {
            Bar
        },
        data() {
            return {
                user: {},
                isLoggedIn: false,
                loaded: false,
                chartData: {
                    labels: [],
                    datasets: [ 
                        {
                            label: 'Vote Result',
                            data: [],
                            backgroundColor: '#0d6efd',
                        } 
                    ]
                },
                chartOptions: {
                    responsive: true
                }
            }
        },
        mounted() {
            this.setUser()
            this.getResult(this.$route.params.slug)
        },

        methods: {
            setUser() {
                this.user = this.$cookies.get('user')
                this.isLoggedIn = this.$cookies.get('token') != null
            },
            getResult(slug) {
                axios.get(`/api/vote/show-options/${slug}`)
                .then(response => {
                    this.parseData(response.data)
                })
                .catch(error => {
                    console.log(error)
                });
            },

            async parseData(data) {
                await Promise.all(
                    data.map(async (result) => {
                        const label = await this.chartData.labels.push(result.name)
                        const voteResult = await this.chartData.datasets[0].data.push(result.total_vote)
                    })
                )
                this.loaded = true
            }
        }
            
    }
</script>