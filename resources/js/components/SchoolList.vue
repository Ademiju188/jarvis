<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl font-semibold text-gray-900">Schools</h1>
        <p class="text-gray-600 mt-1">Manage registered schools</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <router-link
          to="/schools/create"
          class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 hover-lift"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Add School
        </router-link>
      </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift">
      <div v-if="schools.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <p class="mt-2 text-sm text-gray-500">No schools registered yet</p>
      </div>

      <div v-else class="divide-y divide-gray-200">
        <div
          v-for="school in schools"
          :key="school.id"
          class="p-6 hover:bg-gray-50 transition-all duration-300 hover-lift"
        >
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <h3 class="text-lg font-medium text-gray-900">{{ school.name }}</h3>
              <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  {{ school.headteacher_name }}
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                  {{ school.headteacher_email }}
                </div>
                <div v-if="school.phone" class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                  </svg>
                  {{ school.phone }}
                </div>
              </div>
              <div v-if="school.address" class="mt-2 text-sm text-gray-600">
                {{ school.address }}
              </div>
              <div class="mt-2 text-sm text-gray-500">
                {{ school.visits.length }} visit(s) recorded
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <button
                @click="copyAccessLink(school.access_token)"
                class="p-2 text-gray-400 hover:text-blue-600 transition-all duration-300 hover-lift"
                title="Copy Access Link"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'SchoolList',
  computed: {
    ...mapState(['schools'])
  },
  methods: {
    async copyAccessLink(token) {
      const url = `${window.location.origin}/schools/token/${token}`;
      try {
        await navigator.clipboard.writeText(url);
        this.$toastr.success('School access link copied to clipboard!');
      } catch (err) {
        console.error('Failed to copy link:', err);
        this.$toastr.error('Failed to copy access link. Please try again.');
      }
    }
  }
}
</script>
