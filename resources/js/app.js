import './bootstrap';
import { createApp } from 'vue';
import { createStore } from 'vuex';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';

// Import components
import Login from './components/Login.vue';
import HeadteacherRegister from './components/HeadteacherRegister.vue';
import HeadteacherDashboard from './components/HeadteacherDashboard.vue';
import Dashboard from './components/Dashboard.vue';
import VisitList from './components/VisitList.vue';
import VisitForm from './components/VisitForm.vue';
import VisitView from './components/VisitView.vue';
import VisitShare from './components/VisitShare.vue';
import SchoolList from './components/SchoolList.vue';
import SchoolForm from './components/SchoolForm.vue';
import SchoolAccess from './components/SchoolAccess.vue';
import RichTextEditor from './components/RichTextEditor.vue';
import HeadteacherVisitView from './components/HeadteacherVisitView.vue';
import HeadTeacherList from './components/HeadTeacherList.vue';
import Profile from './components/Profile.vue';

// Import Toastr
import 'toastr/build/toastr.min.css';
import toastr from 'toastr';

// Toastr configuration
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 3000,
    extendedTimeOut: 1000,
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

// Vuex Store
const store = createStore({
    state: {
        visits: [],
        schools: [],
        currentVisit: null,
        loading: false,
        error: null,
        isAuthenticated: false
    },
    mutations: {
        SET_VISITS(state, visits) {
            state.visits = visits;
        },
        SET_SCHOOLS(state, schools) {
            state.schools = schools;
        },
        SET_CURRENT_VISIT(state, visit) {
            state.currentVisit = visit;
        },
        SET_LOADING(state, loading) {
            state.loading = loading;
        },
        SET_ERROR(state, error) {
            state.error = error;
        },
        ADD_VISIT(state, visit) {
            state.visits.unshift(visit);
        },
        UPDATE_VISIT(state, updatedVisit) {
            const index = state.visits.findIndex(v => v.id === updatedVisit.id);
            if (index !== -1) {
                state.visits.splice(index, 1, updatedVisit);
            }
        },
        SET_AUTHENTICATED(state, isAuthenticated) {
            state.isAuthenticated = isAuthenticated;
        }
    },
    getters: {
        pendingVisits: (state) => state.visits.filter(v => v.status === 'pending_review'),
        finalizedVisits: (state) => state.visits.filter(v => v.status === 'finalized'),
        recentVisits: (state) => state.visits.slice(0, 5)
    },
    actions: {
        async checkAuth({ commit }) {
            try {
                const response = await fetch('/api/visits', { method: 'HEAD' });
                if (!response.ok) {
                    if (handleAuthError(response.status)) {
                        return false;
                    }
                }
                commit('SET_AUTHENTICATED', response.status !== 401);
                return response.status !== 401;
            } catch (error) {
                commit('SET_AUTHENTICATED', false);
                return false;
            }
        },
        async fetchVisits({ commit }) {
            commit('SET_LOADING', true);
            try {
                const response = await fetch('/api/visits');
                if (!response.ok) {
                    if (handleAuthError(response.status)) {
                        return;
                    }
                    throw new Error('Failed to fetch visits');
                }
                const visits = await response.json();
                commit('SET_VISITS', visits);
            } catch (error) {
                commit('SET_ERROR', error.message);
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async fetchSchools({ commit }) {
            try {
                const response = await fetch('/api/schools');
                if (!response.ok) {
                    if (handleAuthError(response.status)) {
                        return;
                    }
                    throw new Error('Failed to fetch schools');
                }
                const schools = await response.json();
                commit('SET_SCHOOLS', schools);
            } catch (error) {
                commit('SET_ERROR', error.message);
            }
        },
        async createVisit({ commit }, visitData) {
            try {
                const response = await fetch('/api/visits', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(visitData)
                });
                // console.log(visitData);
                if (!response.ok) {
                    if (handleAuthError(response.status)) {
                        return;
                    }
                    throw new Error('Failed to create visit');
                }
                const visit = await response.json();
                commit('ADD_VISIT', visit);
                return visit;
            } catch (error) {
                commit('SET_ERROR', error.message);
                throw error;
            }
        },
        async updateVisit({ commit }, { id, data }) {
            try {
                const response = await fetch(`/api/visits/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                if (!response.ok) {
                    if (handleAuthError(response.status)) {
                        return;
                    }
                    throw new Error('Failed to update visit');
                }
                const visit = await response.json();
                commit('UPDATE_VISIT', visit);
                return visit;
            } catch (error) {
                commit('SET_ERROR', error.message);
                throw error;
            }
        },
        async updateVisitStatus({ commit }, { visitId, newStatus }) {
            try {
                const response = await fetch(`/api/visits/${visitId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                if (!response.ok) {
                    if (handleAuthError(response.status)) {
                        return;
                    }
                    throw new Error('Failed to update visit status');
                }
                const visit = await response.json();
                commit('UPDATE_VISIT', visit);
                return visit;
            } catch (error) {
                commit('SET_ERROR', error.message);
                throw error;
            }
        }
    }
});

// Vue Router
const routes = [
    { path: '/login', component: Login },
    { path: '/register', component: HeadteacherRegister },
    { path: '/profile', component: Profile },
    { path: '/headteacher/dashboard', component: HeadteacherDashboard },
    { path: '/dashboard', component: Dashboard },
    { path: '/visits', component: VisitList },
    { path: '/visits/create', component: VisitForm },
    { path: '/visits/:id', component: VisitView },
    { path: '/visits/:id/edit', component: VisitForm },
    { path: '/visits/share/:token', component: VisitShare },
    { path: '/headteacher/visits/:id', component: HeadteacherVisitView },
    { path: '/schools', component: SchoolList },
    { path: '/schools/create', component: SchoolForm },
    { path: '/schools/:id/edit', component: SchoolForm },
    { path: '/schools/token/:token', component: SchoolAccess },
    { path: '/headteachers', component: HeadTeacherList },
    { path: '/', redirect: '/dashboard' }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Global error handler for authentication errors
function handleAuthError(status) {
    if (status === 401 || status === 422 || status === 302) {
        store.commit('SET_AUTHENTICATED', false);
        // Redirect to SPA entry (login page)
        if (router.currentRoute.value.path !== '/login') {
            router.push('/login');
        }
        return true; // Error was handled
    }
    return false; // Error was not handled
}

// Navigation guard to check authentication
router.beforeEach(async (to, from, next) => {
    // Always allow access to /login and /register for unauthenticated users
    if (to.path === '/login' || to.path === '/register' || to.path.startsWith('/visits/share/') || to.path.startsWith('/schools/token/')) {
        try {
            const response = await fetch('/api/user');
            let user = undefined;
            if (response.ok) {
                try {
                    user = await response.json();
                } catch (e) {
                    user = undefined;
                }
            }
            if (response.status === 302 || user === undefined || user === null) {
                store.commit('SET_AUTHENTICATED', false);
                return next();
            }
            store.commit('SET_AUTHENTICATED', true);
            if (to.path === '/login' || to.path === '/register') {
                if (user.role === 'admin') {
                    return next('/dashboard');
                } else if (user.role === 'headteacher') {
                    return next('/headteacher/dashboard');
                }
            }
        } catch {
            store.commit('SET_AUTHENTICATED', false);
        }
        return next();
    }

    // For all other routes, require authentication and check role
    try {
        const response = await fetch('/api/user');
        let user = undefined;
        if (response.ok) {
            try {
                user = await response.json();
            } catch (e) {
                user = undefined;
            }
        }
        if (response.status === 302 || user === undefined || user === null) {
            store.commit('SET_AUTHENTICATED', false);
            if (to.path !== '/login') {
                return next('/login');
            } else {
                return next();
            }
        }
        store.commit('SET_AUTHENTICATED', true);
        // Prevent headteachers from accessing admin-only routes
        if (user.role === 'headteacher' && !to.path.startsWith('/headteacher/')) {
            return next('/headteacher/dashboard');
        }
        return next();
    } catch {
        store.commit('SET_AUTHENTICATED', false);
        if (to.path !== '/login') {
            return next('/login');
        } else {
            return next();
        }
    }
});

// Create and mount the app
const app = createApp(App);
app.use(store);
app.use(router);

// Make toastr available globally
app.config.globalProperties.$toastr = toastr;

app.mount('#app');
