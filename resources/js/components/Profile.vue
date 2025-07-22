<template>
  <div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Profile Settings</h2>

      <div v-if="success" class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
        {{ success }}
      </div>

      <form @submit.prevent="updateProfile" class="space-y-6">
        <FormField
          v-model="form.name"
          label="Full Name"
          id="name"
          placeholder="Enter your full name"
          :required="true"
          :error="errors.name"
        />

        <FormField
          v-model="form.email"
          label="Email Address"
          id="email"
          type="email"
          placeholder="Enter your email address"
          :required="true"
          :error="errors.email"
        />

        <div class="border-t border-gray-200 pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password (Optional)</h3>

          <FormField
            v-model="form.password"
            label="New Password"
            id="password"
            type="password"
            placeholder="Enter new password"
            :error="errors.password"
            help-text="Leave blank to keep current password"
          />

          <FormField
            v-model="form.password_confirmation"
            label="Confirm New Password"
            id="password_confirmation"
            type="password"
            placeholder="Confirm new password"
            :error="errors.password_confirmation"
          />
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150 ease-in-out cursor-pointer"
          >
            <span v-if="loading" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Updating...
            </span>
            <span v-else>Update Profile</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import FormField from './FormField.vue'
import toastr from 'toastr'

export default {
  name: 'Profile',
  components: {
    FormField
  },
  data() {
    return {
      loading: false,
      success: null,
      errors: {},
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  async mounted() {
    await this.loadUserProfile()
  },
  methods: {
    clearMessages() {
      this.success = null;
      this.errors = {};
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

    async loadUserProfile() {
      try {
        const response = await fetch('/api/user')
        if (!response.ok) throw new Error('Failed to load user profile')

        const user = await response.json()
        this.form.name = user.name || ''
        this.form.email = user.email || ''
      } catch (error) {
        toastr.error('Failed to load user profile')
      }
    },

    async updateProfile() {
      this.loading = true
      this.clearMessages()

      // Validate password confirmation if password is provided
      if (this.form.password && this.form.password !== this.form.password_confirmation) {
        this.errors.password_confirmation = 'Passwords do not match.'
        this.loading = false
        return
      }

      try {
        // First, refresh the CSRF token
        await this.refreshCsrfToken();

        const updateData = {
          name: this.form.name,
          email: this.form.email
        }

        // Only include password fields if a new password is provided
        if (this.form.password) {
          updateData.password = this.form.password
          updateData.password_confirmation = this.form.password_confirmation
        }

        const response = await fetch('/api/user/profile', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(updateData)
        })

        const data = await response.json()

        if (response.ok) {
          this.success = 'Profile updated successfully!'
          // Clear password fields
          this.form.password = ''
          this.form.password_confirmation = ''
          toastr.success('Profile updated successfully!')
        } else {
          if (response.status === 422 && data.errors) {
            this.errors = data.errors
            toastr.error('Please fix the validation errors below.')
          } else {
            throw new Error(data.message || 'Failed to update profile')
          }
        }
      } catch (error) {
        toastr.error(error.message || 'Failed to update profile. Please try again.')
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

