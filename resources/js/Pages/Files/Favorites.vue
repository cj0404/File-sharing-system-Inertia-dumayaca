<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({ files: Array })

const search = ref('')

const filtered = computed(() =>
  props.files.filter(f =>
    f.original_name.toLowerCase().includes(search.value.toLowerCase())
  )
)

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
  <Head title="Favorites" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-bold leading-tight text-gray-900">💾 Favorites</h2>
    </template>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Toolbar -->
      <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <input
          v-model="search"
          placeholder="🔍 Search favorites…"
          class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        />
        <Link
          :href="route('files.index')"
          class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition duration-150 ease-in-out"
        >
          Back to Files
        </Link>
      </div>

      <!-- Empty State -->
      <div v-if="filtered.length === 0" class="bg-white shadow rounded-lg p-12 text-center">
        <p class="text-2xl mb-4">💾</p>
        <p class="text-gray-600 font-medium">No favorites yet</p>
        <p class="text-gray-500 text-sm mt-2">Mark files as favorite to see them here</p>
      </div>

      <!-- File Table -->
      <div v-else class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-yellow-50 text-gray-700 uppercase text-xs font-semibold">
            <tr>
              <th class="px-6 py-3 text-left">File</th>
              <th class="px-6 py-3 text-left">Size</th>
              <th class="px-6 py-3 text-left">Status</th>
              <th class="px-6 py-3 text-left">Downloads</th>
              <th class="px-6 py-3 text-left">Uploaded</th>
              <th class="px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="file in filtered" :key="file.id" class="hover:bg-yellow-50 transition duration-150 ease-in-out">
              <td class="px-6 py-3">
                <span class="mr-2">{{ icon(file.mime_type) }}</span>
                <span class="font-medium text-gray-900 truncate max-w-[200px] inline-block align-middle">
                  {{ file.original_name }}
                </span>
              </td>
              <td class="px-6 py-3 text-gray-600">{{ file.size }}</td>
              <td class="px-6 py-3">
                <span
                  class="px-3 py-1 rounded-full text-xs font-medium inline-block"
                  :class="file.is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700'"
                >
                  {{ file.is_public ? '🌐 Public' : '🔒 Private' }}
                </span>
              </td>
              <td class="px-6 py-3 text-gray-600 font-semibold">{{ file.download_count }}</td>
              <td class="px-6 py-3 text-sm text-gray-500">{{ file.created_at }}</td>
              <td class="px-6 py-3 text-right">
                <button
                  @click="copyLink(file.share_token)"
                  class="text-indigo-600 hover:text-indigo-900 font-medium text-sm mr-3"
                >
                  Share 🔗
                </button>
                <Link
                  :href="route('files.edit', file.id)"
                  class="text-blue-600 hover:text-blue-900 font-medium text-sm"
                >
                  Edit ✏️
                </Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
