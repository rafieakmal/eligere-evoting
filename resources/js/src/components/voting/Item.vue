<template>
    <section>
        <b-container fluid>
            <b-row align-h="center">
                <b-col md="3" class="mb-3" v-for="(item, index) in data" :key="item.id">
                    <div class="card">
                        <img class="card-img-top w-100 d-block fit-cover" :src="item.image">
                        <div class="card-body p-4">
                            <p class="text-primary card-text mb-0">VOTE#{{ index + 1 }}</p>
                            <h4 class="card-title">{{ item.name }}</h4>
                            <div class="item-div">
                                <div v-if="isLoaded">
                                    <div v-if="isUser">
                                        <div v-if="hasVoted">
                                            <button class="btn btn-success disabled" type="button">You have voted on this ballot</button>
                                        </div>
                                        <div v-else>
                                            <button v-if="isDisabled" class="btn btn-primary disabled" type="button" disabled>Vote Ended</button>
                                            <button v-else class="btn btn-primary" type="button" @click="getVoteId(item.id)">Vote</button>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <button class="btn btn-success" type="button" @click="getResult()">Get Result</button>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="justify-content-center">
                                        <b-spinner variant="primary"></b-spinner>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-col>  
            </b-row>
        </b-container>

        <!-- Vote password modal -->
        <b-modal ref="first-modal" 
            id="modal-password"
            hide-backdrop content-class="shadow" 
            centered title="Enter vote password"
            @show="resetModal"
            @hidden="resetModal"
            @ok="handleOk"
        >
            <b-alert
                :show="dismissCountDown"
                variant="danger"
                @dismissed="dismissCountDown=0"
                @dismiss-count-down="countDownChanged"
            >
                Wrong password. Please try again.
                <!-- Countdown progress bar -->
                <b-progress
                    variant="danger"
                    :max="dismissSecs"
                    :value="dismissCountDown"
                    height="3px"
                ></b-progress>
            </b-alert>

            <!-- Password form -->
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group>
                    <b-form-input
                        id="code-input"
                        type="number"
                        v-model="password"
                        :state="passwordState"
                        aria-describedby="input-live-feedback"
                    ></b-form-input>
                    <b-form-invalid-feedback id="input-live-feedback">
                        Your password must be 6 numbers long
                    </b-form-invalid-feedback>
                </b-form-group>
            </form>
        </b-modal>

        <!-- Camera modal -->
        <b-modal ref="camera-modal" id="modal-camera" hide-backdrop content-class="shadow" centered>
            <template #modal-header>
                <h5 v-if="isFaceDetected" class="text-success">Face Detected: Hi, {{ usernameThatVerified }}👋</h5>
                <h5 v-else class="text-danger">Not Detected, Please wait...</h5>
            </template>
            <template #default>
                <div v-show="loading.isCameraLoaded && loading.isModuleLoaded">
                    <canvas ref="canvas" class="face-canvas" width="100%" height="100%"></canvas>
                    <video :src="captureSrc" class="face-video" ref="video" width="100%" height="100%" autoplay muted></video>
                </div>
                <div v-show="!loading.isCameraLoaded" class="text-center">
                    <b-spinner variant="primary"></b-spinner>
                    <h5>Loading camera...</h5>
                </div>
                <div v-show="!loading.isModuleLoaded" class="text-center">
                    <b-spinner variant="primary"></b-spinner>
                    <h5>Loading modules...</h5>
                </div>
            </template>
            <template #modal-footer="{ ok }">
                <b-button v-show="isFaceDetected" variant="primary" @click="closeCameraModal()">
                    Continue
                </b-button>
                <b-button v-show="!isFaceDetected" variant="danger" disabled>
                    Please wait...
                </b-button>
            </template>
        </b-modal>

        <!-- Checked modal -->
        <b-modal ref="success-modal" id="success-camera" hide-backdrop content-class="shadow" centered>
            <template #modal-header>
                <h5 class="text-success">Successfully voted</h5>
            </template>
            <template #default>
                <img src="/assets/img/illustrations/icon-check3.png" class="img-center">
            </template>
            <template #modal-footer="{ ok }">
                <b-button variant="success" @click="closeSuccessModal()">
                    Continue
                </b-button>
            </template>
        </b-modal>

    </section> 
</template>

<style scoped>
.img-center {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
}
.item-div {
    text-align: center;
}

.card-img-top {
    height: 200px;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type='number'] {
    -moz-appearance: textfield;
}

.btn.btn-primary {
    width: 130px;
    height: 40px;
}

