<template>
  <div id="app" class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center">
              <img class="h-8 w-auto" src="/logo.png" alt="Education Consultancy Logo">
              <h1 class="ml-3 text-xl font-semibold text-gray-900 hidden sm:block">Education Consultancy</h1>
            </div>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-4">
            <router-link
              to="/"
              class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:scale-105"
              active-class="text-blue-600 bg-blue-50"
            >
              Dashboard
            </router-link>
            <router-link
              to="/visits"
              class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:scale-105"
              active-class="text-blue-600 bg-blue-50"
            >
              Visits
            </router-link>
            <router-link
              to="/schools"
              class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:scale-105"
              active-class="text-blue-600 bg-blue-50"
            >
              Schools
            </router-link>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center">
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="text-gray-700 hover:text-blue-600 p-2 rounded-md transition-colors duration-200"
            >
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Navigation -->
        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-1"
        >
          <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
              <router-link
                to="/"
                class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
                active-class="text-blue-600 bg-blue-50"
                @click="mobileMenuOpen = false"
              >
                Dashboard
              </router-link>
              <router-link
                to="/visits"
                class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
                active-class="text-blue-600 bg-blue-50"
                @click="mobileMenuOpen = false"
              >
                Visits
              </router-link>
              <router-link
                to="/schools"
                class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
                active-class="text-blue-600 bg-blue-50"
                @click="mobileMenuOpen = false"
              >
                Schools
              </router-link>
            </div>
          </div>
        </transition>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <!-- Loading Overlay -->
        <div v-if="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded-lg shadow-xl animate-pulse">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading...</p>
          </div>
        </div>

        <!-- Error Alert -->
        <transition
          enter-active-class="transition-all duration-500 ease-out"
          enter-from-class="opacity-0 transform -translate-y-4"
          enter-to-class="opacity-100 transform translate-y-0"
          leave-active-class="transition-all duration-300 ease-in"
          leave-from-class="opacity-100 transform translate-y-0"
          leave-to-class="opacity-0 transform -translate-y-4"
        >
          <div v-if="error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ error }}</span>
            <button @click="clearError" class="absolute top-0 bottom-0 right-0 px-4 py-3">
              <span class="sr-only">Close</span>
              <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
              </svg>
            </button>
          </div>
        </transition>

        <!-- Router View with Page Transitions -->
        <transition
          name="page"
          mode="out-in"
          appear
        >
          <router-view />
        </transition>
      </div>
    </main>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'App',
  data() {
    return {
      mobileMenuOpen: false
    }
  },
  computed: {
    ...mapState(['loading', 'error'])
  },
  methods: {
    clearError() {
      this.$store.commit('SET_ERROR', null);
    }
  },
  mounted() {
    // Fetch initial data
    this.$store.dispatch('fetchVisits');
    this.$store.dispatch('fetchSchools');
  },
  watch: {
    $route() {
      // Close mobile menu when route changes
      this.mobileMenuOpen = false;
    }
  }
}
</script>

<style>
/* Page Transition Animations */
.page-enter-active {
  transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-enter-from {
  opacity: 0;
  transform: translateX(30px) scale(0.95);
}

.page-leave-to {
  opacity: 0;
  transform: translateX(-30px) scale(0.95);
}

.page-enter-to {
  opacity: 1;
  transform: translateX(0) scale(1);
}

/* Custom animations */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.slide-enter-active, .slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from {
  transform: translateX(-100%);
}
.slide-leave-to {
  transform: translateX(100%);
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Hover effects for interactive elements */
.hover-lift {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Stagger animation for lists */
.stagger-enter-active {
  transition: all 0.3s ease;
}

.stagger-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.stagger-enter-to {
  opacity: 1;
  transform: translateY(0);
}

/* Pulse animation for loading states */
@keyframes pulse-slow {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse-slow {
  animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
