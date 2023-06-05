require('./bootstrap')

import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import router from './router/index'
import App from './src/App.vue'
import Vuesax from 'vuesax'
import store from './store/index'
import VueMeta from 'vue-meta'
import Vue2Editor from "vue2-editor"
import VueClipboard from 'vue-clipboard2'
import Vuetify from 'vuetify/lib/framework'
import VueSplit from 'vue-split-panel'
// import VueSocialauth from 'vue-social-auth'
import VueCookies from 'vue-cookies'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import * as tf from '@tensorflow/tfjs'
import * as faceapi from 'face-api.js'
import * as Sentry from "@sentry/vue";

import '@tensorflow/tfjs-core'
import '@tensorflow/tfjs-converter'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vuesax/dist/vuesax.css'

Vue.use(VueMeta)
Vue.use(VueRouter)
Vue.use(VueAxios, axios)
Vue.use(Vuesax)
Vue.use(VueSplit)
Vue.use(VueClipboard)
Vue.use(Vue2Editor)
Vue.use(Vuetify)

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueCookies)

// Vue.use(VueSocialauth, {
//   providers: {
//     google: {
//       clientId: '',
//       redirectUri: '/auth/google/callback'
//     }
//   }
// })


Vue.config.productionTip = false;

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.isGuest)) {
    let token = Vue.$cookies.get('token') != null
    if (token) {
      next({
        path: '/',
      })
    } else {
        if (to.matched.some(record => record.meta.isReset)) {
          let email = Vue.$cookies.get('reset_email') != null
          if (!email) {
            next({
              path: '/',
            })
          } else {
            next()
          }
        }
      next()
    }
  } else if (to.matched.some(record => record.meta.requiresAuth)) {
    let token = Vue.$cookies.get('token') != null
    if (!token) {
      next({
        path: '/login',
      })
    } else {
      let user = Vue.$cookies.get('user')
      let roles = user.role
      if (to.matched.some(record => record.meta.isUser)) {
        if (roles.includes('user')) next()
        else if (roles === 'admin') {
          next({
            path: '/',
          })
        }
      } else if (to.matched.some(record => record.meta.isAdmin)) {
        if (roles.includes('admin')) next()
        else if (roles === 'user') {
          next({
            path: '/',
          })
        }
      } else {
        next()
      }
    }
  } else {
    next()
  }
})

Sentry.init({
  Vue,
  dsn: "https://b928cef0bb7a48bcb4e395ae5405bcb9@o4505305735561216.ingest.sentry.io/4505305736937472",
  integrations: [
    new Sentry.BrowserTracing({
      tracePropagationTargets: ["localhost", /^https:\/\/yourserver\.io\/api/],
      routingInstrumentation: Sentry.vueRouterInstrumentation(router),
    }),
    new Sentry.Replay(),
  ],
  // Performance Monitoring
  tracesSampleRate: 1.0, // Capture 100% of the transactions
  // Session Replay
  replaysSessionSampleRate: 0.1,
  replaysOnErrorSampleRate: 1.0,
})

const app = new Vue({
  el: '#app',
  router,
  store: store,
  components: {
    App
  }
})
