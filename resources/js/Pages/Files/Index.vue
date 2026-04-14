<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({ files: Array })

const page   = usePage()
const flash  = computed(() => page.props.flash)
const search = ref('')

const filtered = computed(() =>
  props.files.filter(f =>
    f.original_name.toLowerCase().includes(search.value.toLowerCase())
  )
)

function destroy(id) {
  if (confirm('Delete this file?')) {
    router.delete(route('files.destroy', id), { preserveScroll: true })
  }
}

function icon(mime) {
  if (mime.startsWith('image/'))       return '🖼️'
  if (mime === 'application/pdf')       return '📄'
  if (mime.startsWith('text/'))         return '📝'
  if (mime.includes('spreadsheet'))     return '📊'
  if (mime.includes('zip') || mime.includes('tar')) return '🗜️'
  return '📁'
}

function copyLink(token) {
  navigator.clipboard.writeText(window.location.origin + '/share/' + token)
  alert('Link copied!')
}
</script>

<template>
  <Head title="My Files" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">My Files</h2>
    </template>

    <div class="py-8 max-w-6xl mx-auto px-4">

      <!-- Flash -->
      <div v-if="flash?.success" class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
        {{ flash.success }}
      </div>

      <!-- Toolbar -->
      <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input
          v-model="search"
          placeholder="Search files…"
          class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
        />
        <Link
          :href="route('files.create')"
          class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-5 py-2 rounded-lg text-sm"
        >
          + Upload File
        </Link>
      </div>

      <!-- File Table -->
      <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 uppercase text-xs">
            <tr>
              <th class="px-4 py-3 text-left">File</th>
              <th class="px-4 py-3 text-left">Size</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-left">Downloads</th>
              <th class="px-4 py-3 text-left">Uploaded</th>
              <th class="px-4 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="file in filtered" :key="file.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
              <td class="px-4 py-3">
                <span class="mr-2">{{ icon(file.mime_type) }}</span>
                <span class="font-medium text-gray-800 dark:text-gray-100 truncate max-w-[200px] inline-block align-middle">
                  {{ file.original_name }}
                </span>
              </td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ file.size }}</td>
              <td class="px-4 py-3">
                <span
                  class="px-2 py-0.5 rounded-full text-xs font-medium"
                  :class="file.is_public ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'"
                >
                  {{ file.is_public ? 'Public' : 'Private' }}
                </span>
                <span v-if="file.has_password" class="ml-1 text-yellow-500 text-xs">🔒</span>
              </td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ file.download_count }}</td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ file.created_at }}</td>
              <td class="px-4 py-3">
                <div class="flex justify-end gap-2">
                  <button @click="copyLink(file.share_token)" class="text-indigo-600 hover:text-indigo-800 text-xs" title="Copy link">
                    🔗 Share
                  </button>
                  <Link :href="route('files.download', file.id)" class="text-blue-600 hover:text-blue-800 text-xs">
                    ↓ DL
                  </Link>
                  <Link :href="route('files.edit', file.id)" class="text-gray-600 hover:text-gray-800 text-xs">
                    ✎ Edit
                  </Link>
                  <button @click="destroy(file.id)" class="text-red-500 hover:text-red-700 text-xs">
                    ✕ Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filtered.length === 0">
              <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                No files yet. <Link :href="route('files.create')" class="text-indigo-600 underline">Upload one →</Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
