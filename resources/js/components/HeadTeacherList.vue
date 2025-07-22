<template>
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift">
    <div class="p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">HeadTeacher Management</h3>
          <p class="text-sm text-gray-500 mt-1">Manage all registered headteachers and their schools</p>
        </div>
        <router-link
          to="/schools/create"
          class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-all duration-300 hover-lift"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Add New HeadTeacher
        </router-link>
      </div>

      <!-- Search and Filter -->
      <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search headteachers by name or school..."
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
            >
          </div>
          <div class="flex gap-2">
            <select
              v-model="statusFilter"
              class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
            >
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- HeadTeacher List -->
      <div v-if="loadingHeadteachers" class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <p class="mt-2 text-sm text-gray-500">Loading headteachers...</p>
      </div>

      <div v-else-if="filteredHeadteachers.length === 0" class="text-center py-12">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <p class="mt-4 text-lg font-medium text-gray-900">No HeadTeachers Found</p>
        <p class="mt-2 text-sm text-gray-500">
          {{ searchQuery || statusFilter ? 'Try adjusting your search criteria.' : 'Get started by adding your first headteacher.' }}
        </p>
      </div>

      <div v-else class="overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  HeadTeacher
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  School
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Contact
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Visits
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="(headteacher, index) in filteredHeadteachers"
                :key="headteacher.id"
                class="hover:bg-gray-50 transition-all duration-300"
                :style="{ animationDelay: `${index * 0.05}s` }"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ headteacher.name }}</div>
                      <div class="text-sm text-gray-500">ID: {{ headteacher.id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ headteacher.school?.name || 'N/A' }}</div>
                  <div class="text-sm text-gray-500">{{ headteacher.school?.address || 'No address' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ headteacher.email }}</div>
                  <div class="text-sm text-gray-500">{{ headteacher.school?.phone || 'No phone' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ headteacher.school?.visits_count || 0 }} visits
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Active
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center space-x-2">
                    <!-- <button
                      @click="viewHeadteacher(headteacher)"
                      class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                      title="View Details"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button> -->
                    <button
                      @click="editHeadteacher(headteacher)"
                      class="text-green-600 hover:text-green-900 cursor-pointer transition-colors duration-200"
                      title="Edit"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </button>
                    <button
                      @click="deleteHeadteacher(headteacher)"
                      class="text-red-600 hover:text-red-900 cursor-pointer transition-colors duration-200"
                      title="Delete"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HeadTeacherList',
  data() {
    return {
      searchQuery: '',
      statusFilter: '',
      headteachers: [],
      loadingHeadteachers: false
    }
  },
  computed: {
    filteredHeadteachers() {
      let filtered = this.headteachers;

      // Search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(headteacher =>
          headteacher.name.toLowerCase().includes(query) ||
          headteacher.email.toLowerCase().includes(query) ||
          (headteacher.school?.name && headteacher.school.name.toLowerCase().includes(query))
        );
      }

      // Status filter (for now, all are active)
      if (this.statusFilter) {
        // This can be expanded later when we add status tracking
        filtered = filtered.filter(headteacher => {
          if (this.statusFilter === 'active') return true;
          if (this.statusFilter === 'inactive') return false;
          return true;
        });
      }

      return filtered;
    }
  },
  async mounted() {
    await this.loadHeadteachers();
  },
  methods: {
    async loadHeadteachers() {
      this.loadingHeadteachers = true;
      try {
        const response = await fetch('/api/headteachers');
        if (response.ok) {
          this.headteachers = await response.json();
        } else {
          this.$toastr.error('Failed to load headteachers');
        }
      } catch (error) {
        console.error('Error loading headteachers:', error);
        this.$toastr.error('Failed to load headteachers');
      } finally {
        this.loadingHeadteachers = false;
      }
    },

    viewHeadteacher(headteacher) {
      // Navigate to headteacher detail view or show modal
      this.$toastr.info(`Viewing details for ${headteacher.name}`);
      // You can implement a modal or navigate to a detail page
    },

    editHeadteacher(headteacher) {
      // Navigate to edit headteacher page
      this.$router.push(`/schools/${headteacher.school?.id}/edit`);
    },

    async deleteHeadteacher(headteacher) {
      if (!confirm(`Are you sure you want to delete ${headteacher.name}? This action cannot be undone.`)) {
        return;
      }

      try {
        const response = await fetch(`/api/headteachers/${headteacher.id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });

        if (response.ok) {
          this.$toastr.success(`${headteacher.name} has been deleted successfully`);
          await this.loadHeadteachers(); // Reload the list
        } else {
          const data = await response.json();
          this.$toastr.error(data.message || 'Failed to delete headteacher');
        }
      } catch (error) {
        console.error('Error deleting headteacher:', error);
        this.$toastr.error('Failed to delete headteacher');
      }
    }
  }
}
</script>

<style scoped>
.stagger-enter-active {
  transition: all 0.3s ease;
}

.stagger-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.stagger-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.stagger-move {
  transition: transform 0.3s ease;
}
</style>
