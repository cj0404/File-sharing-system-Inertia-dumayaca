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
      <h2 class="text-2xl font-bold leading-tight text-gray-900">My Files</h2>
    </template>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Flash -->
      <div v-if="flash?.success" class="mb-6 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm font-medium">
        ✓ {{ flash.success }}
      </div>

      <!-- Toolbar -->
      <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <input
          v-model="search"
          placeholder="🔍 Search files…"
          class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <Link
          :href="route('files.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition duration-150 ease-in-out"
        >
          + Upload File
        </Link>
      </div>

      <!-- File Table -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
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
            <tr v-for="file in filtered" :key="file.id" class="hover:bg-gray-50 transition duration-150 ease-in-out">
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
                  {{ file.is_public ? 'Public' : 'Private' }}
                </span>
                <span v-if="file.has_password" class="ml-2 text-yellow-600 text-xs">🔒</span>
              </td>
              <td class="px-6 py-3 text-gray-600">{{ file.download_count }}</td>
              <td class="px-6 py-3 text-gray-600 text-xs">{{ file.created_at }}</td>
              <td class="px-6 py-3 text-right flex justify-end gap-2">
                <Link :href="route('files.show', file.id)" class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</Link>
                <Link :href="route('files.edit', file.id)" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Edit</Link>
                <button @click="destroy(file.id)" class="text-red-600 hover:text-red-800 font-medium text-sm">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="filtered.length === 0" class="text-center py-12 text-gray-500">
          📭 No files found
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
