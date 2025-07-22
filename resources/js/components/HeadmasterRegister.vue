<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <img class="mx-auto h-16 w-auto" src="/logo.png" alt="Jarvis Logo">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          Headmaster Registration
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
          <div>
            <label for="school_name" class="block text-sm font-medium text-gray-700">
              School Name
            </label>
            <div class="mt-1">
              <input
                id="school_name"
                v-model="form.school_name"
                name="school_name"
                type="text"
                required
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="Enter school name"
              >
            </div>
          </div>

          <div>
            <label for="headmaster_name" class="block text-sm font-medium text-gray-700">Headmaster Name</label>
            <input
              id="headmaster_name"
              v-model="form.headmaster_name"
              name="headmaster_name"
              type="text"
              autocomplete="name"
              required
              placeholder="Enter headmaster name"
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
            >
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email Address
            </label>
            <div class="mt-1">
              <input
                id="email"
                v-model="form.email"
                name="email"
                type="email"
                required
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="Enter email address"
              >
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Password
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="form.password"
                name="password"
                type="password"
                required
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="Enter password"
              >
            </div>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
              Confirm Password
            </label>
            <div class="mt-1">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                name="password_confirmation"
                type="password"
                required
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="Confirm password"
              >
            </div>
          </div>

          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">
              Phone Number (Optional)
            </label>
            <div class="mt-1">
              <input
                id="phone"
                v-model="form.phone"
                name="phone"
                type="tel"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="Enter phone number"
              >
            </div>
          </div>

          <div>
            <label for="address" class="block text-sm font-medium text-gray-700">
              School Address (Optional)
            </label>
            <div class="mt-1">
              <textarea
                id="address"
                v-model="form.address"
                name="address"
                rows="3"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="Enter school address"
              ></textarea>
            </div>
          </div>

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
export default {
  name: 'HeadmasterRegister',
  data() {
    return {
      loading: false,
      error: null,
      success: null,
      form: {
        school_name: '',
        headmaster_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        phone: '',
        address: ''
      }
    }
  },
  methods: {
    async register() {
      this.loading = true
      this.error = null
      this.success = null

      // Validate passwords match
      if (this.form.password !== this.form.password_confirmation) {
        this.error = 'Passwords do not match.'
        this.loading = false
        return
      }

      try {
        const response = await fetch('/headmaster/register', {
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
            headmaster_name: '',
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
          this.error = data.message || 'Registration failed. Please try again.'
        }
      } catch (error) {
        this.error = 'An error occurred. Please try again.'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
