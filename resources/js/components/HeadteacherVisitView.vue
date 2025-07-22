<template>
    <div class="max-w-7xl mx-auto p-6">
        <div v-if="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading visit details...</p>
        </div>

        <div v-else-if="visit" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Visit Details Card -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Visit Details</h2>
                </div>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Visit Date</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ formatDate(visit.visit_date) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Consultant Name</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ visit.consultant_name }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">School</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ visit.school?.name }}</p>
                    </div>
                    <div v-if="visit.context">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Context</h3>
                        <div class="mt-1 prose max-w-none" v-html="visit.context"></div>
                    </div>
                    <div v-if="visit.activities_undertaken">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Activities Undertaken</h3>
                        <div class="mt-1 prose max-w-none" v-html="visit.activities_undertaken"></div>
                    </div>
                    <div v-if="visit.progress">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Progress towards actions from last visit</h3>
                        <div class="mt-1 prose max-w-none" v-html="visit.progress"></div>
                    </div>
                    <div v-if="visit.key_findings">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Key Findings</h3>
                        <div class="mt-1 prose max-w-none" v-html="visit.key_findings"></div>
                    </div>
                    <div v-if="visit.recommendations">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Recommendations/Next Steps</h3>
                        <div class="mt-1 prose max-w-none" v-html="visit.recommendations"></div>
                    </div>
                    <div v-if="visit.next_visit_date">
                        <h3 class="text-sm font-medium text-gray-500">Next Visit Date</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ formatDate(visit.next_visit_date) }}</p>
                    </div>
                    <!-- HeadMaster Feedback -->
                    <div v-if="visit.headteacher_feedback"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">HeadMaster Feedback</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ visit.headteacher_feedback }}</p>
                        </div>
                    </div>

                    <!-- Submit Feedback Form -->
                    <div v-if="!visit.headteacher_feedback" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit Feedback</h3>
                        <form @submit.prevent="submitFeedback" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Your Feedback
                                </label>
                                <textarea v-model="feedback" required rows="4"
                                    placeholder="Please provide your feedback on this visit record..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" :disabled="submitting"
                                    class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 hover-lift">
                                    <span v-if="submitting" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Submitting...
                                    </span>
                                    <span v-else>Submit Feedback</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Status Management Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 max-h-100 overflow-y-auto">
                <div class="space-y-4">
                    <div>
                        <div class="bg-gray-50 p-3 rounded-lg">

                            <p class="text-sm text-gray-600 mb-3">Current Status: <span
                                    :class="getStatusClass(visit.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    {{ formatStatus(visit.status) }}
                                </span></p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Quick Actions</h4>
                        <div class="space-y-2">
                            <button @click="generatePdf"
                                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out cursor-pointer">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Generate PDF
                            </button>
                            <button @click="copyShareLink"
                                class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-150 ease-in-out cursor-pointer">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                    </path>
                                </svg>
                                Copy Share Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="text-center py-8">
            <p class="text-gray-600">Visit not found.</p>
        </div>
    </div>
</template>

<script>
import HeadteacherNav from './HeadteacherNav.vue'

export default {
    name: 'HeadteacherVisitView',
    components: { HeadteacherNav },
    data() {
        return {
            visit: null,
            loading: true,
            feedback: '',
            submitting: false
        }
    },
    async mounted() {
        await this.loadVisit()
    },
    methods: {
        async loadVisit() {
            try {
                const response = await fetch(`/api/visits/${this.$route.params.id}`)
                if (!response.ok) {
                    if (response.status === 401 || response.status === 422) {
                        this.$store.commit('SET_AUTHENTICATED', false);
                        this.$router.push('/login');
                        return;
                    }
                    throw new Error('Failed to load visit')
                }
                this.visit = await response.json()
            } catch (error) {
                this.$toastr.error('Failed to load visit details')
            } finally {
                this.loading = false
            }
        },
        formatDate(date) {
            if (!date) return 'Not set'
            return new Date(date).toLocaleDateString()
        },

        formatStatus(status) {
            const statusMap = {
                draft: 'Draft',
                pending_review: 'Pending Review',
                approved: 'Approved',
                finalized: 'Finalized'
            }
            return statusMap[status] || status
        },

        getStatusClass(status) {
            const classMap = {
                draft: 'bg-gray-100 text-gray-800',
                pending_review: 'bg-yellow-100 text-yellow-800',
                approved: 'bg-green-100 text-green-800',
                finalized: 'bg-blue-100 text-blue-800'
            }
            return classMap[status] || 'bg-gray-100 text-gray-800'
        },
        async submitFeedback() {
            this.submitting = true;
            try {
                const response = await fetch(`/api/visits/${this.$route.params.id}/feedback`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ headteacher_feedback: this.feedback })
                });
                if (response.ok) {
                    await this.loadVisit();
                    this.feedback = '';
                    this.$toastr.success('Feedback submitted successfully!');
                } else {
                    throw new Error('Failed to submit feedback');
                }
            } catch (error) {
                this.$toastr.error('Failed to submit feedback. Please try again.');
            } finally {
                this.submitting = false;
            }
        },
        async generatePdf() {
            if (!this.visit) return;
            const url = `/visits/${this.visit.id}/pdf`;
            window.open(url, '_blank');
        },
        async copyShareLink() {
            if (!this.visit || !this.visit.share_token) {
                this.$toastr.error('No share link available.');
                return;
            }
            const link = `${window.location.origin}/visits/share/${this.visit.share_token}`;
            try {
                await navigator.clipboard.writeText(link);
                this.$toastr.success('Share link copied to clipboard!');
            } catch (e) {
                this.$toastr.error('Failed to copy link.');
            }
        }
    }
}
</script>

<style scoped>
.prose {
    line-height: 1.6;
}

.prose p {
    margin-bottom: 1rem;
}

.prose ul,
.prose ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}
</style>
