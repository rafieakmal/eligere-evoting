<template>
    <section class="py-4 py-xl-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5 col-xxl-4" style="width: 712px;">
                    <h2 class="text-center mb-4">Create a Vote</h2>
                    <p class="text-center subtext">Complete the below fields to create your ballot.</p>
                    <hr class="top-spacer">
                    <div class="card mb-5">
                        <div class="card-body p-sm-5">
                            <form @submit.prevent="addData()" enctype="multipart/form-data">
                                <div class="mb-3"><label class="form-label">Title</label>
                                    <hr class="mid-spacer">
                                    <input v-model="voteTitle" class="form-control" type="text" id="name-2" name="title" required>
                                </div>
                                <div class="mb-3"><label class="form-label">Slug</label>
                                    <hr class="mid-spacer">
                                    <input :value="getSlug" class="form-control" type="text" id="name-2" name="title" placeholder="Automated by system" disabled>
                                </div>
                                <div class="mb-3"><label class="form-label">End Date</label>
                                    <hr class="mid-spacer">
                                    <b-form-datepicker 
                                        id="datepicker" 
                                        v-model="voteEndDate" 
                                        class="mb-2"
                                        :min="min"
                                        required
                                    ></b-form-datepicker>
                                </div>
                                <div class="mb-3"><label class="form-label">End Time</label>
                                    <hr class="mid-spacer">
                                    <b-form-timepicker 
                                        v-model="voteEndDuration" 
                                        show-seconds
                                        now-button
                                        reset-button    
                                    ></b-form-timepicker>
                                </div>
                                <div class="mb-3"><label class="form-label">Description</label>
                                    <hr class="mid-spacer">
                                    <textarea v-model="voteDescription" class="form-control" id="message-2" name="message" rows="2" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col">
                                            <label class="col-form-label">Vote Candidate(s)</label>
                                        </div>
                                        <div class="col text-end">
                                            <button class="btn btn-info btn-sm" @click="addOption()" type="button">Add Candidate
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <hr class="mid-spacer">
                                    <div v-for="(option, index) in optionData" :key="index">
                                        <div class="row custom">
                                            <div class="col">
                                                <input class="form-control form-control-sm" @change="handleFileUpload($event, index)" ref="optionFile" type="file" required accept="image/*" title="Choose your image">
                                            </div>
                                            <div class="col">
                                                <input class="form-control form-control-sm" v-model="option.option_name" type="text" placeholder="Candidate Name" required>
                                            </div>
                                            <div class="col-auto text-end">
                                                <button v-if="index < 1" class="btn btn-danger btn-sm disabled" @click="removeOption(index)" type="button">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                                <button v-else class="btn btn-danger btn-sm" @click="removeOption(index)" type="button">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3"><label class="form-label">Rules</label>
                                    <hr class="mid-spacer">
                                    <div class="accordion" role="tablist" id="accordion-1">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" role="tab">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-1 .item-1" aria-expanded="true" aria-controls="accordion-1 .item-1">Available Rules</button>
                                            </h2>
                                            <div class="accordion-collapse collapse show item-1" role="tabpanel" data-bs-parent="#accordion-1">
                                                <div class="accordion-body">
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <input v-model="settings.usePassword" class="form-check-input" type="checkbox" value="true" id="flexCheckDefault">
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                Use Password
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div v-if="settings.usePassword">
                                                        <b-alert
                                                            :show="dismissCountDown"
                                                            variant="danger"
                                                            @dismissed="dismissCountDown=0"
                                                            @dismiss-count-down="countDownChanged"
                                                        >
                                                            Your password must be 6 numbers long. Please try again.
                                                            <!-- Countdown progress bar -->
                                                            <b-progress
                                                                variant="danger"
                                                                :max="dismissSecs"
                                                                :value="dismissCountDown"
                                                                height="3px"
                                                            ></b-progress>
                                                        </b-alert>
                                                        <b-form  @submit.stop.prevent>
                                                            <b-form-input v-model="votePassword" :state="validation" id="feedback-user" placeholder="Your password"></b-form-input>
                                                            <b-form-invalid-feedback :state="validation">
                                                                Your password must be 6 numbers long.
                                                            </b-form-invalid-feedback>
                                                            <b-form-valid-feedback :state="validation">
                                                                Looks Good.
                                                            </b-form-valid-feedback>
                                                        </b-form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-info d-block w-100" type="submit">Create Vote</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.text-center.subtext {
    margin-top: -25px;
    padding-bottom: 40px;
}
.top-spacer {
    border-radius: 6px;
    border-width: 10px;
    border-color: rgb(0,124,248);
    margin-bottom: -7px;
}
.mid-spacer {
    margin-top: 0px;
    border-radius: 3px;
    border-width: 3px;
    border-color: rgb(114,190,245);
}
.btn.btn-info {
    color: rgb(255,255,255);
}
.row.custom {
    margin-bottom: 10px;
}
</style>

