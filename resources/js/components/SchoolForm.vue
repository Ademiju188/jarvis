<template>
  <div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">Add New School</h2>
        <p class="text-gray-600 mt-1">Register a new school for visit records</p>
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
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Headteacher Name *
            </label>
            <input
              type="text"
              v-model="form.headteacher_name"
              required
              placeholder="Enter headteacher name"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Headteacher Email *
            </label>
            <input
              type="email"
              v-model="form.headteacher_email"
              required
              placeholder="Enter headteacher email"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
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
            class="px-6 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
          >
            <span v-if="submitting" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Creating...
            </span>
            <span v-else>Add School</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SchoolForm',
  data() {
    return {
      form: {
        name: '',
        headteacher_name: '',
        headteacher_email: '',
        phone: '',
        address: ''
      },
      submitting: false
    }
  },
  methods: {
    async submitForm() {
      this.submitting = true;
      try {
        const response = await fetch('/api/schools', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.form)
        });

        if (response.ok) {
          this.$store.dispatch('fetchSchools');
          this.$router.push('/schools');
        } else {
          throw new Error('Failed to create school');
        }
      } catch (error) {
        console.error('Error creating school:', error);
        alert('Failed to create school. Please try again.');
      } finally {
        this.submitting = false;
      }
    }
  }
}
</script>
