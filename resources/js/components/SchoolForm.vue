<template>
  <div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">{{ isEdit ? 'Edit School' : 'Add New School' }}</h2>
        <p class="text-gray-600 mt-1">
          {{ isEdit ? 'Update school details' : 'Register a new school for visit records' }}
        </p>
      </div>

      <form @submit.prevent="submitForm" class="p-6 space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            School Name *
          </label>
          <input
            type="text"
            v-model="form.name"
            required
            placeholder="Enter school name"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
          >
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="headmaster_name" class="block text-sm font-medium text-gray-700">Headmaster Name *</label>
            <input
              v-model="form.headmaster_name"
              type="text"
              id="headmaster_name"
              placeholder="Enter headmaster name"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
          </div>
          <div>
            <label for="headmaster_email" class="block text-sm font-medium text-gray-700">Headmaster Email *</label>
            <input
              v-model="form.headmaster_email"
              type="email"
              id="headmaster_email"
              placeholder="Enter headmaster email"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Phone Number
            </label>
            <input
              type="tel"
              v-model="form.phone"
              placeholder="Enter phone number"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Address
            </label>
            <input
              type="text"
              v-model="form.address"
              placeholder="Enter school address"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
            >
          </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
          <router-link
            to="/schools"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
          >
            Cancel
          </router-link>
          <button
            type="submit"
            :disabled="submitting"
            class="px-6 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 cursor-pointer"
          >
            <span v-if="submitting" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ isEdit ? 'Saving...' : 'Creating...' }}
            </span>
            <span v-else>{{ isEdit ? 'Save Changes' : 'Add School' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import toastr from 'toastr';

export default {
  name: 'SchoolForm',
  data() {
    return {
      form: {
        name: '',
        headmaster_name: '',
        headmaster_email: '',
        phone: '',
        address: ''
      },
      submitting: false,
      isEdit: false,
      schoolId: null
    }
  },
  async created() {
    // If editing, fetch school data
    if (this.$route.params.id) {
      this.isEdit = true;
      this.schoolId = this.$route.params.id;
      try {
        const response = await fetch(`/api/schools/${this.schoolId}`);
        if (!response.ok) throw new Error('Failed to fetch school');
        const school = await response.json();
        this.form = {
          name: school.name,
          headmaster_name: school.headmaster_name,
          headmaster_email: school.headmaster_email,
          phone: school.phone || '',
          address: school.address || ''
        };
      } catch (err) {
        toastr.error('Failed to load school details.');
        this.$router.push('/schools');
      }
    }
  },
  methods: {
    async submitForm() {
      this.submitting = true;
      try {
        let response;
        if (this.isEdit) {
          response = await fetch(`/api/schools/${this.schoolId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(this.form)
          });
        } else {
          response = await fetch('/api/schools', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(this.form)
          });
        }

        if (response.ok) {
          this.$store.dispatch('fetchSchools');
          toastr.success(this.isEdit ? 'School updated successfully!' : 'School created successfully!');
          this.$router.push('/schools');
        } else {
          throw new Error('Failed to save school');
        }
      } catch (error) {
        toastr.error('Failed to save school. Please try again.');
      } finally {
        this.submitting = false;
      }
    }
  }
}
</script>
