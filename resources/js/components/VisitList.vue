<template>
  <div class="space-y-6">
    <!-- Header -->
    <transition
      enter-active-class="transition-all duration-500 ease-out"
      enter-from-class="opacity-0 transform translateY(-20px)"
      enter-to-class="opacity-100 transform translateY(0)"
      appear
    >
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-xl font-semibold text-gray-900">Visit Records</h1>
          <p class="text-gray-600 mt-1">Manage and view all school visit records</p>
        </div>
        <div class="mt-4 sm:mt-0">
          <router-link
            to="/visits/create"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover-lift"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Visit
          </router-link>
        </div>
      </div>
    </transition>

    <!-- Filters -->
    <transition
      enter-active-class="transition-all duration-500 ease-out"
      enter-from-class="opacity-0 transform translateY(20px)"
      enter-to-class="opacity-100 transform translateY(0)"
      appear
    >
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-4 hover-lift">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input
              type="text"
              v-model="search"
              placeholder="Search by school or consultant..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select
              v-model="statusFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
            >
              <option value="">All Statuses</option>
              <option value="draft">Draft</option>
              <option value="pending_review">Pending Review</option>
              <option value="approved">Approved</option>
              <option value="finalized">Finalized</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
            <select
              v-model="schoolFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
            >
              <option value="">All Schools</option>
              <option v-for="school in schools" :key="school.id" :value="school.id">
                {{ school.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </transition>

    <!-- Visits List -->
    <transition
      enter-active-class="transition-all duration-600 ease-out"
      enter-from-class="opacity-0 transform translateY(30px)"
      enter-to-class="opacity-100 transform translateY(0)"
      appear
    >
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift">
        <div v-if="filteredVisits.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
          </svg>
          <p class="mt-2 text-sm text-gray-500">No visits found</p>
        </div>

        <div v-else class="divide-y divide-gray-200">
          <transition-group
            name="visit-list"
            tag="div"
            class="divide-y divide-gray-200"
          >
            <div
              v-for="(visit, index) in filteredVisits"
              :key="visit.id"
              class="p-6 hover:bg-gray-50 transition-all duration-300 cursor-pointer hover-lift"
              @click="$router.push(`/visits/${visit.id}`)"
              :style="{ animationDelay: `${index * 0.1}s` }"
            >
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="flex items-center space-x-3">
                    <h3 class="text-lg font-medium text-gray-900">{{ visit.school.name }}</h3>
                    <span
                      :class="getStatusClass(visit.status)"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    >
                      {{ formatStatus(visit.status) }}
                    </span>
                  </div>
                  <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      {{ formatDate(visit.visit_date) }}
                    </div>
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      {{ visit.consultant_name }}
                    </div>
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                      </svg>
                      <span class="text-xs text-gray-500">Headteacher: {{ visit.school.headteacher ? visit.school.headteacher.name : 'N/A' }}</span>
                    </div>
                  </div>
                  <div class="mt-2 text-sm text-gray-600 line-clamp-2">
                    {{ stripHtmlAndTruncate(visit.context) }}
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <button
                    @click.stop="downloadPdf(visit.id)"
                    class="p-2 text-gray-400 hover:text-blue-600 transition-all duration-300 hover-lift cursor-pointer"
                    title="Download PDF"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                  </button>
                  <button
                    @click.stop="copyShareLink(visit.share_token)"
                    class="p-2 text-gray-400 hover:text-green-600 transition-all duration-300 hover-lift cursor-pointer"
                    title="Copy Share Link"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                  </button>

                  <!-- Change Status Button -->
                  <button
                    @click.stop="openStatusModal(visit)"
                    class="p-2 text-gray-400 hover:text-blue-600 transition-all duration-300 hover-lift cursor-pointer"
                    title="Change Status"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </transition-group>
        </div>
      </div>
    </transition>

    <!-- Status Change Modal -->
    <div
      v-if="showStatusModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click="closeStatusModal"
    >
      <div
        class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4"
        @click.stop
      >
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Change Visit Status</h3>
            <button
              @click="closeStatusModal"
              class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div class="mb-4">
            <p class="text-sm text-gray-600 mb-2">Current Status:</p>
            <span
              :class="getStatusClass(selectedVisit.status)"
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            >
              {{ formatStatus(selectedVisit.status) }}
            </span>
          </div>

          <div class="space-y-2">
            <p class="text-sm text-gray-600 mb-3">Select New Status:</p>
            <button
              v-for="status in ['draft', 'pending_review', 'approved', 'finalized']"
              :key="status"
              @click="updateVisitStatus(selectedVisit.id, status)"
              :disabled="selectedVisit.status === status"
              class="w-full text-left p-3 rounded-lg border transition-all duration-200 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
              :class="selectedVisit.status === status ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <span
                    :class="getStatusClass(status)"
                    class="w-3 h-3 rounded-full mr-3"
                  ></span>
                  <span class="font-medium">{{ formatStatus(status) }}</span>
                </div>
                <svg
                  v-if="selectedVisit.status === status"
                  class="w-5 h-5 text-blue-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
  name: 'VisitList',
  data() {
    return {
      search: '',
      statusFilter: '',
      schoolFilter: '',
      showStatusModal: false,
      selectedVisit: null
    }
  },
  computed: {
    ...mapState(['visits', 'schools']),
    filteredVisits() {
      return this.visits.filter(visit => {
        const matchesSearch = !this.search ||
          (visit.school && visit.school.name && visit.school.name.toLowerCase().includes(this.search.toLowerCase())) ||
          (visit.consultant_name && visit.consultant_name.toLowerCase().includes(this.search.toLowerCase()));

        const matchesStatus = !this.statusFilter || visit.status === this.statusFilter;
        const matchesSchool = !this.schoolFilter || visit.school_id == this.schoolFilter;

        return matchesSearch && matchesStatus && matchesSchool;
      });
    }
  },
    async mounted() {
    await this.fetchVisits();
    await this.fetchSchools();
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
      if (!status) return 'Unknown';
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
    downloadPdf(visitId) {
      window.open(`/visits/${visitId}/pdf`, '_blank');
    },
    async copyShareLink(token) {
      const url = `${window.location.origin}/visits/share/${token}`;
      try {
        await navigator.clipboard.writeText(url);
        this.$toastr.success('Share link copied to clipboard!');
      } catch (err) {
        console.error('Failed to copy link:', err);
        this.$toastr.error('Failed to copy share link. Please try again.');
      }
    },
    stripHtmlAndTruncate(text) {
      if (!text) return '';
      let html = text;
      // Remove HTML tags
      html = html.replace(/<[^>]*>/g, '');
      // Truncate text to a reasonable length
      const maxLength = 150; // Adjust as needed
      if (html.length > maxLength) {
        return html.substring(0, maxLength) + '...';
      }
      return html;
         },
     openStatusModal(visit) {
       this.selectedVisit = visit;
       this.showStatusModal = true;
     },
     closeStatusModal() {
       this.showStatusModal = false;
       this.selectedVisit = null;
     },
     async updateVisitStatus(visitId, newStatus) {
       try {
         await this.$store.dispatch('updateVisitStatus', { visitId, newStatus });
         this.$toastr.success(`Visit status updated to ${this.formatStatus(newStatus)}!`);
         this.closeStatusModal();
       } catch (error) {
         console.error('Failed to update visit status:', error);
         this.$toastr.error('Failed to update visit status. Please try again.');
       }
     },

  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.visit-list-enter-active {
  transition: all 0.3s ease;
}

.visit-list-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.visit-list-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.visit-list-move {
  transition: transform 0.3s ease;
}
</style>
