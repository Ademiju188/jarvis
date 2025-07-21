<template>
  <div v-if="visit" class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-xl font-semibold text-gray-900">{{ visit.school.name }}</h1>
          <p class="text-gray-600 mt-1">Visit Record - {{ formatDate(visit.visit_date) }}</p>
        </div>
        <div class="flex items-center space-x-3 mt-4 sm:mt-0">
          <span
            :class="getStatusClass(visit.status)"
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
          >
            {{ formatStatus(visit.status) }}
          </span>
          <button
            @click="downloadPdf"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover-lift"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download PDF
          </button>
          <button
            @click="editMode = !editMode"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 hover-lift"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            {{ editMode ? 'Cancel Edit' : 'Edit Visit' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Form -->
    <transition
      enter-active-class="transition-all duration-500 ease-out"
      enter-from-class="opacity-0 transform translateY(-20px)"
      enter-to-class="opacity-100 transform translateY(0)"
      leave-active-class="transition-all duration-300 ease-in"
      leave-from-class="opacity-100 transform translateY(0)"
      leave-to-class="opacity-0 transform translateY(-20px)"
    >
      <div v-if="editMode" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Edit Visit Record</h2>
        <form @submit.prevent="saveChanges" class="space-y-6">
          <!-- Status Update -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select
              v-model="editForm.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
            >
              <option value="draft">Draft</option>
              <option value="pending_review">Pending Review</option>
              <option value="approved">Approved</option>
              <option value="finalized">Finalized</option>
            </select>
          </div>

          <!-- Visit Date and Consultant -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Visit Date</label>
              <input
                type="date"
                v-model="editForm.visit_date"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Consultant Name</label>
              <input
                type="text"
                v-model="editForm.consultant_name"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
              >
            </div>
          </div>

          <!-- Context -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Context</label>
            <textarea
              v-model="editForm.context"
              required
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
            ></textarea>
          </div>

          <!-- Activities Undertaken -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Activities Undertaken</label>
            <textarea
              v-model="editForm.activities_undertaken"
              required
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
            ></textarea>
          </div>

          <!-- Key Findings -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Key Findings</label>
            <textarea
              v-model="editForm.key_findings"
              required
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
            ></textarea>
          </div>

          <!-- Recommendations -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Recommendations & Next Steps</label>
            <textarea
              v-model="editForm.recommendations"
              required
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
            ></textarea>
          </div>

          <!-- Next Visit Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Next Visit Date</label>
            <input
              type="date"
              v-model="editForm.next_visit_date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
            >
          </div>

          <!-- Form Actions -->
          <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <button
              type="button"
              @click="cancelEdit"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover-lift"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 hover-lift"
            >
              <span v-if="saving" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
              </span>
              <span v-else>Save Changes</span>
            </button>
          </div>
        </form>
      </div>
    </transition>

    <!-- Visit Details (Read-only when not editing) -->
    <div v-if="!editMode" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Context -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Context</h3>
          <p class="text-gray-700 whitespace-pre-wrap">{{ visit.context }}</p>
        </div>

        <!-- Activities -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Activities Undertaken</h3>
          <p class="text-gray-700 whitespace-pre-wrap">{{ visit.activities_undertaken }}</p>
        </div>

        <!-- Key Findings -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Key Findings</h3>
          <p class="text-gray-700 whitespace-pre-wrap">{{ visit.key_findings }}</p>
        </div>

        <!-- Recommendations -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Recommendations & Next Steps</h3>
          <p class="text-gray-700 whitespace-pre-wrap">{{ visit.recommendations }}</p>
        </div>

        <!-- Headteacher Feedback -->
        <div v-if="visit.headteacher_feedback" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Headteacher Feedback</h3>
          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <p class="text-gray-700 whitespace-pre-wrap">{{ visit.headteacher_feedback }}</p>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Visit Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Visit Information</h3>
          <dl class="space-y-3">
            <div>
              <dt class="text-sm font-medium text-gray-500">Visit Date</dt>
              <dd class="text-sm text-gray-900">{{ formatDate(visit.visit_date) }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Consultant</dt>
              <dd class="text-sm text-gray-900">{{ visit.consultant_name }}</dd>
            </div>
            <div v-if="visit.next_visit_date">
              <dt class="text-sm font-medium text-gray-500">Next Visit</dt>
              <dd class="text-sm text-gray-900">{{ formatDate(visit.next_visit_date) }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Status</dt>
              <dd>
                <span
                  :class="getStatusClass(visit.status)"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                >
                  {{ formatStatus(visit.status) }}
                </span>
              </dd>
            </div>
          </dl>
        </div>

        <!-- School Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">School Information</h3>
          <dl class="space-y-3">
            <div>
              <dt class="text-sm font-medium text-gray-500">School Name</dt>
              <dd class="text-sm text-gray-900">{{ visit.school.name }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Headteacher</dt>
              <dd class="text-sm text-gray-900">{{ visit.school.headteacher_name }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Email</dt>
              <dd class="text-sm text-gray-900">{{ visit.school.headteacher_email }}</dd>
            </div>
            <div v-if="visit.school.phone">
              <dt class="text-sm font-medium text-gray-500">Phone</dt>
              <dd class="text-sm text-gray-900">{{ visit.school.phone }}</dd>
            </div>
            <div v-if="visit.school.address">
              <dt class="text-sm font-medium text-gray-500">Address</dt>
              <dd class="text-sm text-gray-900">{{ visit.school.address }}</dd>
            </div>
          </dl>
        </div>

        <!-- Actions -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
          <div class="space-y-3">
            <button
              @click="copyShareLink"
              class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 hover-lift"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
              </svg>
              Copy Share Link
            </button>
            <router-link
              to="/visits"
              class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all duration-300 hover-lift"
            >
              Back to Visits
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VisitView',
  data() {
    return {
      visit: null,
      editMode: false,
      saving: false,
      editForm: {
        status: '',
        visit_date: '',
        consultant_name: '',
        context: '',
        activities_undertaken: '',
        key_findings: '',
        recommendations: '',
        next_visit_date: ''
      }
    }
  },
  async mounted() {
    await this.fetchVisit();
  },
  methods: {
    async fetchVisit() {
      try {
        const response = await fetch(`/api/visits/${this.$route.params.id}`);
        this.visit = await response.json();
        this.initializeEditForm();
      } catch (error) {
        console.error('Error fetching visit:', error);
      }
    },
    initializeEditForm() {
      if (this.visit) {
        this.editForm = {
          status: this.visit.status,
          visit_date: this.formatDateForInput(this.visit.visit_date),
          consultant_name: this.visit.consultant_name,
          context: this.visit.context,
          activities_undertaken: this.visit.activities_undertaken,
          key_findings: this.visit.key_findings,
          recommendations: this.visit.recommendations,
          next_visit_date: this.visit.next_visit_date ? this.formatDateForInput(this.visit.next_visit_date) : ''
        };
      }
    },
    formatDateForInput(date) {
      if (!date) return '';
      const d = new Date(date);
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    },
    async saveChanges() {
      this.saving = true;
      try {
        const response = await fetch(`/api/visits/${this.visit.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify(this.editForm)
        });

        if (response.ok) {
          this.visit = await response.json();
          this.editMode = false;
          // Update the visit in the store
          this.$store.commit('UPDATE_VISIT', this.visit);
          this.$toastr.success('Visit updated successfully!');
        } else {
          throw new Error('Failed to update visit');
        }
      } catch (error) {
        console.error('Error updating visit:', error);
        this.$toastr.error('Failed to update visit. Please try again.');
      } finally {
        this.saving = false;
      }
    },
    cancelEdit() {
      this.editMode = false;
      this.initializeEditForm();
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-GB', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    formatStatus(status) {
      return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
    },
    getStatusClass(status) {
      const classes = {
        draft: 'bg-gray-100 text-gray-800',
        pending_review: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        finalized: 'bg-blue-100 text-blue-800'
      };
      return classes[status] || classes.draft;
    },
    downloadPdf() {
      window.open(`/api/visits/${this.visit.id}/pdf`, '_blank');
    },
    async copyShareLink() {
      const url = `${window.location.origin}/visits/share/${this.visit.share_token}`;
      try {
        await navigator.clipboard.writeText(url);
        this.$toastr.success('Share link copied to clipboard!');
      } catch (err) {
        console.error('Failed to copy link:', err);
        this.$toastr.error('Failed to copy share link. Please try again.');
      }
    }
  }
}
</script>
