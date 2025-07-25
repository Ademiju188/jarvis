<template>
  <div class="max-w-6xl mx-auto py-8">
    <div v-if="loading" class="text-center py-12">
      <span class="text-gray-500">Loading school information...</span>
    </div>
    <div v-else-if="error" class="text-center py-12">
      <span class="text-red-600 font-semibold">{{ error }}</span>
    </div>
    <div v-else-if="school" class="space-y-8">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-4">
        <h2 class="text-2xl font-semibold text-gray-900 mb-2">{{ school.name }}</h2>
        <p class="text-gray-600 mb-1"><span class="font-medium">Headteacher:</span> {{ school.headteacher_name }}</p>
        <p class="text-gray-600 mb-1"><span class="font-medium">Email:</span> {{ school.headteacher_email }}</p>
        <p v-if="school.phone" class="text-gray-600 mb-1"><span class="font-medium">Phone:</span> {{ school.phone }}</p>
        <p v-if="school.address" class="text-gray-600"><span class="font-medium">Address:</span> {{ school.address }}</p>
      </div>
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Visit Records</h3>
        <div v-if="school.visits && school.visits.length">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultant</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Context</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-for="visit in school.visits" :key="visit.id" class="hover:bg-blue-50 transition">
                <td class="px-3 py-2 whitespace-nowrap cursor-pointer" @click="goToVisit(visit.id)">{{ formatDate(visit.visit_date) }}</td>
                <td class="px-3 py-2 whitespace-nowrap cursor-pointer" @click="goToVisit(visit.id)">{{ visit.consultant_name }}</td>
                <td class="px-3 py-2 cursor-pointer" @click="goToVisit(visit.id)">{{ visit.context }}</td>
                <td class="px-3 py-2">
                  <span :class="getStatusClass(visit.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ formatStatus(visit.status) }}
                  </span>
                </td>
                <td class="px-3 py-2 text-right">
                  <button @click.stop="confirmDeleteVisit(visit)" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="text-gray-500 text-center py-8">
          No visits recorded for this school yet.
        </div>
      </div>
    </div>
    <div v-if="showDeleteVisitModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative">
        <h3 class="text-lg font-semibold mb-4 text-red-700">Delete Visit?</h3>
        <p class="mb-6 text-gray-700">Are you sure you want to delete this visit? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
          <button @click="showDeleteVisitModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
          <button @click="deleteVisit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SchoolAccess',
  data() {
    return {
      loading: true,
      error: null,
      school: null,
      showDeleteVisitModal: false,
      visitToDelete: null
    }
  },
  async mounted() {
    await this.fetchSchool();
  },
  methods: {
    async fetchSchool() {
      this.loading = true;
      this.error = null;
      try {
        const token = this.$route.params.token;
        const response = await fetch(`/api/schools/token/${token}`);
        if (!response.ok) {
          throw new Error('School not found or invalid access link.');
        }
        this.school = await response.json();
      } catch (err) {
        this.error = err.message || 'Failed to load school.';
      } finally {
        this.loading = false;
      }
    },
    formatDate(date) {
      if (!date) return '';
      return new Date(date).toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
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
        finalised: 'bg-blue-100 text-blue-800'
      };
      return classes[status] || classes.draft;
    },
    goToVisit(id) {
      this.$router.push(`/visits/${id}`);
    },
    confirmDeleteVisit(visit) {
      this.visitToDelete = visit;
      this.showDeleteVisitModal = true;
    },
    async deleteVisit() {
      if (!this.visitToDelete) return;
      try {
        const response = await fetch(`/api/visits/${this.visitToDelete.id}`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });
        if (response.ok) {
          this.$toastr.success('Visit deleted successfully!');
          // Remove from local list
          this.school.visits = this.school.visits.filter(v => v.id !== this.visitToDelete.id);
          this.showDeleteVisitModal = false;
          this.visitToDelete = null;
        } else {
          this.$toastr.error('Failed to delete visit.');
        }
      } catch (error) {
        this.$toastr.error('Failed to delete visit.');
      }
    }
  }
}
</script>
