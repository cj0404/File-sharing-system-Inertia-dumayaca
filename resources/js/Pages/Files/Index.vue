<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import axios from 'axios'

const props = defineProps({ files: Array })

const page   = usePage()
const flash  = computed(() => page.props.flash)
const search = ref('')
const filterFavorites = ref(false)
const favoritedFiles = ref(new Set())

const filtered = computed(() =>
  props.files.filter(f => {
    const matchesSearch = f.original_name.toLowerCase().includes(search.value.toLowerCase())
    const matchesFilter = !filterFavorites.value || favoritedFiles.value.has(f.id)
    return matchesSearch && matchesFilter
  })
)

function destroy(id) {
  if (confirm('Delete this file?')) {
    router.delete(route('files.destroy', id), { preserveScroll: true })
  }
}

async function toggleFavorite(fileId) {
  console.log('Toggle clicked for file', fileId)
  try {
    const response = await axios.post(`/api/${fileId}/star`)
    if (response.data.starred) {
      favoritedFiles.value.add(fileId)
      alert('Favorited!')
    } else {
      favoritedFiles.value.delete(fileId)
      alert('Unfavorited!')
    }
  } catch (error) {
    console.error('Error toggling favorite:', error)
    alert('Error: ' + (error.response?.data?.message || error.message || 'Unknown'))
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
  alert('✓ Share link copied to clipboard!')
}
</script>

<template>
  <Head title="My Files" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold leading-tight text-gray-900">📁 My Files</h2>
        <span class="text-sm text-gray-600">{{ filtered.length }} file{{ filtered.length !== 1 ? 's' : '' }}</span>
      </div>
    </template>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Flash Messages -->
      <div v-if="flash?.success" class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
        <span>✓</span> {{ flash.success }}
      </div>

      <!-- Filter & Toolbar -->
      <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <input
          v-model="search"
          placeholder="🔍 Search files…"
          class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
        />
        <button
          @click="filterFavorites = !filterFavorites"
          :class="filterFavorites ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-700'"
          class="px-4 py-2.5 rounded-lg font-medium text-sm transition"
        >
          ❤️ Favorites {{ filterFavorites ? '(On)' : '' }}
        </button>
        <Link
          :href="route('files.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition duration-150 ease-in-out shadow-md"
        >
          + Upload
        </Link>
      </div>

      <!-- Quick Links -->
      <div class="mb-6 flex flex-wrap gap-3">
        <Link
          :href="route('dashboard')"
          class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
        >
          📊 Dashboard
        </Link>
        <Link
          :href="route('files.starred')"
          class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
        >
          ❤️ Favorites
        </Link>
        <Link
          :href="route('tags.index')"
          class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
        >
          🏷️ Tags
        </Link>
      </div>

      <!-- Empty State -->
      <div v-if="filtered.length === 0" class="bg-white shadow rounded-lg p-12 text-center">
        <p class="text-3xl mb-4">📭</p>
        <p class="text-gray-600 font-medium">No files found</p>
        <p class="text-gray-500 text-sm mt-2">
          {{ filterFavorites ? 'Favorite some files to see them here' : 'Upload your first file to get started' }}
        </p>
        <Link
          v-if="!filterFavorites"
          :href="route('files.create')"
          class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition"
        >
          + Upload File
        </Link>
      </div>

      <!-- File Table -->
      <div v-else class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 uppercase text-xs font-semibold border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left">File</th>
                <th class="px-6 py-4 text-left">Size</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-center">⬇️ Downloads</th>
                <th class="px-6 py-4 text-left">Uploaded</th>
                <th class="px-6 py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="file in filtered" :key="file.id" class="hover:bg-blue-50 transition duration-150 ease-in-out">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <button
                      @click="toggleFavorite(file.id)"
                      class="text-xl hover:scale-125 transition-transform duration-200"
                      :class="favoritedFiles.has(file.id) ? 'text-pink-500 fill-current' : 'text-gray-400'"
                      title="Toggle favorite"
                    >
                      ❤️
                    </button>
                    <span class="mr-2 text-xl">{{ icon(file.mime_type) }}</span>
                    <span class="font-medium text-gray-900 truncate max-w-[250px]">
                      {{ file.original_name }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-gray-600 text-sm">{{ file.size }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span
                      class="px-3 py-1 rounded-full text-xs font-medium"
                      :class="file.is_public ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    >
                      {{ file.is_public ? '🌐 Public' : '🔒 Private' }}
                    </span>
                    <span v-if="file.password" class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                      🔐 Password
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-center font-mono font-bold text-lg text-gray-900">{{ file.download_count }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ file.created_at }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                  <button @click="copyLink(file.share_token)" class="text-indigo-600 hover:text-indigo-900 p-1 rounded transition">
                    <span class="sr-only">Share</span>
                    🔗
                  </button>
                  <Link :href="route('files.edit', file.id)" class="text-blue-600 hover:text-blue-900 p-1 rounded transition">
                    ✏️
                  </Link>
                  <button @click="destroy(file.id)" class="text-red-600 hover:text-red-900 p-1 rounded transition">
                    🗑️
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
