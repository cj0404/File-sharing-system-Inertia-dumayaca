<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import axios from 'axios'

const tags = ref([])
const newTagName = ref('')
const newTagColor = ref('blue')
const loading = ref(false)
const selectedTag = ref(null)

const colors = ['blue', 'green', 'red', 'yellow', 'purple', 'pink', 'indigo', 'emerald']

const colorMap = {
  blue: 'bg-blue-100 text-blue-800 border-blue-300',
  green: 'bg-green-100 text-green-800 border-green-300',
  red: 'bg-red-100 text-red-800 border-red-300',
  yellow: 'bg-yellow-100 text-yellow-800 border-yellow-300',
  purple: 'bg-purple-100 text-purple-800 border-purple-300',
  pink: 'bg-pink-100 text-pink-800 border-pink-300',
  indigo: 'bg-indigo-100 text-indigo-800 border-indigo-300',
  emerald: 'bg-emerald-100 text-emerald-800 border-emerald-300',
}

onMounted(async () => {
  await fetchTags()
})

async function fetchTags() {
  try {
    const response = await axios.get('/api/tags')
    tags.value = response.data
  } catch (error) {
    console.error('Error fetching tags:', error)
    alert('Error fetching tags: ' + (error.response?.data?.message || error.message))
  }
}

async function createTag() {
  if (!newTagName.value.trim()) return

  loading.value = true
  try {
    await axios.post('/api/tags', {
      name: newTagName.value,
      color: newTagColor.value
    })
    newTagName.value = ''
    newTagColor.value = 'blue'
    await fetchTags()
  } catch (error) {
    console.error('Error creating tag:', error)
    alert('Error creating tag: ' + (error.response?.data?.message || error.message))
  } finally {
    loading.value = false
  }
}

async function selectTag(tag) {
  if (selectedTag.value?.id === tag.id) {
    selectedTag.value = null
    return
  }
  selectedTag.value = tag
  try {
    const response = await axios.get(`/api/tags/${tag.id}/files`)
    tag.files = response.data
  } catch (error) {
    console.error('Error fetching tag files:', error)
  }
}
</script>

<template>
  <Head title="Tags &amp; Categories" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-bold leading-tight text-gray-900">🏷️ Tags &amp; Categories</h2>
    </template>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Create Tag Section -->
      <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Create New Tag</h3>
        <div class="flex flex-col sm:flex-row gap-3">
          <input
            v-model="newTagName"
            type="text"
            placeholder="Enter tag name (e.g., Work, Personal)…"
            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
          <select
            v-model="newTagColor"
            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            <option v-for="color in colors" :key="color" :value="color">
              {{ color.charAt(0).toUpperCase() + color.slice(1) }}
            </option>
          </select>
          <button
            @click="createTag"
            :disabled="loading"
            class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-semibold px-6 py-2 rounded-lg transition"
          >
            {{ loading ? 'Creating...' : '+ Create' }}
          </button>
        </div>
      </div>

      <!-- Tags List -->
      <div v-if="tags.length === 0" class="bg-white shadow rounded-lg p-12 text-center">
        <p class="text-2xl mb-4">🏷️</p>
        <p class="text-gray-600 font-medium">No tags created yet</p>
        <p class="text-gray-500 text-sm mt-2">Create tags to organize your files</p>
      </div>

      <div v-else class="space-y-4">
        <div
          v-for="tag in tags"
          :key="tag.id"
          class="bg-white shadow rounded-lg overflow-hidden hover:shadow-md transition"
        >
          <!-- Tag Header -->
          <button
            @click="selectTag(tag)"
            class="w-full text-left p-6 cursor-pointer"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <span
                  class="px-4 py-2 rounded-full text-sm font-semibold border-2"
                  :class="colorMap[tag.color] || colorMap.blue"
                >
                  {{ tag.name }}
                </span>
              </div>
              <span class="text-gray-400">
                {{ selectedTag?.id === tag.id ? '▼' : '▶' }}
              </span>
            </div>
          </button>
        </div>
      </div>

      <!-- Back Link -->
      <div class="mt-8">
        <Link
          :href="route('files.index')"
          class="text-indigo-600 hover:text-indigo-900 font-medium"
        >
          ← Back to Files
        </Link>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
