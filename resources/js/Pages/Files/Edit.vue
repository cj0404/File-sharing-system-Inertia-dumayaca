<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

const props = defineProps({ file: Object })

const form = useForm({
  is_public:       props.file.is_public,
  password:        '',
  remove_password: false,
  expires_at:      props.file.expires_at ?? '',
})

function submit() {
  form.patch(route('files.update', props.file.id))
}
</script>

<template>
  <Head title="Edit File" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
        Edit: {{ file.original_name }}
      </h2>
    </template>

    <div class="py-8 max-w-xl mx-auto px-4">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-5">

        <label class="flex items-center gap-3 cursor-pointer">
          <input type="checkbox" v-model="form.is_public" class="w-4 h-4 text-indigo-600 rounded" />
          <span class="text-sm text-gray-700 dark:text-gray-300">Public</span>
        </label>

        <div v-if="file.has_password">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" v-model="form.remove_password" class="w-4 h-4 text-red-500 rounded" />
            <span class="text-sm text-gray-700 dark:text-gray-300">Remove existing password</span>
          </label>
        </div>

        <div>
          <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">
            {{ file.has_password ? 'Change password' : 'Set password (optional)' }}
          </label>
          <input
            v-model="form.password"
            type="password"
            :disabled="form.remove_password"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-40"
          />
        </div>

        <div>
          <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Expiry date</label>
          <input
            v-model="form.expires_at"
            type="datetime-local"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <div class="flex gap-3 pt-2">
          <button
            @click="submit"
            :disabled="form.processing"
            class="flex-1 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-semibold py-2.5 rounded-lg transition"
          >
            {{ form.processing ? 'Saving…' : 'Save Changes' }}
          </button>
          <Link
            :href="route('files.index')"
            class="flex-1 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
          >
            Cancel
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
