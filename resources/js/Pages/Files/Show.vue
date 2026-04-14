<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props   = defineProps({ file: Object })
const copied  = ref(false)

function copy() {
  navigator.clipboard.writeText(props.file.share_url)
  copied.value = true
  setTimeout(() => copied.value = false, 2000)
}
</script>

<template>
  <Head :title="file.original_name" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">File Details</h2>
    </template>

    <div class="py-8 max-w-xl mx-auto px-4">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 break-all">{{ file.original_name }}</h3>

        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
          <dt class="text-gray-500">Size</dt>       <dd class="font-medium dark:text-gray-200">{{ file.size }}</dd>
          <dt class="text-gray-500">Type</dt>       <dd class="font-medium dark:text-gray-200 truncate">{{ file.mime_type }}</dd>
          <dt class="text-gray-500">Visibility</dt><dd class="font-medium dark:text-gray-200">{{ file.is_public ? 'Public' : 'Private' }}</dd>
          <dt class="text-gray-500">Password</dt>  <dd class="font-medium dark:text-gray-200">{{ file.has_password ? 'Yes 🔒' : 'None' }}</dd>
          <dt class="text-gray-500">Downloads</dt> <dd class="font-medium dark:text-gray-200">{{ file.download_count }}</dd>
          <dt class="text-gray-500">Expires</dt>   <dd class="font-medium dark:text-gray-200">{{ file.expires_at ?? 'Never' }}</dd>
        </dl>

        <!-- Share URL -->
        <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-2">
          <input :value="file.share_url" readonly class="flex-1 bg-transparent text-sm text-gray-700 dark:text-gray-300 truncate outline-none" />
          <button @click="copy" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium shrink-0">
            {{ copied ? 'Copied!' : 'Copy' }}
          </button>
        </div>

        <div class="flex gap-3 pt-2">
          <Link :href="route('files.download', file.id)" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 rounded-lg transition text-sm">
            ↓ Download
          </Link>
          <Link :href="route('files.edit', file.id)" class="flex-1 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">
            ✎ Edit
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
