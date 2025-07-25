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
        <FormField
          v-model="form.name"
          label="School Name"
          id="school_name"
          placeholder="Enter school name"
          :required="true"
          :error="errors.name"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <FormField
            v-model="form.headteacher_name"
            label="Headteacher Name"
            id="headteacher_name"
            placeholder="Enter headteacher name"
            :required="true"
            :error="errors.headteacher_name"
          />
          <FormField
            v-model="form.headteacher_email"
            label="Headteacher Email"
            id="headteacher_email"
            type="email"
            placeholder="Enter headteacher email"
            :required="true"
            :error="errors.headteacher_email"
          />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <FormField
            v-model="form.phone"
            label="Phone Number"
            id="phone"
            type="tel"
            placeholder="Enter phone number"
            :error="errors.phone"
          />
          <FormField
            v-model="form.address"
            label="Address"
            id="address"
            placeholder="Enter school address"
            :error="errors.address"
          />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <FormField
            v-model="form.password"
            label="Password"
            id="password"
            type="password"
            placeholder="Enter password"
            :required="!isEdit"
            :error="errors.password"
            :help-text="isEdit ? 'Leave blank to keep current password' : 'Minimum 8 characters'"
          />
          <FormField
            v-model="form.password_confirmation"
            label="Password Confirmation"
            id="password_confirmation"
            type="password"
            placeholder="Enter password confirmation"
            :required="!isEdit"
            :error="errors.password_confirmation"
          />
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
import FormField from './FormField.vue';

export default {
  name: 'SchoolForm',
  components: {
    FormField
  },
  data() {
    return {
      form: {
        name: '',
        headteacher_name: '',
        headteacher_email: '',
        phone: '',
        address: '',
        password: '',
        password_confirmation: ''
      },
      errors: {},
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
        const response = await fetch(`/api/schools/${this.schoolId}`, {
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });
        if (!response.ok) throw new Error('Failed to fetch school');
        const school = await response.json();
        this.form = {
          name: school.name,
          headteacher_name: school.headteacher.name,
          headteacher_email: school.headteacher.email,
          phone: school.phone || '',
          address: school.address || '',
          password: '',
          password_confirmation: ''
        };
      } catch (err) {
        toastr.error('Failed to load school details.');
        this.$router.push('/schools');
      }
    }
  },
  methods: {
    clearErrors() {
      this.errors = {};
    },
    async refreshCsrfToken() {
      try {
        const response = await fetch('/csrf-token');
        if (response.ok) {
          const data = await response.json();
          const metaTag = document.querySelector('meta[name="csrf-token"]');
          if (metaTag) {
            metaTag.setAttribute('content', data.token);
          }
        }
      } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
      }
    },
    async submitForm() {
      this.clearErrors();
      this.submitting = true;

      try {
        // First, refresh the CSRF token
        await this.refreshCsrfToken();

        // Prepare form data
        const formData = {
          name: this.form.name,
          headteacher_name: this.form.headteacher_name,
          headteacher_email: this.form.headteacher_email,
          phone: this.form.phone,
          address: this.form.address
        };

        // Only include password fields if provided (for new schools) or if password is entered (for editing)
        if (!this.isEdit || (this.isEdit && this.form.password)) {
          formData.password = this.form.password;
          formData.password_confirmation = this.form.password_confirmation;
        }

        let response;
        if (this.isEdit) {
          response = await fetch(`/api/schools/${this.schoolId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
          });
        } else {
          response = await fetch('/api/schools', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
          });
        }

        const data = await response.json();

        if (response.ok) {
          this.$store.dispatch('fetchSchools');
          toastr.success(this.isEdit ? 'School updated successfully!' : 'School created successfully!');
          this.$router.push('/schools');
        } else {
          if (response.status === 422 && data.errors) {
            this.errors = data.errors;
            toastr.error('Please fix the validation errors below.');
          } else {
            throw new Error(data.message || 'Failed to save school');
          }
        }
      } catch (error) {
        toastr.error(error.message || 'Failed to save school. Please try again.');
      } finally {
        this.submitting = false;
      }
    }
  }
}
</script>
