<template>
    <section class="py-4 py-md-5 my-5">
        <div class="container py-md-5">
            <div class="row">
                <div class="col-md-6 text-center"><img class="img-fluid w-100" src="assets/img/illustrations/register.svg"></div>
                <div class="col-md-5 col-xl-4 text-center text-md-start">
                    <h2 class="display-6 fw-bold mb-5"><span class="underline pb-1">Sign up</span></h2>
                    <form @submit.prevent="registration">
                        <div class="mb-3">
                            <input v-model="user.username" class="shadow-sm form-control" type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input v-model="user.email" class="shadow-sm form-control" type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input v-model="user.password" v-show="!showPassword" @input="validatePassword" class="shadow-sm form-control" type="password" name="password" placeholder="Password" minlength="8" required>
                            <input v-model="user.password" v-show="showPassword" @input="validatePassword" class="shadow-sm form-control" type="text" name="password" placeholder="Password" minlength="8" required>
                        </div>
                        <div class="mb-3">
                            <input v-model="user.password_confirmation" v-show="!showPassword" @input="validatePassword" class="shadow-sm form-control" type="password" name="password_repeat" placeholder="Repeat Password" minlength="8" required>
                            <input v-model="user.password_confirmation" v-show="showPassword" @input="validatePassword" class="shadow-sm form-control" type="text" name="password_repeat" placeholder="Repeat Password" minlength="8" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input v-model="showPassword" class="form-check-input" type="checkbox" value="true" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Show Password
                                </label>
                            </div>
                        </div>
                        <div v-if="errorMessage">
                            <p style="color: red;">{{ errorMessage }}</p>
                        </div>
                        <div class="mb-5">
                            <button v-show="validated" class="btn btn-primary shadow" type="submit">Create account</button>
                            <button v-show="!validated" class="btn btn-primary shadow disabled" type="submit">Create account</button>
                        </div>
                        <p class="text-muted">Have an account? 
                            <a href="/login">Log in&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-arrow-narrow-right">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <line x1="15" y1="16" x2="19" y2="12"></line>
                            <line x1="15" y1="8" x2="19" y2="12"></line>
                            </svg>
                        </a>&nbsp;</p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Phone Number Modal -->
        <b-modal ref="phone-modal" size="lg"
            id="phone-modal"
            hide-backdrop content-class="shadow" 
            centered title="Enter your whatsapp number"
            @show="resetPhoneModal"
            @hidden="resetPhoneModal"
            @ok="handlePhoneModal"
        >
            <b-alert
                :show="phoneCountDown"
                variant="danger"
                @dismissed="phoneCountDown=0"
                @dismiss-count-down="phoneCountDownChanged"
            >
                {{ messageError.phone }}
                <!-- Countdown progress bar -->
                <b-progress
                    variant="danger"
                    :max="phoneSecs"
                    :value="phoneCountDown"
                    height="3px"
                ></b-progress>
            </b-alert>

            <!-- Phone form -->
            <form ref="form" @submit.stop.prevent="handlePhoneSubmit">
                <b-form-group>
                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                        <template #prepend>
                            <b-input-group-text >
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="25" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                </svg>
                            </b-input-group-text>
                        </template>
                        <b-form-input 
                            id="phone-input" 
                            type="tel" 
                            v-model="user.phone"
                            :state="phoneState"
                            :value="sanitizePhone">
                        </b-form-input>
                    </b-input-group>
                </b-form-group>
            </form>
        </b-modal>

        <!-- OTP modal -->
        <b-modal ref="code-modal" size="lg"
            id="code-modal"
            hide-backdrop content-class="shadow" 
            centered title="We sent you a code"
            @show="resetCodeModal"
            @hidden="resetCodeModal"
            @ok="handleCodeModal"
        >
            <b-alert
                :show="dismissCountDown"
                variant="danger"
                @dismissed="dismissCountDown=0"
                @dismiss-count-down="countDownChanged"
            >
                Wrong code. Please try again.
                <!-- Countdown progress bar -->
                <b-progress
                    variant="danger"
                    :max="dismissSecs"
                    :value="dismissCountDown"
                    height="3px"
                ></b-progress>
            </b-alert>

            <!-- Code form -->
            <form ref="form" @submit.stop.prevent="handleCodeSubmit">
                <b-form-group>
                    <b-input-group>
                        <template #prepend>
                            <b-input-group-text >
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="25" fill="currentColor" class="bi bi-send-check" viewBox="0 0 16 16">
                                <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372l2.8-7Zm-2.54 1.183L5.93 9.363 1.591 6.602l11.833-4.733Z"/>
                                <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z"/>
                                </svg>
                            </b-input-group-text>
                        </template>
                        <b-form-input
                            id="code-input"
                            type="number"
                            v-model="code"
                            :state="CodeState">
                        </b-form-input>
                    </b-input-group>
                </b-form-group>            
            </form>
            <a v-show="!isTimer" @click="resendCode">Resend code</a>
            <a v-show="isTimer">Resend code in {{ timeRemaining }}</a>
        </b-modal>

        <!-- KTP modal -->
        <b-modal ref="ktp-modal" id="ktp-modal" hide-backdrop content-class="shadow" size="lg" centered>
            <template #modal-header>
                <h5 class="text-dark">Add your KTP</h5>
            </template>
            <template #default>
                <vue-dropzone ref="myVueDropzone" id="dropzone" 
                    :options="dropzoneOptions" 
                    :useCustomSlot=true 
                    v-on:vdropzone-success="successEvent"
                    v-on:vdropzone-sending="sendingEvent"
                >
                    <div class="dropzone-custom-content">
                        <canvas ref="canvas" class="face-canvas" width="100%" height="100%"></canvas>
                        <img class="dropzone-custom-image" src="/assets/img/illustrations/ktp.png">
                        <h3 class="dropzone-custom-title">Drag and drop your KTP here!</h3>
                    </div>
                </vue-dropzone>
                <div v-if="messageError.ktp">
                    <p style="color: red;">{{ messageError.ktp }}</p>
                </div>
            </template>
            <template #modal-footer="{ ok }">
                <b-button v-show="state.isLoading" variant="primary" disabled>
                    Waiting...
                </b-button>
                <b-button v-show="!state.isLoading" variant="primary" disabled>
                    Continue
                </b-button>
            </template> 
        </b-modal>

        <!-- Confirm Ktp modal -->
        <b-modal ref="confirm-ktp-modal" size="lg"
            id="confirm-ktp-modal"
            hide-backdrop content-class="shadow" 
            centered title="Please confirm your data"
            @show="resetKtpModal"
            @hidden="resetKtpModal"
            @ok="handleKtpModal"
        >
            <!-- Code form -->
            <form ref="form" @submit.stop.prevent="handleKtpSubmit">
                <b-form-group label="NIK" label-for="nik-input" class="mb-0">
                    <b-form-input
                        id="nik-input"
                        v-model="user.nik"
                        placeholder="Enter your NIK"
                    ></b-form-input>
                </b-form-group>

                <b-form-group label="Gender" label-for="gender-input" class="mb-0">
                    <b-form-input
                        id="gender-input"
                        v-model="user.gender"
                        placeholder="Enter your Gender"
                    ></b-form-input>
                </b-form-group>

                <b-form-group label="Regency" label-for="regency-input" class="mb-0">
                    <b-form-input
                        id="regency-input"
                        v-model="user.regency"
                        placeholder="Enter your Regency"
                    ></b-form-input>
                </b-form-group>

                <b-form-group label="Province" label-for="province-input" class="mb-0">
                    <b-form-input
                        id="province-input"
                        v-model="user.province"
                        placeholder="Enter your Province"
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>

        <!-- Checked modal -->
        <b-modal ref="success-modal" id="success-camera" hide-backdrop content-class="shadow" centered>
            <template #modal-header>
                <h5 class="text-success">Successfully Signed Up</h5>
            </template>
            <template #default>
                <img src="/assets/img/illustrations/icon-check3.png" class="img-center">
            </template>
            <template #modal-footer="{ ok }">
                <b-button variant="success" @click="closeSuccessModal()">
                    Login Here
                </b-button>
            </template>
        </b-modal>
    </section>
