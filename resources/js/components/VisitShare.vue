<template>
  <div v-if="loading" class="flex items-center justify-center min-h-screen">
    <div class="text-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
      <p class="mt-4 text-gray-600">Loading visit details...</p>
    </div>
  </div>

  <div v-else-if="visit" class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-semibold text-gray-900">{{ visit.school.name }}</h1>
          <p class="text-gray-600 mt-1">Visit Record - {{ formatDate(visit.visit_date) }}</p>
        </div>
        <div class="flex items-center space-x-3">
          <span
            :class="getStatusClass(visit.status)"
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
          >
            {{ formatStatus(visit.status) }}
          </span>
        </div>
      </div>
    </div>

    <!-- Visit Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Context -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Context</h3>
          <div class="text-gray-700" v-html="visit.context"></div>
        </div>

        <!-- Activities -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Activities Undertaken</h3>
          <div class="text-gray-700" v-html="visit.activities_undertaken"></div>
        </div>

        <!-- Progress -->
        <div v-if="visit.progress" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress towards actions from last visit</h3>
          <div class="text-gray-700" v-html="visit.progress"></div>
        </div>

        <!-- Key Findings -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Key Findings</h3>
          <div class="text-gray-700" v-html="visit.key_findings"></div>
        </div>

        <!-- Recommendations -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Recommendations & Next Steps</h3>
          <div class="text-gray-700" v-html="visit.recommendations"></div>
        </div>

        <!-- Headmaster Feedback -->
        <div v-if="visit.headteacher_feedback" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Headmaster Feedback</h3>
          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="text-gray-700" v-html="visit.headteacher_feedback"></div>
          </div>
        </div>

        <!-- Submit Feedback Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit Feedback</h3>
          <form @submit.prevent="submitFeedback" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Your Feedback
              </label>
              <textarea
                v-model="feedback"
                required
                rows="4"
                placeholder="Please provide your feedback on this visit record..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
              ></textarea>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="submitting"
                class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 hover-lift"
              >
                <span v-if="submitting" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Submitting...
                </span>
                <span v-else>Submit Feedback</span>
              </button>
            </div>
          </form>
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
              <dt class="text-sm font-medium text-gray-500">Headmaster</dt>
              <dd class="text-sm text-gray-900">{{ visit.school.headmaster ? visit.school.headmaster.name : 'N/A' }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Email</dt>
              <dd class="text-sm text-gray-900">{{ visit.school.headmaster ? visit.school.headmaster.email : 'N/A' }}</dd>
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
              @click="downloadPdf"
              class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover-lift"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Download PDF
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VisitShare',
  data() {
    return {
      visit: null,
      feedback: '',
      submitting: false,
      loading: true
    }
  },
  async mounted() {
    await this.fetchVisit();
  },
  methods: {
    async fetchVisit() {
      try {
        this.loading = true;

        // First check if user should be redirected (before fetching data)
        const shouldRedirect = await this.checkAndRedirect();

        // Only fetch visit data if user should see public view
        if (!shouldRedirect) {
          const response = await fetch(`/api/visits/share/${this.$route.params.token}`);
          this.visit = await response.json();
        }
      } catch (error) {
        console.error('Error fetching visit:', error);
      } finally {
        this.loading = false;
      }
    },

    async checkAndRedirect() {
      try {
        // First check if user is a headmaster
        const userResponse = await fetch('/headmaster/user');
        if (userResponse.ok) {
          const user = await userResponse.json();

          // For headmasters, we need to check if this visit belongs to their school
          // So we need to fetch the visit data temporarily
          const visitResponse = await fetch(`/api/visits/share/${this.$route.params.token}`);
          if (visitResponse.ok) {
            const visit = await visitResponse.json();
            if (user.school_id === visit.school_id) {
              this.$router.push(`/visits/${visit.id}`);
              return true; // Should redirect
            }
          }
        }

        // If not headmaster, check if user is admin by trying to fetch visits
        const visitsResponse = await fetch('/api/visits');
        if (visitsResponse.ok) {
          // User is admin, get visit ID and redirect
          const visitResponse = await fetch(`/api/visits/share/${this.$route.params.token}`);
          if (visitResponse.ok) {
            const visit = await visitResponse.json();
            this.$router.push(`/visits/${visit.id}`);
            return true; // Should redirect
          }
        }

        return false; // Should not redirect, show public view
      } catch (error) {
        // User is not authenticated, continue with public share view
        console.log('User not authenticated, showing public share view');
        return false; // Should not redirect, show public view
      }
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
    async submitFeedback() {
      this.submitting = true;
      try {
        const response = await fetch(`/api/visits/share/${this.$route.params.token}/feedback`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            headmaster_feedback: this.feedback
          })
        });

        if (response.ok) {
          await this.fetchVisit();
          this.feedback = '';
          this.$toastr.success('Feedback submitted successfully!');
        } else {
          throw new Error('Failed to submit feedback');
        }
      } catch (error) {
        console.error('Error submitting feedback:', error);
        this.$toastr.error('Failed to submit feedback. Please try again.');
      } finally {
        this.submitting = false;
      }
    }
  }
}
</script>
