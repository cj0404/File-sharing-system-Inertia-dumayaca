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
      <h2 class="text-2xl font-bold leading-tight text-gray-900">
        Edit: {{ file.original_name }}
      </h2>
    </template>

    <div class="py-8 max-w-xl mx-auto px-4">
      <div class="bg-white rounded-lg shadow p-6 space-y-5">

        <label class="flex items-center gap-3 cursor-pointer">
          <input type="checkbox" v-model="form.is_public" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" />
          <span class="text-sm font-medium text-gray-700">Make Public</span>
        </label>

        <div v-if="file.has_password">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" v-model="form.remove_password" class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500" />
            <span class="text-sm font-medium text-gray-700">Remove existing password</span>
          </label>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">
            {{ file.has_password ? 'Change password' : 'Set password (optional)' }}
          </label>
          <input
            v-model="form.password"
            type="password"
            :disabled="form.remove_password"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-900 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">Expiry date</label>
          <input
            v-model="form.expires_at"
            type="datetime-local"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div class="flex gap-3 pt-4 border-t">
          <button
            @click="submit"
            :disabled="form.processing"
            class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-2.5 rounded-lg transition duration-150 ease-in-out"
          >
            {{ form.processing ? 'Saving…' : 'Save Changes' }}
          </button>
          <Link
            :href="route('files.index')"
            class="flex-1 text-center border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition"
          >
            Cancel
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
