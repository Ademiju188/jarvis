<template>
  <div class="max-w-4xl mx-auto">
    <transition
      enter-active-class="transition-all duration-600 ease-out"
      enter-from-class="opacity-0 transform translateY(20px)"
      enter-to-class="opacity-100 transform translateY(0)"
      appear
    >
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover-lift">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-900">Create Visit Record</h2>
          <p class="text-gray-600 mt-1">Document your school visit with detailed information</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="p-6 space-y-6">
          <!-- School Selection -->
          <transition
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 transform translateX(-20px)"
            enter-to-class="opacity-100 transform translateX(0)"
            appear
          >
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                School *
              </label>
              <select
                v-model="form.school_id"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
              >
                <option value="">Select a school</option>
                <option v-for="school in schools" :key="school.id" :value="school.id">
                  {{ school.name }} - {{ school.headteacher_name }}
                </option>
              </select>
            </div>
          </transition>

          <!-- Visit Date and Consultant -->
          <transition
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 transform translateX(-20px)"
            enter-to-class="opacity-100 transform translateX(0)"
            appear
          >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Visit Date *
                </label>
                <input
                  type="date"
                  v-model="form.visit_date"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Consultant Name *
                </label>
                <input
                  type="text"
                  v-model="form.consultant_name"
                  required
                  placeholder="Enter consultant name"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
                >
              </div>
            </div>
          </transition>

          <!-- Context -->
          <transition
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 transform translateX(-20px)"
            enter-to-class="opacity-100 transform translateX(0)"
            appear
          >
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Context *
              </label>
              <textarea
                v-model="form.context"
                required
                rows="4"
                placeholder="Describe the context and purpose of this visit..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
              ></textarea>
            </div>
          </transition>

          <!-- Activities Undertaken -->
          <transition
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 transform translateX(-20px)"
            enter-to-class="opacity-100 transform translateX(0)"
            appear
          >
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Activities Undertaken *
              </label>
              <textarea
                v-model="form.activities_undertaken"
                required
                rows="4"
                placeholder="Detail the activities and observations made during the visit..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
              ></textarea>
            </div>
          </transition>

          <!-- Key Findings -->
          <transition
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 transform translateX(-20px)"
            enter-to-class="opacity-100 transform translateX(0)"
            appear
          >
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Key Findings *
              </label>
              <textarea
                v-model="form.key_findings"
                required
                rows="4"
                placeholder="Summarize the key findings and insights from the visit..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
              ></textarea>
            </div>
          </transition>

          <!-- Recommendations -->
          <transition
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 transform translateX(-20px)"
            enter-to-class="opacity-100 transform translateX(0)"
            appear
          >
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Recommendations & Next Steps *
              </label>
              <textarea
                v-model="form.recommendations"
                required
                rows="4"
                placeholder="Provide recommendations and outline next steps..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400 resize-none"
              ></textarea>
            </div>
          </transition>

          <!-- Next Visit Date -->
          <transition
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 transform translateX(-20px)"
            enter-to-class="opacity-100 transform translateX(0)"
            appear
          >
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Next Visit Date
              </label>
              <input
                type="date"
                v-model="form.next_visit_date"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover:border-blue-400"
              >
            </div>
          </transition>

          <!-- Form Actions -->
          <transition
            enter-active-class="transition-all duration-600 ease-out"
            enter-from-class="opacity-0 transform translateY(20px)"
            enter-to-class="opacity-100 transform translateY(0)"
            appear
          >
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
              <router-link
                to="/visits"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 hover-lift"
              >
                Cancel
              </router-link>
              <button
                type="submit"
                :disabled="submitting"
                class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 hover-lift"
              >
                <span v-if="submitting" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Creating...
                </span>
                <span v-else>Create Visit Record</span>
              </button>
            </div>
          </transition>
        </form>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'VisitForm',
  data() {
    return {
      form: {
        school_id: '',
        visit_date: '',
        consultant_name: '',
        context: '',
        activities_undertaken: '',
        key_findings: '',
        recommendations: '',
        next_visit_date: ''
      },
      submitting: false
    }
  },
  computed: {
    ...mapState(['schools'])
  },
  methods: {
    async submitForm() {
      this.submitting = true;
      try {
        const visit = await this.$store.dispatch('createVisit', this.form);
        this.$router.push(`/visits/${visit.id}`);
      } catch (error) {
        console.error('Error creating visit:', error);
      } finally {
        this.submitting = false;
      }
    }
  }
}
</script>
