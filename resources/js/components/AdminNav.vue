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
            <router-link
              to="/headteachers"
              class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
              active-class="text-blue-600"
            >
              Headteachers
            </router-link>
          </div>
        </div>

        <div class="hidden md:flex items-center">
          <div class="relative">
            <button
              @click="userDropdownOpen = !userDropdownOpen"
              class="flex items-center space-x-2 text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out cursor-pointer"
            >
              <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                {{ userInitials }}
              </div>
              <span>{{ userName }}</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            <!-- User Dropdown -->
            <div
              v-show="userDropdownOpen"
              class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
            >
              <router-link
                to="/profile"
                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out"
                @click="userDropdownOpen = false"
              >
                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile
              </router-link>
              <div class="border-t border-gray-100"></div>
              <button
                @click="logout"
                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out cursor-pointer"
              >
                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
              </button>
            </div>
          </div>
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
          <router-link
            to="/headteachers"
            class="text-gray-900 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out"
            active-class="text-blue-600 bg-blue-50"
            @click="mobileMenuOpen = false"
          >
            Headteachers
          </router-link>
          <div class="border-t border-gray-200 pt-2">
            <div class="px-3 py-2">
              <div class="flex items-center space-x-2 mb-2">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                  {{ userInitials }}
                </div>
                <span class="text-sm font-medium text-gray-900">{{ userName }}</span>
              </div>
            </div>
            <router-link
              to="/profile"
              class="flex items-center px-3 py-2 text-gray-900 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out"
              active-class="text-blue-600 bg-blue-50"
              @click="mobileMenuOpen = false"
            >
              <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              Profile
            </router-link>
            <button
              @click="logout"
              class="flex items-center w-full text-left px-3 py-2 text-gray-900 hover:text-red-600 rounded-md text-base font-medium transition duration-150 ease-in-out cursor-pointer"
            >
              <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
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
      mobileMenuOpen: false,
      userDropdownOpen: false,
      userInitials: '',
      userName: ''
    }
  },
  async mounted() {
    await this.loadUserData();
    // Close dropdown when clicking outside
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  },
  watch: {
    $route() {
      this.mobileMenuOpen = false;
      this.userDropdownOpen = false;
    }
  },
  methods: {
    async loadUserData() {
      try {
        const response = await fetch('/api/user');
        if (response.ok) {
          const user = await response.json();
          this.userName = user.name || 'User';
          this.userInitials = this.getInitials(user.name || 'User');
        }
      } catch (error) {
        console.error('Failed to load user data:', error);
        this.userName = 'User';
        this.userInitials = 'U';
      }
    },

    getInitials(name) {
      return name
        .split(' ')
        .map(word => word.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);
    },

    handleClickOutside(event) {
      const dropdown = this.$el.querySelector('.relative');
      if (dropdown && !dropdown.contains(event.target)) {
        this.userDropdownOpen = false;
      }
    },

    logout() {
      this.userDropdownOpen = false;
      // Call the global logout method from parent App component
      this.$parent.logout();
    }
  }
}
</script>