<script>
    export default {
        name: "vote-create",
        data() {
            const now = new Date()
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
            const minDate = new Date(today)
            minDate.setMonth(minDate.getMonth())
            minDate.setDate(minDate.getDate() + 1)
            return {
                min: minDate,
                user: {},
                isLoggedIn: false,
                optionData: [
                    { option_image: '', option_name: '' },
                ],
                formData: {},
                voteTitle: '',
                voteSlug: '',
                voteDescription: '',
                voteEndDate: '',
                voteEndDuration: '',
                votePassword: '',
                settings: {
                    onlyVerified: true,
                    onlyOneIP: false,
                    usePassword: false,
                    onlyVerifiedValue: '1',
                    onlyOneIPValue: '1',
                    usePasswordValue: '0',
                },
                dismissSecs: 3,
                dismissCountDown: 0,
            }
        },
        mounted() {
            this.setUser()
        },
        computed: {
            getSlug: function () {
                var text = this.slugify(this.voteTitle)
                this.voteSlug = text
                return text
            },
            validation() {
                return this.votePassword.length > 5 && this.votePassword.length < 7
            }
        },

        methods: {
            setUser() {
                this.user = this.$cookies.get('user')
                this.isLoggedIn = this.$cookies.get('token') != null
            },
            
            showAlert() {
                this.votePassword = ''
                this.dismissCountDown = this.dismissSecs
            },

            checkPasswordValidity() {
                const valid = this.votePassword.length > 5 && this.votePassword.length < 7
                if (!valid) {
                    return
                }
                return valid
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },

            settingOnlyVerified(event) {
                this.settings.onlyVerified = true
                this.settings.onlyVerifiedValue = event.target.value
            },
            settingOnlyOneIP(event) {
                this.settings.onlyOneIP = true
                this.settings.onlyOneIPValue = event.target.value
            },
            settingUsePassword(event) {
                this.settings.usePassword = true
                this.settings.usePasswordValue = event.target.value
            },
            slugify(text, ampersand = 'and') {
                const a = 'àáäâèéëêìíïîòóöôùúüûñçßÿỳýœæŕśńṕẃǵǹḿǘẍźḧ'
                const b = 'aaaaeeeeiiiioooouuuuncsyyyoarsnpwgnmuxzh'
                const p = new RegExp(a.split('').join('|'), 'g')
                return text.toString().toLowerCase()
                    .replace(/[\s_]+/g, '-')
                    .replace(p, c =>
                    b.charAt(a.indexOf(c)))
                    .replace(/&/g, `-${ampersand}-`)
                    .replace(/[^\w-]+/g, '')
                    .replace(/--+/g, '-')
                    .replace(/^-+|-+$/g, '')
            },
            addOption() {
                this.optionData.push({
                    option_image: '',
                    option_name: ''
                })
            },

            removeOption(index) {
                this.optionData.splice(index, 1)
            },

            uploadOptions(file, name) {
                let formData = new FormData()
                formData.append('file', file)
                formData.append('name', name)
                formData.append('slug', this.voteSlug)
                this.axios({
                    url: '/api/vote/add-options',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                    .then(response => (
                        console.log("Success upload options")
                ))
                    .catch(err => console.log(err))
            },

            addData() {
                if(this.settings.usePassword) {
                    if (!this.checkPasswordValidity()) {
                        return this.showAlert()
                    }
                }
                console.log(this.settings.usePassword ? '1': '0')
                let formData = new FormData()
                formData.append('owner_id', this.user.id)
                formData.append('title', this.voteTitle)
                formData.append('slug', this.voteSlug)
                formData.append('description', this.voteDescription)
                formData.append('end_date', this.voteEndDate)
                formData.append('end_time', this.voteEndDuration)
                formData.append('only_verified', this.settings.onlyVerifiedValue)
                formData.append('only_one_ip', this.settings.onlyOneIPValue)
                formData.append('use_password', this.settings.usePassword ? '1': '0')
                this.axios({
                    url: '/api/vote/add-data',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                    .then(response => (
                        this.addOptions()
                ))
                    .catch(err => console.log(err))
            },

            addPassword() {
                let formData = new FormData()
                formData.append('slug', this.voteSlug)
                formData.append('password', this.votePassword)
                this.axios({
                    url: '/api/vote/insert-password',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Access-Control-Allow-Origin': '*',
                    }
                })
                    .then(response => (
                        console.log("Success add password")
                ))
                    .catch(err => console.log(err))
            },

            async addOptions() {
                await Promise.all(
                    this.optionData.map(async (option) => {
                            const data = await this.uploadOptions(option.option_image, option.option_name)
                    })
                )
                
                if(this.settings.usePassword) {
                    this.addPassword()
                }

                this.$router.push('/')
            },

            handleFileUpload(event, index) {
                const file = event.target.files[0]
                this.optionData.splice(index, 1, {
                    option_image: file,
                    option_name: this.optionData[index].option_name
                })
            }

        },
    }
</script>