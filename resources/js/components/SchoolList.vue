<template>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Schools</h1>
                <p class="text-gray-600 mt-1">Manage registered schools</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <router-link to="/schools/create"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 hover-lift cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add School
                </router-link>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift">
            <div v-if="schools.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">No schools registered yet</p>
            </div>

            <div v-else class="divide-y divide-gray-200">
                <div v-for="school in schools" :key="school.id"
                    class="p-6 hover:bg-gray-50 transition-all duration-300 hover-lift">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ school.name }}</h3>
                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    {{ school.headteacher_name }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ school.headteacher_email }}
                                </div>
                                <div v-if="school.phone" class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    {{ school.phone }}
                                </div>
                            </div>
                            <div v-if="school.address" class="mt-2 text-sm text-gray-600">
                                {{ school.address }}
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                {{ school.visits_count || school.visits.length }} visit(s) recorded
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="previewSchool(school)"
                                class="p-2 text-gray-400 hover:text-blue-600 transition-all duration-300 hover-lift cursor-pointer"
                                title="View Details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                            <router-link :to="`/schools/${school.id}/edit`"
                                class="p-2 text-gray-400 hover:text-green-600 transition-all duration-300 hover-lift cursor-pointer"
                                title="Edit School">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </router-link>
                            <button @click="copyAccessLink(school.access_token)"
                                class="p-2 text-gray-400 hover:text-blue-600 transition-all duration-300 hover-lift cursor-pointer"
                                title="Copy Access Link">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Preview Modal -->
        <div v-if="showPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="closePreviewModal">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">School Details</h3>
                        <button @click="closePreviewModal" class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div v-if="selectedSchool" class="space-y-4">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">{{ selectedSchool.name }}</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Headteacher</p>
                                <p class="text-sm text-gray-900">{{ selectedSchool.headteacher.name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-sm text-gray-900">{{ selectedSchool.headteacher.email }}</p>
                            </div>
                            <div v-if="selectedSchool.phone">
                                <p class="text-sm font-medium text-gray-500">Phone</p>
                                <p class="text-sm text-gray-900">{{ selectedSchool.phone }}</p>
                            </div>
                            <div v-if="selectedSchool.address">
                                <p class="text-sm font-medium text-gray-500">Address</p>
                                <p class="text-sm text-gray-900">{{ selectedSchool.address }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Visits</p>
                            <p class="text-sm text-gray-900">{{ selectedSchool.visits_count || selectedSchool.visits.length }} visit(s) recorded</p>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <router-link :to="`/schools/${selectedSchool.id}/edit`"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out cursor-pointer">
                                Edit School
                            </router-link>
                            <button @click="deleteSchool(selectedSchool)"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out cursor-pointer">
                                Delete School
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
    name: 'SchoolList',
    data() {
        return {
            showPreviewModal: false,
            selectedSchool: null
        }
    },
    computed: {
        ...mapState(['schools'])
    },
    async mounted() {
        await this.fetchSchools();
    },
    methods: {
        ...mapActions(['fetchSchools']),
        previewSchool(school) {
            this.selectedSchool = school;
            this.showPreviewModal = true;
        },
        closePreviewModal() {
            this.showPreviewModal = false;
            this.selectedSchool = null;
        },
        async copyAccessLink(token) {
            const url = `${window.location.origin}/schools/token/${token}`;
            try {
                await navigator.clipboard.writeText(url);
                this.$toastr.success('School access link copied to clipboard!');
            } catch (err) {
                console.error('Failed to copy link:', err);
                this.$toastr.error('Failed to copy access link. Please try again.');
            }
        },
        async deleteSchool(school) {
            if (!confirm(`Are you sure you want to delete ${school.name}? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`/api/schools/${school.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    this.$store.dispatch('fetchSchools');
                    this.$toastr.success('School deleted successfully!');
                    this.closePreviewModal();
                } else {
                    const data = await response.json();
                    this.$toastr.error(data.message || 'Failed to delete school');
                }
            } catch (error) {
                this.$toastr.error('Failed to delete school. Please try again.');
            }
        }
    }
}
</script>
