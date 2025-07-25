<template>
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                {{ isEdit ? 'Edit Visit Record' : 'Create New Visit Record' }}
            </h2>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <FormField v-model="form.visit_date" label="Visit Date" id="visit_date" type="date" :required="true"
                        :error="validationErrors.visit_date" />
                    <FormField v-model="form.consultant_name" label="Consultant Name" id="consultant_name"
                        placeholder="Enter consultant name" :required="true"
                        :error="validationErrors.consultant_name" />
                </div>

                <!-- School Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">School <span
                            class="text-red-500">*</span></label>
                    <select v-model="form.school_id" required :class="[
                        'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200',
                        validationErrors.school_id ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300'
                    ]">
                        <option value="">Select a school</option>
                        <option v-for="school in schools" :key="school.id" :value="school.id">
                            {{ school.name }}
                        </option>
                    </select>
                    <p v-if="validationErrors.school_id" class="mt-1 text-sm text-red-600">{{
                        validationErrors.school_id[0] }}</p>
                </div>

                <!-- Rich Text Fields -->
                <div>
                    <label for="context" class="block text-sm font-medium text-gray-700">
                        Context <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <RichTextEditor v-model:value="form.context"
                            :key="'context-' + (visitId || 'new') + '-' + isEdit"
                            placeholder="Describe the context and purpose of this visit..." />
                        <div v-if="validationErrors.context" class="text-red-600 text-sm mt-1">
                            {{ validationErrors.context[0] }}
                        </div>
                    </div>
                </div>

                <div>
                    <label for="activities_undertaken" class="block text-sm font-medium text-gray-700">
                        Activities Undertaken <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <RichTextEditor v-model:value="form.activities_undertaken"
                            :key="'activities-' + (visitId || 'new') + '-' + isEdit"
                            placeholder="Describe the activities undertaken..." />
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
                        <RichTextEditor v-model:value="form.progress"
                            :key="'progress-' + (visitId || 'new') + '-' + isEdit"
                            placeholder="Describe the progress..." />
                        <div v-if="validationErrors.progress" class="text-red-600 text-sm mt-1">
                            {{ validationErrors.progress[0] }}
                        </div>
                    </div>
                </div>

                <div>
                    <label for="key_findings" class="block text-sm font-medium text-gray-700">
                        Key Findings <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <RichTextEditor v-model:value="form.key_findings"
                            :key="'findings-' + (visitId || 'new') + '-' + isEdit"
                            placeholder="Describe the key findings..." />
                        <div v-if="validationErrors.key_findings" class="text-red-600 text-sm mt-1">
                            {{ validationErrors.key_findings[0] }}
                        </div>
                    </div>
                </div>

                <div>
                    <label for="recommendations" class="block text-sm font-medium text-gray-700">
                        Recommendations/Next Steps <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <RichTextEditor v-model:value="form.recommendations"
                            :key="'recommendations-' + (visitId || 'new') + '-' + isEdit"
                            placeholder="Describe the recommendations..." />
                        <div v-if="validationErrors.recommendations" class="text-red-600 text-sm mt-1">
                            {{ validationErrors.recommendations[0] }}
                        </div>
                    </div>
                </div>

                <!-- Next Visit Date -->
                <FormField v-model="form.next_visit_date" label="Next Visit Date (Optional)" id="next_visit_date"
                    type="date" :error="validationErrors.next_visit_date" />

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="button" @click="$router.push('/visits')"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" :disabled="loading"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150 ease-in-out cursor-pointer">
                        {{ loading ? 'Saving...' : (isEdit ? 'Update Visit' : 'Create Visit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import RichTextEditor from './RichTextEditor.vue'
import FormField from './FormField.vue'

export default {
    name: 'VisitForm',
    components: {
        RichTextEditor,
        FormField
    },
    data() {
        return {
            loading: false,
            isEdit: false,
            visitId: null,
            form: {
                visit_date: '',
                consultant_name: '',
                school_id: '',
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
    computed: {
        ...mapState(['schools'])
    },
    async mounted() {
        await this.fetchSchools()

        // Check if we're editing
        if (this.$route.params.id) {
            this.isEdit = true
            this.visitId = this.$route.params.id
            await this.loadVisit()
        }
    },
    methods: {
        ...mapActions(['fetchSchools', 'createVisit', 'updateVisit']),

        async refreshCsrfToken() {
            try {
                const response = await fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update the meta tag with the new token
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.setAttribute('content', data.token);
                    }
                }
            } catch (error) {
                console.error('Failed to refresh CSRF token:', error);
            }
        },

        async loadVisit() {
            try {
                const response = await fetch(`/api/visits/${this.visitId}`)
                if (!response.ok) throw new Error('Failed to load visit')

                const visit = await response.json()
                Object.assign(this.form, {
                    visit_date: visit.visit_date,
                    consultant_name: visit.consultant_name,
                    school_id: visit.school_id,
                    context: visit.context || '',
                    activities_undertaken: visit.activities_undertaken || '',
                    progress: visit.progress || '',
                    key_findings: visit.key_findings || '',
                    recommendations: visit.recommendations || '',
                    next_visit_date: visit.next_visit_date || ''
                })
            } catch (error) {
                this.$toastr.error('Failed to load visit details')
            }
        },

        async submitForm() {
            this.loading = true
            this.validationErrors = {}

            // Ensure rich text fields are properly set (empty strings for empty content)
            this.form.context = this.form.context || '';
            this.form.activities_undertaken = this.form.activities_undertaken || '';
            this.form.progress = this.form.progress || '';
            this.form.key_findings = this.form.key_findings || '';
            this.form.recommendations = this.form.recommendations || '';

            try {
                // First, refresh the CSRF token
                await this.refreshCsrfToken();

                let response;
                if (this.isEdit) {
                    response = await fetch(`/api/visits/${this.visitId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.form)
                    });
                } else {
                    response = await fetch('/api/visits', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.form)
                    });
                }

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        this.validationErrors = data.errors || {};
                        this.$toastr.error('Please fix the validation errors.');
                        return;
                    }
                    throw new Error(data.message || 'Failed to save visit');
                }

                if (this.isEdit) {
                    this.$toastr.success('Visit updated successfully!');
                } else {
                    this.$toastr.success('Visit created successfully!');
                }
                this.$router.push('/visits');
            } catch (error) {
                this.$toastr.error(error.message || 'Failed to save visit. Please try again.');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
