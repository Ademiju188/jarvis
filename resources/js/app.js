import './bootstrap';
import { createApp } from 'vue';
import { createStore } from 'vuex';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';

// Import components
import Dashboard from './components/Dashboard.vue';
import VisitForm from './components/VisitForm.vue';
import VisitList from './components/VisitList.vue';
import VisitView from './components/VisitView.vue';
import VisitShare from './components/VisitShare.vue';
import SchoolList from './components/SchoolList.vue';
import SchoolForm from './components/SchoolForm.vue';

// Import Toastr
import 'toastr/build/toastr.min.css';
import toastr from 'toastr';

// Configure Toastr
toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: "toast-top-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
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
        error: null
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
        }
    },
    getters: {
        pendingVisits: (state) => state.visits.filter(v => v.status === 'pending_review'),
        finalizedVisits: (state) => state.visits.filter(v => v.status === 'finalized'),
        recentVisits: (state) => state.visits.slice(0, 5)
    },
    actions: {
        async fetchVisits({ commit }) {
            commit('SET_LOADING', true);
            try {
                const response = await fetch('/api/visits');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const visits = await response.json();
                commit('SET_VISITS', visits);
            } catch (error) {
                commit('SET_ERROR', error.message);
                console.error('Error fetching visits:', error);
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async fetchSchools({ commit }) {
            commit('SET_LOADING', true);
            try {
                const response = await fetch('/api/schools');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const schools = await response.json();
                commit('SET_SCHOOLS', schools);
            } catch (error) {
                commit('SET_ERROR', error.message);
                console.error('Error fetching schools:', error);
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async createVisit({ commit }, visitData) {
            commit('SET_LOADING', true);
            try {
                const response = await fetch('/api/visits', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(visitData)
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const visit = await response.json();
                commit('ADD_VISIT', visit);
                toastr.success('Visit record created successfully!');
                return visit;
            } catch (error) {
                commit('SET_ERROR', error.message);
                toastr.error('Failed to create visit record. Please try again.');
                throw error;
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async updateVisit({ commit }, { id, data }) {
            commit('SET_LOADING', true);
            try {
                const response = await fetch(`/api/visits/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const visit = await response.json();
                commit('UPDATE_VISIT', visit);
                toastr.success('Visit record updated successfully!');
                return visit;
            } catch (error) {
                commit('SET_ERROR', error.message);
                toastr.error('Failed to update visit record. Please try again.');
                throw error;
            } finally {
                commit('SET_LOADING', false);
            }
        }
    }
});

// Vue Router
const routes = [
    { path: '/', component: Dashboard },
    { path: '/visits', component: VisitList },
    { path: '/visits/create', component: VisitForm },
    { path: '/visits/:id', component: VisitView },
    { path: '/visits/share/:token', component: VisitShare },
    { path: '/schools', component: SchoolList },
    { path: '/schools/create', component: SchoolForm }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Create and mount the app
const app = createApp(App);
app.use(store);
app.use(router);

// Make toastr available globally
app.config.globalProperties.$toastr = toastr;

app.mount('#app');