.face-canvas {
  position: absolute;
  top: 0;
  left: 0;
  object-fit: fill;
}

.face-video {
  object-fit: fill;
}
</style>

<script>
    import * as faceapi from 'face-api.js'
    import { EventBus } from '../../eventBus'
    import canvas from "canvas"

    export default {
        name: "vote-item",
        data() {
            return {
                data: null,
                captureSrc: '',
                voteId: '',
                votePassword: '111202',
                password: '',
                dismissSecs: 3,
                dismissCountDown: 0,
                submittedNames: [],
                isDisabled: false,
                isLoaded: false,
                isUser: false,
                loading: {
                    isCameraLoaded: false,
                    isModuleLoaded: true,
                    isLabeledImage: true
                },
                isPassword: false,
                isFaceDetected: false,
                intervalId: null,
                faceMatcher: null,
                hasVoted: false,
                labeledDescriptors: [],
                usernameThatVerified: '',
                user: {},
                isLoggedIn: false,
            };
        },
        beforeDestroy() {
            clearInterval(this.intervalId)
        },
        created () {
            this.listenTimerIsLoaded()
            this.listenTimerIsEnded()
            this.setUser()
            this.getUserIP()
            this.getOptionsData(this.$route.params.slug)
            this.getIfVoteUsePassword(this.$route.params.slug)
        },
        computed: {
            passwordState() {
                return this.password.length > 5 && this.password.length < 7 ? true : false
            }
        },
        methods:{
            setUser() {
                this.user = this.$cookies.get('user')
                if(this.user.role != "admin") {
                    this.isUser = true
                }
                this.isLoggedIn = this.$cookies.get('token') != null
                this.checkUser()
            },

            checkUser() {
                const id = this.user.id
                const slug = this.$route.params.slug
                axios.get(`/api/vote/check-user/${id}/${slug}`)
                .then(response => {
                    this.hasVoted = response.data
                })
                .catch(error => {
                    console.log(error)
                });
            },

            getUserIP() {
                axios.get('/api/my-ip')
                .then(response => {
                    this.user.ip = response.data.ip
                })
                .catch(error => {
                    console.log(error)
                });
            },

            getIfVoteUsePassword(param) {
                axios.get(`/api/vote/find/${param}`)
                .then(response => {
                    this.isPassword = response.data.is_password
                    this.getVoteUsePassword(this.$route.params.slug)
                })
                .catch(error => {
                    console.log(error)
                });
            },

            getVoteUsePassword(param) {
                axios.get(`/api/vote/show-passwords/${param}`)
                .then(response => {
                    this.votePassword = response.data.password
                })
                .catch(error => {
                    console.log(error)
                });
            },

            getOptionsData(param) {
                axios.get(`/api/vote/show-options/${param}`)
                .then(response => {
                    this.data = response.data
                })
                .catch(error => {
                    console.log(error)
                });
            },

            listenTimerIsLoaded() {
                EventBus.$on('timer_loaded', (value) => {
                    this.isLoaded = true
                })
            },

            listenTimerIsEnded() {
                EventBus.$on('timer_ended', (value) => {
                    this.isDisabled = true
                    this.isLoaded = true
                })
            },

            getVoteId(id) {
                this.voteId = id
                if(this.isPassword) {
                    this.$refs['first-modal'].show()
                } else {
                    this.showModal()
                }

            },

            getResult() {
                this.$router.push('/vote/result/' + this.$route.params.slug)
                window.scrollTo(0, 0)
            },

            updater(id) {
                console.log(id)
                axios.get(`/api/vote/update-option/${id}`)
                .then(response => {
                    console.log(response)
                })
                .catch(error => {
                    console.log(error)
                });
            },

            insertData() {
                let formData = new FormData()
                formData.append('user_id', this.user.id)
                formData.append('vote_id', this.$store.state.voteData.id)
                formData.append('user_ip', this.user.ip)
                formData.append('slug', this.$store.state.voteData.slug)
                formData.append('option_id', this.voteId)
                formData.append('is_verified', '1')
                this.axios({
                    url: '/api/vote/insert-vote',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                    .then(response => (
                        console.log(response.data)
                ))
                    .catch(err => console.log(err))
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },

            showAlert() {
                this.resetModal()
                this.dismissCountDown = this.dismissSecs
            },

            checkFormValidity() {
                const valid = this.password.length > 5 && this.password.length < 7 ? true : false
                const validatePassword = this.password === this.votePassword ? true : false
                if (!valid || !validatePassword) {
                    return
                }

                return validatePassword
            },

            resetModal() {
                this.password = ''
            },

            handleOk(bvModalEvent) {
                bvModalEvent.preventDefault()
                this.handleSubmit()
            },

            handleSubmit() {
                if (!this.checkFormValidity()) {
                    return this.showAlert()
                }
                this.submittedNames.push(this.name)
                this.$nextTick(() => {
                    this.$bvModal.hide('modal-password')
                    this.showModal()
                })
            },

            async loadNewModels() {
                await Promise.all([
                    faceapi.loadTinyFaceDetectorModel('/models'),
                    faceapi.loadFaceDetectionModel('/models'),
                    faceapi.loadFaceLandmarkModel('/models'),
                    faceapi.loadFaceRecognitionModel('/models'),
                    faceapi.tf.setBackend('cpu'),
                    tf.ready(),
                ])
            },

            async startCamera() {
                try {
                    await navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                        .then(stream => {   
                            this.$refs.video.srcObject = stream
                            this.$refs.video.addEventListener('loadeddata', () => {
                                this.loading.isCameraLoaded = true
                                this.loading.isModuleLoaded = false
                                this.loadNewModels().then(() => {
                                    this.loading.isModuleLoaded = true
                                    this.loadLabeledImages().then(() => {
                                        this.recognizeFaces()
                                })
                            })
                        })
                    })
                } catch (error) {
                    console.log(error)
                }
            },

            async loadLabeledImages() {
                const labels = [this.user.username]
                console.log(labels)
                const labeledFaceDescriptors = await Promise.all(
                    labels.map(async (label) => {
                        const imgUrl = `/assets/img/user/${label}.jpg`
                        const img = await faceapi.fetchImage(imgUrl)
                        console.log(img)
                        const detections = await faceapi.detectSingleFace(img)
                            .withFaceLandmarks()
                            .withFaceDescriptor()
                        console.log(detections)
                        return new faceapi.LabeledFaceDescriptors(label, [detections.descriptor])
                    })
                );
                this.labeledDescriptors = labeledFaceDescriptors;
                this.faceMatcher = new faceapi.FaceMatcher(this.labeledDescriptors, 0.6);
            },

            async recognizeFaces() {
                const video = this.$refs.video
                const canvas = this.$refs.canvas
                const displaySize = { width: video.width, height: video.height }
                const detectionOptions = new faceapi.TinyFaceDetectorOptions({ 
                    inputSize: 224, 
                    scoreThreshold: 0.5 
                });
                this.intervalId = setInterval(async () => {
                    const detections = await faceapi.detectAllFaces(video, detectionOptions)
                        .withFaceLandmarks()
                        .withFaceDescriptors()
                    if (detections) {
                        const resizedDetections = faceapi.resizeResults(detections, displaySize);
                        const results = resizedDetections.map((d) => this.faceMatcher.findBestMatch(d.descriptor))
                        results.forEach((result, i) => {
                            const box = resizedDetections[i].detection.box;
                            const label = result.toString()
                            const arr = label.split(" ")
                            if(arr[0] != 'unknown') {
                                this.isFaceDetected = true
                                this.usernameThatVerified = arr[0]
                            } else {
                                this.isFaceDetected = false
                                this.usernameThatVerified = ''
                            }
                            
                        }, 100)
                    } else {
                        this.isFaceDetected = false
                        this.usernameThatVerified = ''
                    }
                })
            },

            async notification() {
                var txt = "*Athen's Choice Notification*"
                txt += "\n\nThanks for your vote at:"
                txt += `\nVote ID: VOTE#${this.voteId}`
                txt += "\n\nDetail user:"
                txt += `\nUser ID: ${this.user.id}`
                txt += `\nUser Name: ${this.usernameThatVerified}`
                let formData = new FormData()
                formData.append('number', this.user.phone)
                formData.append('message', String(txt))
                this.axios({
                    url: '/api/notification',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                    .then(response => (
                        console.log(response.data)
                ))
                    .catch(err => console.log(err))
            },

            async closeCameraModal() {
                await clearInterval(this.intervalId)
                await this.insertData()
                await this.$refs.video.srcObject.getTracks()[0].stop()
                await this.$refs['camera-modal'].hide()
                await this.notification()
                this.usernameThatVerified = ''
                this.voteId = ''
                this.hasVoted = true
                await this.$refs['success-modal'].show()
            },

            async closeSuccessModal() {
                await this.$refs['success-modal'].hide()
            },

            showModal() {
                this.$refs['camera-modal'].show()
                this.startCamera()
            },
        }
    }
</script>