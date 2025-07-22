<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Welcome, {{ user?.name }}</h2>
          <p class="text-gray-600">{{ school?.name }}</p>
        </div>
        <!-- <button
          @click="logout"
          class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out"
        >
          Logout
        </button> -->
      </div>

      <!-- School Information -->
      <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">School Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-blue-700"><strong>School:</strong> {{ school?.name }}</p>
            <p class="text-sm text-blue-700"><strong>Headmaster:</strong> {{ school?.headmaster_name }}</p>
          </div>
          <div>
            <p class="text-sm text-blue-700"><strong>Email:</strong> {{ school?.headmaster_email }}</p>
            <p class="text-sm text-blue-700"><strong>Phone:</strong> {{ school?.phone || 'Not provided' }}</p>
          </div>
        </div>
        <div v-if="school?.address" class="mt-2">
          <p class="text-sm text-blue-700"><strong>Address:</strong> {{ school.address }}</p>
        </div>
      </div>

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Total Visits</p>
              <p class="text-2xl font-semibold text-gray-900">{{ visits.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Pending Review</p>
              <p class="text-2xl font-semibold text-gray-900">{{ pendingVisits.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Finalized</p>
              <p class="text-2xl font-semibold text-gray-900">{{ finalizedVisits.length }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Visits -->
      <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Recent Visit Records</h3>
        </div>

        <div v-if="loading" class="p-6 text-center">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
          <p class="mt-2 text-gray-600">Loading visits...</p>
        </div>

        <div v-else-if="error" class="p-6 text-center">
          <p class="text-red-600">{{ error }}</p>
        </div>

        <div v-else-if="visits.length === 0" class="p-6 text-center">
          <p class="text-gray-500">No visit records found for your school.</p>
        </div>

        <div v-else class="divide-y divide-gray-200">
          <div
            v-for="visit in visits"
            :key="visit.id"
            class="px-6 py-4 hover:bg-gray-50 cursor-pointer transition duration-150 ease-in-out"
            @click="viewVisit(visit)"
          >
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <div class="flex items-center space-x-3">
                  <h4 class="text-sm font-medium text-gray-900">
                    Visit on {{ formatDate(visit.visit_date) }}
                  </h4>
                  <span :class="getStatusClass(visit.status)" class="inline-flex px-2 py-1 text-xs font-medium rounded-full">
                    {{ formatStatus(visit.status) }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mt-1">
                  Consultant: {{ visit.consultant_name }}
                </p>
                <p class="text-sm text-gray-600">
                  Context: {{ visit.context ? visit.context.substring(0, 100) + '...' : 'No context provided' }}
                </p>
              </div>
              <div class="flex space-x-2">
                <button
                  @click.stop="viewVisit(visit)"
                  class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                >
                  View Details
                </button>
                <button
                  @click.stop="generatePdf(visit)"
                  class="text-green-600 hover:text-green-800 text-sm font-medium"
                >
                  Download PDF
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HeadmasterDashboard',
  data() {
    return {
      loading: true,
      error: null,
      user: null,
      school: null,
      visits: []
    }
  },
  computed: {
    pendingVisits() {
      return this.visits.filter(v => v.status === 'pending_review')
    },
    finalizedVisits() {
      return this.visits.filter(v => v.status === 'finalized')
    }
  },
  async mounted() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      try {
        this.loading = true
        this.error = null

        // Load user and school data
        const userResponse = await fetch('/headmaster/user')
        if (!userResponse.ok) {
          if (userResponse.status === 401 || userResponse.status === 422) {
            this.$store.commit('SET_AUTHENTICATED', false);
            this.$router.push('/login');
            return;
          }
          throw new Error('Failed to load user data')
        }
        this.user = await userResponse.json()

        const schoolResponse = await fetch('/headmaster/school')
        if (!schoolResponse.ok) {
          if (schoolResponse.status === 401 || schoolResponse.status === 422) {
            this.$store.commit('SET_AUTHENTICATED', false);
            this.$router.push('/login');
            return;
          }
          throw new Error('Failed to load school data')
        }
        this.school = await schoolResponse.json()

        // Load visits for this school only
        const visitsResponse = await fetch('/headmaster/visits')
        if (!visitsResponse.ok) {
          if (visitsResponse.status === 401 || visitsResponse.status === 422) {
            this.$store.commit('SET_AUTHENTICATED', false);
            this.$router.push('/login');
            return;
          }
          throw new Error('Failed to load visits')
        }
        this.visits = await visitsResponse.json()
      } catch (error) {
        console.error('Error loading data:', error)
        this.error = 'Failed to load data. Please try again.'
      } finally {
        this.loading = false
      }
    },

    viewVisit(visit) {
      window.open(`/visits/share/${visit.share_token}`, '_blank')
    },

    generatePdf(visit) {
      window.open(`/visits/${visit.id}/pdf`, '_blank')
    },

    formatDate(date) {
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

    async logout() {
      // Call the global logout method from parent App component
      this.$parent.logout()
    }
  }
}
</script>
