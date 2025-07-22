<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <img class="mx-auto h-16 w-auto" src="/logo.png" alt="Jarvis Logo">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          Sign in to your account
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Access your dashboard
        </p>
      </div>

      <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
        <div v-if="error" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
          {{ error }}
        </div>

        <form @submit.prevent="login" class="space-y-6">
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
                placeholder="Enter your email"
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
                placeholder="Enter your password"
              >
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
              {{ loading ? 'Signing In...' : 'Sign In' }}
            </button>
          </div>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Don't have an account?
            <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
              Register your school
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
  name: 'Login',
  data() {
    return {
      loading: false,
      error: null,
      form: {
        email: '',
        password: ''
      }
    }
  },
  methods: {
    async login() {
      this.loading = true
      this.error = null

      try {
        // First, refresh the CSRF token
        await this.refreshCsrfToken()

        const response = await fetch('/login', {
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
          this.$store.commit('SET_AUTHENTICATED', true)

          // Redirect based on user role
          if (data.redirect) {
            this.$router.push(data.redirect)
          } else {
            // Default to dashboard
            this.$router.push('/dashboard')
          }
        } else {
          this.error = data.message || 'Invalid credentials. Please try again.'
        }
      } catch (error) {
        this.error = 'An error occurred. Please try again.'
      } finally {
        this.loading = false
      }
    },

    async refreshCsrfToken() {
      try {
        // Get a fresh CSRF token
        const response = await fetch('/csrf-token', {
          method: 'GET',
          headers: {
            'Accept': 'application/json'
          }
        })

        if (response.ok) {
          const data = await response.json()
          // Update the meta tag with the new token
          const metaTag = document.querySelector('meta[name="csrf-token"]')
          if (metaTag) {
            metaTag.setAttribute('content', data.token)
          }
        }
      } catch (error) {
        console.error('Failed to refresh CSRF token:', error)
      }
    }
  }
}
</script>
