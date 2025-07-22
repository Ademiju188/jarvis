<template>
  <div id="app" class="min-h-screen bg-gray-100">
    <!-- Admin Navigation - Show for admin routes -->
    <AdminNav v-if="isAuthenticated && isAdminRoute && $route.path !== '/login'" />

    <!-- Headteacher Navigation - Show for headteacher routes -->
    <HeadteacherNav v-if="isAuthenticated && isHeadteacherRoute" />

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Loading...</span>
      </div>
    </div>

    <!-- Error Alert -->
    <div v-if="error" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
      <div class="flex items-center">
        <span class="mr-2">⚠️</span>
        <span>{{ error }}</span>
        <button @click="error = null" class="ml-4 text-red-700 hover:text-red-900 cursor-pointer">×</button>
      </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <transition name="page" mode="out-in">
          <router-view />
        </transition>
      </div>
    </main>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import AdminNav from './components/AdminNav.vue'
import HeadteacherNav from './components/HeadteacherNav.vue'

export default {
  name: 'App',
  components: {
    AdminNav,
    HeadteacherNav
  },
  computed: {
    ...mapState(['loading', 'error', 'isAuthenticated']),
    isAdminRoute() {
      return !this.$route.path.startsWith('/headteacher/')
    },
    isHeadteacherRoute() {
      return this.$route.path.startsWith('/headteacher/')
    }
  },
  methods: {
    // Global logout method that can be called from navigation components
    async logout() {
      try {
        // First, refresh the CSRF token
        await this.refreshCsrfToken();

        const response = await fetch('/logout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })

        // Always redirect to login page regardless of response
        this.$store.commit('SET_AUTHENTICATED', false)
        this.$router.push('/login')
      } catch (error) {
        // If there's an error, still redirect to login
        this.$store.commit('SET_AUTHENTICATED', false)
        this.$router.push('/login')
      }
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
    }
  }
}
</script>

<style>
.page-enter-active,
.page-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.page-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}
</style>
