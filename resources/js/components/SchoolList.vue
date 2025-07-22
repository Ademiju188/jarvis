<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Schools</h2>
          <p class="text-gray-600">Manage registered schools</p>
        </div>

        <!-- Comment out Add School button for headmaster restriction -->
        <!-- <router-link
          to="/schools/create"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out"
        >
          Add School
        </router-link> -->
      </div>

      <!-- Search and Filter -->
      <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search schools..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
        <p class="mt-2 text-gray-600">Loading schools...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredSchools.length === 0" class="text-center py-8">
        <p class="text-gray-500">No schools found.</p>
      </div>

      <!-- Schools Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="school in filteredSchools"
          :key="school.id"
          class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-150 ease-in-out cursor-pointer"
          @click="previewSchool(school)"
        >
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ school.name }}</h3>
            <div class="flex space-x-2">
              <button
                @click="copyAccessLink(school)"
                class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                title="Copy access link"
              >
                Copy Link
              </button>
              <!-- Comment out Edit button for headmaster restriction -->
              <!-- <router-link
                :to="`/schools/${school.id}/edit`"
                class="text-green-600 hover:text-green-800 text-sm font-medium"
              >
                Edit
              </router-link> -->
            </div>
          </div>

          <div class="space-y-2 text-sm text-gray-600">
            <p><strong>Headmaster:</strong> {{ school.headmaster ? school.headmaster.name : 'N/A' }}</p>
            <p><strong>Email:</strong> {{ school.headmaster ? school.headmaster.email : 'N/A' }}</p>
            <p v-if="school.phone"><strong>Phone:</strong> {{ school.phone }}</p>
            <p v-if="school.address"><strong>Address:</strong> {{ school.address }}</p>
            <p><strong>Visits:</strong> {{ school.visits_count || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selectedSchool" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full relative">
        <button @click="selectedSchool = null" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        <h2 class="text-2xl font-bold mb-4">{{ selectedSchool.name }}</h2>
        <div class="space-y-2 text-gray-700">
          <p><strong>Headmaster:</strong> {{ selectedSchool.headmaster ? selectedSchool.headmaster.name : 'N/A' }}</p>
          <p><strong>Email:</strong> {{ selectedSchool.headmaster ? selectedSchool.headmaster.email : 'N/A' }}</p>
          <p v-if="selectedSchool.phone"><strong>Phone:</strong> {{ selectedSchool.phone }}</p>
          <p v-if="selectedSchool.address"><strong>Address:</strong> {{ selectedSchool.address }}</p>
          <p><strong>Visits:</strong> {{ selectedSchool.visits_count || 0 }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'SchoolList',
  data() {
    return {
      searchQuery: '',
      loading: true,
      selectedSchool: null,
      showDeleteInviteModal: false
    }
  },
  computed: {
    ...mapState(['schools']),
    filteredSchools() {
      if (!this.searchQuery) return this.schools

      const query = this.searchQuery.toLowerCase()
      return this.schools.filter(school =>
        school.name.toLowerCase().includes(query) ||
        school.headteacher_name.toLowerCase().includes(query) ||
        school.headteacher_email.toLowerCase().includes(query)
      )
    }
  },
  async mounted() {
    await this.loadSchools()
  },
  methods: {
    async loadSchools() {
      this.loading = true
      try {
        await this.$store.dispatch('fetchSchools')
      } catch (error) {
        this.$toastr.error('Failed to load schools')
      } finally {
        this.loading = false
      }
    },
    copyAccessLink(school) {
      const link = `${window.location.origin}/schools/token/${school.access_token}`
      navigator.clipboard.writeText(link).then(() => {
        this.$toastr.success('Access link copied to clipboard!')
      }).catch(() => {
        this.$toastr.error('Failed to copy link')
      })
    },
    previewSchool(school) {
      this.selectedSchool = school;
    },
    async deleteInvite() {
      if (!this.selectedSchool) return;
      try {
        const response = await fetch(`/api/schools/${this.selectedSchool.id}/invite`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });
        if (response.ok) {
          this.$toastr.success('Invite deleted successfully!');
          // Update the school in the list
          this.selectedSchool.access_token = null;
          const idx = this.schools.findIndex(s => s.id === this.selectedSchool.id);
          if (idx !== -1) this.schools[idx].access_token = null;
          this.showDeleteInviteModal = false;
        } else {
          this.$toastr.error('Failed to delete invite.');
        }
      } catch (error) {
        this.$toastr.error('Failed to delete invite.');
      }
    }
  }
}
</script>
