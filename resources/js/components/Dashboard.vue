<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-semibold text-gray-900 mb-2">Welcome to Jarvis Education</h1>
                <p class="text-gray-600">Manage your school visits and create professional records</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <transition enter-active-class="transition-all duration-500 ease-out"
                enter-from-class="opacity-0 transform translateY(20px) scale(0.9)"
                enter-to-class="opacity-100 transform translateY(0) scale(1)" appear>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift"
                    style="animation-delay: 0.1s;">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Visits</p>
                                <p class="text-2xl font-bold text-gray-900">{{ visits.length }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>

            <transition enter-active-class="transition-all duration-500 ease-out"
                enter-from-class="opacity-0 transform translateY(20px) scale(0.9)"
                enter-to-class="opacity-100 transform translateY(0) scale(1)" appear>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift"
                    style="animation-delay: 0.2s;">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Schools</p>
                                <p class="text-2xl font-bold text-gray-900">{{ schools.length }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>

            <transition enter-active-class="transition-all duration-500 ease-out"
                enter-from-class="opacity-0 transform translateY(20px) scale(0.9)"
                enter-to-class="opacity-100 transform translateY(0) scale(1)" appear>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift"
                    style="animation-delay: 0.3s;">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pending Review</p>
                                <p class="text-2xl font-bold text-gray-900">{{ pendingVisits.length }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>

            <transition enter-active-class="transition-all duration-500 ease-out"
                enter-from-class="opacity-0 transform translateY(20px) scale(0.9)"
                enter-to-class="opacity-100 transform translateY(0) scale(1)" appear>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift"
                    style="animation-delay: 0.4s;">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Finalised</p>
                                <p class="text-2xl font-bold text-gray-900">{{ finalisedVisits.length }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>

            <transition enter-active-class="transition-all duration-500 ease-out"
                enter-from-class="opacity-0 transform translateY(20px) scale(0.9)"
                enter-to-class="opacity-100 transform translateY(0) scale(1)" appear>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift"
                    style="animation-delay: 0.5s;">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Headteachers</p>
                                <p class="text-2xl font-bold text-gray-900">{{ headteachersCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <transition enter-active-class="transition-all duration-600 ease-out"
                enter-from-class="opacity-0 transform translateX(-30px)"
                enter-to-class="opacity-100 transform translateX(0)" appear>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <router-link to="/visits/create"
                                class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-300 group hover-lift">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-700 transition-all duration-300 group-hover:scale-110">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Create New Visit Record</p>
                                    <p class="text-sm text-gray-500">Document a recent school visit</p>
                                </div>
                            </router-link>

                            <router-link to="/schools/create"
                                class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-all duration-300 group hover-lift">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-700 transition-all duration-300 group-hover:scale-110">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Add New School</p>
                                    <p class="text-sm text-gray-500">Register a new school</p>
                                </div>
                            </router-link>

                            <router-link to="/visits"
                                class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-all duration-300 group hover-lift">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:bg-purple-700 transition-all duration-300 group-hover:scale-110">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">View All Visits</p>
                                    <p class="text-sm text-gray-500">Browse and manage visit records</p>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- Recent Visits -->
            <transition enter-active-class="transition-all duration-600 ease-out"
                enter-from-class="opacity-0 transform translateX(30px)"
                enter-to-class="opacity-100 transform translateX(0)" appear>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Visits</h3>
                        <div v-if="recentVisits.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No visits recorded yet</p>
                        </div>
                        <div v-else class="space-y-3">
                            <transition-group name="stagger" tag="div" class="space-y-3">
                                <div v-for="(visit, index) in recentVisits" :key="visit.id"
                                    class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300 cursor-pointer hover-lift"
                                    @click="$router.push(`/visits/${visit.id}`)"
                                    :style="{ animationDelay: `${index * 0.1}s` }">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ visit.school.name }}</p>
                                        <p class="text-sm text-gray-500">{{ formatDate(visit.visit_date) }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span :class="getStatusClass(visit.status)"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            {{ formatStatus(visit.status) }}
                                        </span>
                                    </div>
                                </div>
                            </transition-group>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    name: 'Dashboard',
    data() {
        return {
            headteachers: []
        }
    },
    computed: {
        ...mapState(['visits', 'schools']),
        ...mapGetters(['pendingVisits', 'finalisedVisits', 'recentVisits']),

        headteachersCount() {
            return this.headteachers.length;
        }
    },
    async mounted() {
        // Prevent headteachers from accessing this dashboard
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
            if (!user || user.role !== 'admin') {
                this.$router.push('/headteacher/dashboard');
            } else {
                // Load dashboard data
                await this.fetchVisits();
                await this.fetchSchools();
                await this.loadHeadteachersCount();
            }
        } catch {
            this.$router.push('/login');
        }
    },
    methods: {
        ...mapActions(['fetchVisits', 'fetchSchools']),
        formatDate(date) {
            return new Date(date).toLocaleDateString('en-GB', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        },

        formatStatus(status) {
            const statusMap = {
                draft: 'Draft',
                pending_review: 'Pending Review',
                approved: 'Approved',
                finalised: 'Finalised'
            }
            return statusMap[status] || status
        },

        getStatusClass(status) {
            const classMap = {
                draft: 'bg-gray-100 text-gray-800',
                pending_review: 'bg-yellow-100 text-yellow-800',
                approved: 'bg-green-100 text-green-800',
                finalised: 'bg-blue-100 text-blue-800'
            }
            return classMap[status] || 'bg-gray-100 text-gray-800'
        },

        async loadHeadteachersCount() {
            try {
                const response = await fetch('/api/headteachers');
                if (response.ok) {
                    this.headteachers = await response.json();
                }
            } catch (error) {
                console.error('Error loading headteachers count:', error);
            }
        }
    }
}
</script>

<style scoped>
.stagger-enter-active {
    transition: all 0.3s ease;
}

.stagger-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.stagger-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.stagger-move {
    transition: transform 0.3s ease;
}
</style>
