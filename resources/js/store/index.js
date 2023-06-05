import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
      voteData: {},

      displayDays: 0,
      displayHours: 0,
      displayMinutes: 0,
      displaySeconds: 1,

      quoteOrigin: 'placeholder',
      quotePosition: 'bottom-center',
      quoteColor: '#00cc00',
      quoteProgress: 'auto',
      quoteDuration: '2500',
      quoteBorder: '#FFFFFF',
      quoteText: 'placeholder',

      loadingTime: 2500,
      loadingType: 'circles',
      loadingText: 'Loading...',
      loadingColor: '#311e8e',
    },
    getters: {
      //
    },
    mutations: {
      addVoteData(state, newData) {
        state.voteData = newData
      },

      addDays(state, newDays) {
        state.displayDays = newDays
      },

      addHours(state, newHours) {
        state.displayHours = newHours
      },

      addMinutes(state, newMinutes) {
        state.displayMinutes = newMinutes
      },

      addSeconds(state, newSeconds) {
        state.displaySeconds = newSeconds
      },
    }
})

export default store;