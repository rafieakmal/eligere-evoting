import VotePage from '../src/pages/VotePage'
import VoteResultPage from '../src/pages/VoteResultPage'
import HomePage from '../src/pages/HomeComponent'
import VoteForm from '../src/pages/VoteForm'
import NotFound from '../src/pages/NotFoundComponent.vue'
import SignUp from '../src/pages/SignUpComponent.vue'
import Login from '../src/pages/LoginComponent.vue'
import ForgotPassword from '../src/pages/ForgotPasswordComponent.vue'
import ForgotPasswordForm from '../src/components/signup/ForgotPasswordForm.vue'


const routes = [
  {
    path: '/',
    name: 'vote-home',
    component: HomePage,
    meta: {
      requiresAuth: true
    },
    
  },
  {
    path: '/vote/:slug/',
    name: 'vote-page',
    component: VotePage,
    meta: {
      requiresAuth: true
    },

    
  },
  {
    path: '/vote/result/:slug/',
    name: 'vote-result-page',
    component: VoteResultPage,
    meta: {
      requiresAuth: true,
      isAdmin: true
    },
    
  },
  { path: '/sign-up', 
    name: 'sign-up', 
    component: SignUp,
    meta: { 
      isAdmin: true 
    } 
  },
  { path: '/login', 
    name: 'login', 
    component: Login,
    meta: { 
      isGuest: true 
    } 
  },
  { path: '/forgot-password', 
    name: 'forgot-password', 
    component: ForgotPassword,
    meta: { 
      isGuest: true
    } 
  },
  { 
    path: '/reset-password/:token', 
    name: 'reset-password-form', 
    component: ForgotPasswordForm, 
    meta: { 
      isGuest: true,
      isReset: true
    } 
  },
  { path: '/create', 
    name: 'vote-create', 
    component: VoteForm,
    meta: {
      requiresAuth: true,
      isAdmin: true
    },
  },
  { path: '/:pathMatch(.*)*', 
    name: 'not-found', 
    component: NotFound,
  },
]

export default routes