</template>

<style scoped>
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
.face-canvas {
  position: absolute;
  top: 0;
  left: 0;
  object-fit: fill;
}

.img-center {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
}

.face-video {
  object-fit: fill;
}

.dropzone-custom-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.dropzone-custom-image {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 30%;
}

.dropzone-custom-title {
  margin-top: 0;
  color: #128cd7;
}

.subtitle {
  color: #314b5f;
}

</style>
<script>
    import * as faceapi from 'face-api.js'
    import canvas from "canvas"
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {
        name: "sign-up",
        metaInfo: {
            title: "Eligere - Registration",
        },
        components: {
            vueDropzone: vue2Dropzone
        },
        data() {
            return {
                showPassword: false,
                blob: '',
                user: {
                    email: '',
                    password: '',
                    username: '',
                    password_confirmation: '',
                    phone: '',
                    image: '',
                    nik: '',
                    province: '',
                    regency: '',
                    gender: '',
                },
                state: {
                    isLoading: true,
                },
                messageError: {
                    phone: '',
                    ktp: null
                },
                captureSrc:'',
                errorMessage: '',
                isCameraLoaded: false,
                dismissSecs: 5,
                dismissCountDown: 0,
                phoneSecs: 5,
                phoneCountDown: 0,
                videoWidth: 640,
                videoHeight: 480,
                isFaceDetected: false,
                is: false,
                intervalId: null,
                code: '',
                phone: '',
                validated: false,
                generatedCode: '',
                timer: null,
                isTimer: false,
                timeRemaining: 30,
                displaySize: { width: 640, height: 480 },
                dropzoneOptions: {
                    url: 'http://localhost:70/post',
                    thumbnailWidth: 150,
                    maxFiles: 1,
                    addRemoveLinks: true,
                    duplicateCheck: true,
                }
            }
        },
        beforeDestroy() {
            clearInterval(this.intervalId)
            clearInterval(this.timer)
        },
        computed: {
            sanitizePhone() {
                var text = this.checkPhone(this.user.phone)
                this.user.phone = text
                return this.user.phone
            },

            phoneState() {
                return this.user.phone.length >= 11 && this.user.phone.length < 14 ? true : false
            },

            CodeState() {
                return this.code.length > 5 && this.code.length < 7 ? true : false
            }
        },
        methods: {
            successEvent(file, response) {
                this.parseKTP(file)
            },
            sendingEvent(file, xhr, formData) {
                this.state.isLoading = true
            },
            async getKtpData(file, img) {
                let formData = new FormData()
                formData.append('file', file)
                this.axios({
                    url: '/api/ktp',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                    .then(response => {
                        this.user.nik = response.data.message.nik
                        this.user.province = response.data.message.provinsi
                        this.user.regency = response.data.message.kabupaten
                        this.user.gender = response.data.message.kelamin
                        this.messageError.ktp = null
                        this.loadNewModels().then(() => {
                            this.cropKTP(img)
                        })
                    })
                    .catch(err => {
                        this.messageError.ktp = 'Failed to detect your KTP'
                        this.$refs.myVueDropzone.removeAllFiles()
                    }
                )
            },
            async parseKTP(file) {
                const image = new Image()
                image.src = URL.createObjectURL(file)
                this.user.image = image.src
                await new Promise(resolve => image.onload = resolve)
                const canvas = this.$refs.canvas
                canvas.width = image.width
                canvas.height = image.height
                const ctx = canvas.getContext('2d')
                ctx.drawImage(image, 0, 0, image.width, image.height)
                ctx.canvas.hidden = true
                const data = await this.getKtpData(file, image)
            },

            async cropKTP(image) {
                const canvas = this.$refs.canvas
                const detections = await faceapi.detectAllFaces(canvas).withFaceLandmarks().withFaceDescriptors()
                if(detections) {
                    this.messageError.ktp = null
                    const faceCanvas = document.createElement('canvas')
                    faceCanvas.width = detections[0].detection.box.width
                    faceCanvas.height = detections[0].detection.box.height
                    const faceCtx = faceCanvas.getContext('2d')
                    faceCtx.drawImage(image, detections[0].detection.box.x + 10, 
                        detections[0].detection.box.y + 10, detections[0].detection.box.width + 10, 
                        detections[0].detection.box.height + 10, 0, 0, detections[0].detection.box.width + 10, 
                        detections[0].detection.box.height + 10)
                    this.blob = await new Promise(resolve => faceCanvas.toBlob(resolve, 'image/jpeg', 0.9))
                    this.closeModal()
                } else {
                    this.$refs.myVueDropzone.removeAllFiles()
                    this.messageError.ktp = 'Failed to detect your KTP image'
                }
            },
            async startTimer() {
                this.isTimer = true
                this.timer = setInterval(() => {
                    this.timeRemaining--
                    if (this.timeRemaining === 0) {
                        clearInterval(this.timer)
                        this.timeRemaining = 30
                        this.isTimer = false
                    }
                }, 1000)
            },
            async resendCode() {
                await this.getCode(this.phone)
                await this.startTimer()
                console.log('resent')
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },

            phoneCountDownChanged(dismissCountDown) {
                this.phoneCountDown = dismissCountDown
            },

            checkPhone(text) {
                return text.toString().toLowerCase()
                    .replace('+62', '')
                    .replace('62', '')
            },

            checkCodeValidity() {
                const valid = this.code.length > 5 && this.code.length < 7 ? true : false
                const validate = parseInt(this.code) === this.generatedCode ? true : false
                if (!valid || !validate) {
                    return
                }
                return validate
            },

            checkPhoneValidity() {
                const phoneRegex = /^(^\+62\s?|^0)(\d{3,4}-?){2}\d{3,4}$/
                const valid = this.user.phone.length > 9 && this.user.phone.length < 14 ? true : false
                const validate = phoneRegex.test(this.user.phone)
                if (!valid || !validate) {
                    return
                }
                return validate
            },

            resetPhoneModal() {
                this.user.phone = ''
            },

            resetCodeModal() {
                this.code = ''
            },

            resetKtpModal() {
                this.nik = ''
            },

            showPhoneAlert(message) {
                this.resetPhoneModal()
                this.messageError.phone = message
                this.phoneCountDown = this.phoneSecs
            },

            showCodeAlert() {
                this.resetCodeModal()
                this.dismissCountDown = this.dismissSecs
            },

            handleCodeModal(bvModalEvent) {
                bvModalEvent.preventDefault()
                this.handleCodeSubmit()
            },

            handleKtpModal(bvModalEvent) {
                bvModalEvent.preventDefault()
                this.handleKtpSubmit()
            },

            handlePhoneModal(bvModalEvent) {
                bvModalEvent.preventDefault()
                this.handlePhoneSubmit()
            },

            handleKtpSubmit() {
                this.$nextTick(() => {
                    this.$bvModal.hide('confirm-ktp-modal')
                    this.addData(this.blob)
                })
            },

            handleCodeSubmit() {
                if (!this.checkCodeValidity()) {
                    return this.showCodeAlert()
                }
                this.$nextTick(() => {
                    this.$bvModal.hide('code-modal')
                    this.showModal()
                })
            },

            showRegisteredAlert() {
                this.resetPhoneModal()
                this.messageError.phone = 'Your phone number registered, please login or use another number'
                this.phoneCountDown = this.phoneSecs
            },

            async getNumber(param) {
                const number = param
                axios.get(`/api/check-phone?number=${number}`)
                .then(response => {
                    console.log(response.data)
                    if(!response.data) {
                        this.getCode(number)
                    } else {    
                        this.showRegisteredAlert()
                    }
                })
                .catch(error => {
                    console.log(error)
                });
            },
            
            async handlePhoneSubmit() {
                if (!this.checkPhoneValidity()) {
                    return this.showPhoneAlert('Wrong phone number. Please try again.')
                }
                await this.getNumber(this.user.phone)
            },

            async showCodeModal() {
                await this.startTimer()
                this.$refs['code-modal'].show()
            },

            showPhoneModal() {
                this.$refs['phone-modal'].show()
            },

            showModal() {
                this.$refs['ktp-modal'].show()
            },

            showKtpModal() {
                this.$refs['ktp-modal'].show()
            },

            stopCamera() {
                const stream = this.$refs.video.srcObject
                if (stream) {
                    stream.getTracks().forEach((track) => {
                        track.stop();
                    })
                    this.$refs.video.srcObject = null;
                }
            },

            async closeSuccessModal() {
                await this.$refs['success-modal'].hide()
                this.$router.push({name: 'login'}).catch(() => {})

            },

            async closeModal() {
                await this.$refs['ktp-modal'].hide()
                this.$refs['confirm-ktp-modal'].show()
            },

            // async takePhoto() {
            //     try {
            //         await clearInterval(this.intervalId)
            //         const canvas = this.$refs.canvas
            //         canvas.width = this.videoWidth
            //         canvas.height = this.videoHeight

            //         const context = this.$refs.canvas.getContext('2d')
            //         const draw = context.drawImage(this.$refs.video, 0, 0, canvas.width, canvas.height)
            //         context.canvas.hidden = true

            //         const canvasImg = document.getElementById("photoTaken")
            //         var image = canvasImg.toDataURL("image/png")

            //         const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.9))
            //         this.addData(blob)
            //     } catch(err) {
            //         console.log(err)
            //     }
                
            // },

            async getCode(phone) {
                axios.get('/api/get-code')
                .then(response => {
                    this.generatedCode = response.data.code
                    this.sendSMS(phone)
                })
                .catch(error => {
                    this.showPhoneAlert('Failed to send number')
                })
            },

            sendSMS(phone) {
                let formData = new FormData()
                this.phone = phone
                formData.append('number', phone)
                formData.append('message', this.generatedCode)
                this.axios({
                    url: '/api/send-code',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                    .then(response => {
                        if(response.data.code === 500) {
                            this.showPhoneAlert(response.data.status)
                        } else {
                            this.$nextTick(() => {
                                this.$bvModal.hide('phone-modal')
                                this.showCodeModal()
                            })
                        }
                    })
                    .catch(err => {
                        this.showPhoneAlert('Failed to get code')
                    })
            },

            addData(blob) {
                let formData = new FormData()
                formData.append('file', blob, 'image.jpg')
                formData.append('name', this.user.username)
                this.axios({
                    url: '/api/upload',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                .then(response => {
                    this.messageError.ktp = null
                    this.insertAccount()
                })
                .catch(err => {
                    this.$refs.myVueDropzone.removeAllFiles()
                    this.messageError.ktp = 'Failed to add your KTP data'
                })
            },

            insertAccount() {
                let formData = new FormData()
                formData.append('password', this.user.password)
                formData.append('email', this.user.email)
                formData.append('username', this.user.username)
                formData.append('phone', this.phone)
                formData.append('password_confirmation', this.user.password_confirmation)
                this.axios({
                    url: '/api/registration',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                    .then(response => (
                        this.$refs['success-modal'].show()
                ))
                    .catch(err => 
                        console.log(err)
                    )
            },

            async loadNewModels() {
                await Promise.all([
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.tf.setBackend('cpu'),
                    tf.ready(),
                ]).then(() => {
                    console.log('Face model loaded')
                })
            },

            // async startCamera() {
            //     try {
            //         await navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            //             .then(stream => {   
            //                 this.$refs.video.srcObject = stream
            //                 this.isCameraLoaded = true
            //                 this.$refs.video.addEventListener('loadeddata', () => {
            //                     const canvas = this.$refs.canvas
            //                     canvas.width = this.videoWidth
            //                     canvas.height = this.videoHeight

            //                     faceapi.matchDimensions(canvas, this.displaySize)

            //                     const detectionOptions = new faceapi.TinyFaceDetectorOptions({ 
            //                         inputSize: 224, 
            //                         scoreThreshold: 0.7 
            //                     })
            //                     this.intervalId = setInterval(async () => {
            //                         const detections = await faceapi.detectSingleFace(this.$refs.video, detectionOptions)
            //                         if (detections) {
            //                             this.isFaceDetected = true
            //                             this.takePhoto()
            //                         } else {
            //                             this.isFaceDetected = false
            //                         }
            //                     }, 100);
            //                 })
            //             })
            //     } catch (error) {
            //         console.log(error)
            //     }
            // },

            validatePassword() { 
                if (this.user.password !== this.user.password_confirmation) {
                    this.errorMessage = 'Passwords do not match'
                    this.validated = false
                    return false
                }
                this.errorMessage = ''
                this.validated = true
                return true
            },

            registration() {
                this.showPhoneModal()
            }
        }

    }
    
</script>