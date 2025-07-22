<template>
  <nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <img src="/logo.png" alt="Logo" class="h-8 w-auto">
          <div class="hidden md:flex ml-4 space-x-8">
            <router-link
              to="/dashboard"
              class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
              active-class="text-blue-600"
            >
              Dashboard
            </router-link>
            <router-link
              to="/visits"
              class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
              active-class="text-blue-600"
            >
              Visits
            </router-link>
            <router-link
              to="/schools"
              class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
              active-class="text-blue-600"
            >
              Schools
            </router-link>
          </div>
        </div>

        <div class="hidden md:flex items-center">
          <button
            @click="logout"
            class="text-gray-900 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out cursor-pointer"
          >
            Logout
          </button>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="text-gray-900 hover:text-blue-600 p-2 rounded-md transition duration-150 ease-in-out cursor-pointer"
            aria-label="Toggle menu"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <div v-show="mobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200 bg-white">
          <router-link
            to="/dashboard"
            class="text-gray-900 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out"
            active-class="text-blue-600 bg-blue-50"
            @click="mobileMenuOpen = false"
          >
            Dashboard
          </router-link>
          <router-link
            to="/visits"
            class="text-gray-900 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out"
            active-class="text-blue-600 bg-blue-50"
            @click="mobileMenuOpen = false"
          >
            Visits
          </router-link>
          <router-link
            to="/schools"
            class="text-gray-900 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out"
            active-class="text-blue-600 bg-blue-50"
            @click="mobileMenuOpen = false"
          >
            Schools
          </router-link>
          <div class="border-t border-gray-200 pt-2">
            <button
              @click="logout"
              class="text-gray-900 hover:text-red-600 block w-full text-left px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out cursor-pointer"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'AdminNav',
  data() {
    return {
      mobileMenuOpen: false
    }
  },
  watch: {
    $route() {
      this.mobileMenuOpen = false
    }
  },
  methods: {
    logout() {
      // Call the global logout method from parent App component
      this.$parent.logout()
    }
  }
}
</script>
