<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <img class="mx-auto h-16 w-auto" src="/logo.png" alt="Jarvis Logo">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          Headteacher Registration
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Register your school to access visit records
        </p>
      </div>

      <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
        <div v-if="error" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
          {{ error }}
        </div>

        <div v-if="success" class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
          {{ success }}
        </div>

        <form @submit.prevent="register" class="space-y-6">
          <FormField
            v-model="form.school_name"
            label="School Name"
            id="school_name"
            placeholder="Enter school name"
            :required="true"
            :error="errors.school_name"
          />

          <FormField
            v-model="form.headteacher_name"
            label="Headteacher Name"
            id="headteacher_name"
            placeholder="Enter headteacher name"
            :required="true"
            :error="errors.headteacher_name"
          />

          <FormField
            v-model="form.email"
            label="Email Address"
            id="email"
            type="email"
            placeholder="Enter email address"
            :required="true"
            :error="errors.email"
          />

          <FormField
            v-model="form.password"
            label="Password"
            id="password"
            type="password"
            placeholder="Enter password"
            :required="true"
            :error="errors.password"
            help-text="Minimum 8 characters"
          />

          <FormField
            v-model="form.password_confirmation"
            label="Confirm Password"
            id="password_confirmation"
            type="password"
            placeholder="Confirm password"
            :required="true"
            :error="errors.password_confirmation"
          />

          <FormField
            v-model="form.phone"
            label="Phone Number (Optional)"
            id="phone"
            type="tel"
            placeholder="Enter phone number"
            :error="errors.phone"
          />

          <FormField
            v-model="form.address"
            label="School Address (Optional)"
            id="address"
            type="textarea"
            placeholder="Enter school address"
            :rows="3"
            :error="errors.address"
          />

          <div>
            <button
              type="submit"
              :disabled="loading"
              class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150 ease-in-out cursor-pointer"
            >
              <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
              </span>
              {{ loading ? 'Registering...' : 'Register School' }}
            </button>
          </div>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Already registered?
            <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
              Sign in here
            </router-link>
          </p>
        </div>
      </div>

      <div class="text-center text-sm text-gray-600">
        <p>School Visit Management System</p>
      </div>
    </div>
  </div>
</template>

<script>
import FormField from './FormField.vue'

export default {
  name: 'HeadteacherRegister',
  components: {
    FormField
  },
  data() {
    return {
      loading: false,
      error: null,
      success: null,
      errors: {},
      form: {
        school_name: '',
        headteacher_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        phone: '',
        address: ''
      }
    }
  },
  methods: {
    clearErrors() {
      this.errors = {};
      this.error = null;
    },
    
    async refreshCsrfToken() {
      try {
        const response = await fetch('/csrf-token', {
          method: 'GET',
          headers: {
            'Accept': 'application/json'
          }
        });

        if (response.ok) {
          const data = await response.json();
          // Update the meta tag with the new token
          const metaTag = document.querySelector('meta[name="csrf-token"]');
          if (metaTag) {
            metaTag.setAttribute('content', data.token);
          }
        }
      } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
      }
    },
    
    async register() {
      this.loading = true
      this.clearErrors()

      // Validate passwords match
      if (this.form.password !== this.form.password_confirmation) {
        this.errors.password_confirmation = 'Passwords do not match.';
        this.loading = false
        return
      }

      try {
        // First, refresh the CSRF token
        await this.refreshCsrfToken();

        const response = await fetch('/headteacher/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.form)
        })

        const data = await response.json()

        if (response.ok) {
          this.success = 'Registration successful! You can now log in.'
          this.form = {
            school_name: '',
            headteacher_name: '',
            email: '',
            password: '',
            password_confirmation: '',
            phone: '',
            address: ''
          }

          // Redirect to login after 2 seconds
          setTimeout(() => {
            this.$router.push('/login')
          }, 2000)
        } else {
          if (response.status === 422 && data.errors) {
            this.errors = data.errors;
            this.error = 'Please fix the validation errors below.';
          } else {
            this.error = data.message || 'Registration failed. Please try again.'
          }
        }
      } catch (error) {
        this.error = 'Network error. Please check your connection and try again.'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
