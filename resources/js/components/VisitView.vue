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
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-4">
                        <button
                            @click="goBack"
                            class="flex cursor-pointer items-center space-x-2 px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition duration-150 ease-in-out"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Back</span>
                        </button>
                        <h2 class="text-2xl font-bold text-gray-900">Visit Details</h2>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="toggleEdit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            {{ isEditing ? 'Cancel Edit' : 'Edit Visit' }}
                        </button>
                        <button @click="showDeleteModal = true"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            Delete Visit
                        </button>
                    </div>
                </div>

                <!-- Edit Form Loading -->
                <div v-if="isEditing && !editFormReady" class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600">Preparing edit form...</p>
                </div>

                <!-- Edit Form -->
                <form v-if="isEditing && editFormReady" @submit.prevent="saveChanges" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Visit Date</label>
                            <input v-model="editForm.visit_date" type="date" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consultant Name</label>
                            <input v-model="editForm.consultant_name" type="text" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select v-model="editForm.status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="draft">Draft</option>
                            <option value="pending_review">Pending Review</option>
                            <option value="approved">Approved</option>
                            <option value="finalized">Finalized</option>
                        </select>
                    </div>

                    <div>
                        <label for="context" class="block text-sm font-medium text-gray-700">
                            Context
                        </label>
                        <div class="mt-1">
                            {{ console.log(editForm.context) }}
                            <RichTextEditor v-if="editFormReady && editForm.context !== undefined" v-model:value="editForm.context" :key="'context-' + visit.id + '-' + formKey"
                                placeholder="Describe the context of the visit..." />
                            <div v-if="validationErrors.context" class="text-red-600 text-sm mt-1">
                                {{ validationErrors.context[0] }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="activities_undertaken" class="block text-sm font-medium text-gray-700">
                            Activities Undertaken
                        </label>
                        <div class="mt-1">
                            <RichTextEditor v-if="editFormReady && editForm.activities_undertaken !== undefined" v-model:value="editForm.activities_undertaken"
                                :key="'activities-' + visit.id + '-' + formKey"
                                placeholder="Describe the activities undertaken during the visit..." />
                            <div v-if="validationErrors.activities_undertaken" class="text-red-600 text-sm mt-1">
                                {{ validationErrors.activities_undertaken[0] }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="progress" class="block text-sm font-medium text-gray-700">
                            Progress towards actions from last visit (if applicable)
                        </label>
                        <div class="mt-1">
                            <RichTextEditor v-if="editFormReady && editForm.progress !== undefined" v-model:value="editForm.progress" :key="'progress-' + visit.id + '-' + formKey"
                                placeholder="Describe progress made on actions from previous visit..." />
                            <div v-if="validationErrors.progress" class="text-red-600 text-sm mt-1">
                                {{ validationErrors.progress[0] }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="key_findings" class="block text-sm font-medium text-gray-700">
                            Key Findings
                        </label>
                        <div class="mt-1">
                            <RichTextEditor v-if="editFormReady && editForm.key_findings !== undefined" v-model:value="editForm.key_findings"
                                :key="'findings-' + visit.id + '-' + formKey"
                                placeholder="Document key findings from the visit..." />
                            <div v-if="validationErrors.key_findings" class="text-red-600 text-sm mt-1">
                                {{ validationErrors.key_findings[0] }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="recommendations" class="block text-sm font-medium text-gray-700">
                            Recommendations/Next Steps
                        </label>
                        <div class="mt-1">
                            <RichTextEditor v-if="editFormReady && editForm.recommendations !== undefined" v-model:value="editForm.recommendations"
                                :key="'recommendations-' + visit.id + '-' + formKey"
                                placeholder="Provide recommendations and next steps..." />
                            <div v-if="validationErrors.recommendations" class="text-red-600 text-sm mt-1">
                                {{ validationErrors.recommendations[0] }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Next Visit Date (Optional)</label>
                        <input v-model="editForm.next_visit_date" type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="cancelEdit"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button type="submit" :disabled="saving"
                            class="px-6 py-2 cursor-pointer bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150 ease-in-out">
                            {{ saving ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>

                <!-- View Mode -->
                <div v-else class="space-y-6">
                    <!-- Basic Information -->
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

                    <!-- Rich Content Fields -->
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

                    <!-- Headteacher Feedback -->
                    <div v-if="visit.headteacher_feedback" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">HeadTeacher Feedback</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ visit.headteacher_feedback }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Management Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 max-h-180 overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Status Management</h3>
                    <span :class="getStatusClass(visit.status)"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ formatStatus(visit.status) }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-3">Current Status:</p>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center">
                                <span :class="getStatusClass(visit.status)" class="w-3 h-3 rounded-full mr-3"></span>
                                <span class="font-medium">{{ formatStatus(visit.status) }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-3">Change Status:</p>
                        <div class="space-y-2">
                            <button v-for="status in ['draft', 'pending_review', 'approved', 'finalized']" :key="status"
                                @click="updateStatus(status)" :disabled="visit.status === status"
                                class="w-full text-left p-3 rounded-lg border transition-all duration-200 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                                :class="visit.status === status ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span :class="getStatusClass(status)" class="w-3 h-3 rounded-full mr-3"></span>
                                        <span class="font-medium">{{ formatStatus(status) }}</span>
                                    </div>
                                    <svg v-if="visit.status === status" class="w-5 h-5 text-blue-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </button>
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

        <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative">
                <h3 class="text-lg font-semibold mb-4 text-red-700">Delete Visit?</h3>
                <p class="mb-6 text-gray-700">Are you sure you want to delete this visit? This action cannot be undone.
                </p>
                <div class="flex justify-end space-x-3">
                    <button @click="showDeleteModal = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
                    <button @click="deleteVisit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RichTextEditor from './RichTextEditor.vue'

export default {
    name: 'VisitView',
    components: {
        RichTextEditor
    },
    data() {
        return {
            visit: null,
            loading: true,
            isEditing: false,
            saving: false,
            showDeleteModal: false,
            formKey: 0,
            editFormReady: false,
            editForm: {
                visit_date: '',
                consultant_name: '',
                status: '',
                context: '',
                activities_undertaken: '',
                progress: '',
                key_findings: '',
                recommendations: '',
                next_visit_date: ''
            },
            validationErrors: {}
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

        toggleEdit() {
            if (!this.isEditing) {
                this.editFormReady = false
                this.initializeEditForm();
                // Add a small delay to ensure data is properly set
                setTimeout(() => {
                    this.editFormReady = true
                    this.isEditing = true;
                }, 100)
            } else {
                this.isEditing = false;
                this.editFormReady = false;
            }
        },

        initializeEditForm() {
            this.formKey++
            this.editForm = {
                visit_date: this.formatDateForInput(this.visit.visit_date),
                consultant_name: this.visit.consultant_name,
                status: this.visit.status,
                context: this.visit.context || '',
                activities_undertaken: this.visit.activities_undertaken || '',
                progress: this.visit.progress || '',
                key_findings: this.visit.key_findings || '',
                recommendations: this.visit.recommendations || '',
                next_visit_date: this.visit.next_visit_date ? this.formatDateForInput(this.visit.next_visit_date) : ''
            }
        },

        formatDateForInput(date) {
            if (!date) return ''
            return new Date(date).toISOString().split('T')[0]
        },

        async saveChanges() {
            this.saving = true

            // Sanitize rich text fields
            const sanitize = (val) => {
                if (!val) return '';
                const cleaned = val.replace(/<p><br><\/p>/gi, '').replace(/\s+/g, '');
                return cleaned === '' ? '' : val;
            };
            this.editForm.context = sanitize(this.editForm.context);
            this.editForm.activities_undertaken = sanitize(this.editForm.activities_undertaken);
            this.editForm.progress = sanitize(this.editForm.progress);
            this.editForm.key_findings = sanitize(this.editForm.key_findings);
            this.editForm.recommendations = sanitize(this.editForm.recommendations);
            this.validationErrors = {};

            try {
                console.log(this.editForm);
                const response = await fetch(`/api/visits/${this.visit.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.editForm)
                })

                if (!response.ok) {
                    if (response.status === 401) {
                        this.$store.commit('SET_AUTHENTICATED', false);
                        this.$router.push('/login');
                        return;
                    }
                    if (response.status === 422) {
                        const data = await response.json();
                        this.validationErrors = data.errors || {};
                        this.$toastr.error('Please fix the validation errors.');
                        return;
                    }
                    throw new Error('Failed to update visit');
                }

                await this.loadVisit();
                this.isEditing = false;
                this.$toastr.success('Visit updated successfully!');
            } catch (error) {
                this.$toastr.error('Failed to update visit. Please try again.')
            } finally {
                this.saving = false
            }
        },

        cancelEdit() {
            this.isEditing = false
        },

        async generatePdf() {
            window.open(`/visits/${this.visit.id}/pdf`, '_blank')
        },

        copyShareLink() {
            const shareUrl = `${window.location.origin}/visits/share/${this.visit.share_token}`
            navigator.clipboard.writeText(shareUrl).then(() => {
                this.$toastr.success('Share link copied to clipboard!')
            }).catch(() => {
                this.$toastr.error('Failed to copy link')
            })
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

        async updateStatus(newStatus) {
            this.saving = true
            try {
                const response = await fetch(`/api/visits/${this.visit.id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: newStatus })
                })

                if (!response.ok) throw new Error('Failed to update status')

                await this.loadVisit()
                this.$toastr.success(`Status updated to ${this.formatStatus(newStatus)}!`)
            } catch (error) {
                this.$toastr.error('Failed to update status. Please try again.')
            } finally {
                this.saving = false
            }
        },

        async deleteVisit() {
            if (!this.visit) return;
            try {
                const response = await fetch(`/api/visits/${this.visit.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (response.ok) {
                    this.$toastr.success('Visit deleted successfully!');
                    this.$router.push('/visits');
                } else {
                    this.$toastr.error('Failed to delete visit.');
                }
            } catch (error) {
                this.$toastr.error('Failed to delete visit.');
            } finally {
                this.showDeleteModal = false;
            }
        },

        goBack() {
            this.$router.go(-1);
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
