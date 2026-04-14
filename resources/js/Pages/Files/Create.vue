<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
  file:       null,
  is_public:  false,
  password:   '',
  expires_at: '',
})

const dragOver  = ref(false)
const fileLabel = ref('Drop file here or click to browse')

function onFile(e) {
  const f = e.target.files[0] || e.dataTransfer?.files[0]
  if (f) { form.file = f; fileLabel.value = f.name }
}

function submit() {
  form.post(route('files.store'), { forceFormData: true })
}
</script>

<template>
  <Head title="Upload File" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Upload File</h2>
    </template>

    <div class="py-8 max-w-xl mx-auto px-4">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

        <!-- Drop Zone -->
        <div
          class="border-2 border-dashed rounded-xl p-10 text-center cursor-pointer transition mb-6"
          :class="dragOver ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600'"
          @dragover.prevent="dragOver = true"
          @dragleave="dragOver = false"
          @drop.prevent="e => { dragOver = false; onFile(e) }"
          @click="$refs.fileInput.click()"
        >
          <p class="text-gray-500 dark:text-gray-400 text-sm">{{ fileLabel }}</p>
          <input ref="fileInput" type="file" class="hidden" @change="onFile" />
        </div>
        <div v-if="form.errors.file" class="text-red-500 text-xs mb-4">{{ form.errors.file }}</div>

        <!-- Options -->
        <div class="space-y-4">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" v-model="form.is_public" class="w-4 h-4 text-indigo-600 rounded" />
            <span class="text-sm text-gray-700 dark:text-gray-300">Make publicly accessible</span>
          </label>

          <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Password (optional)</label>
            <input
              v-model="form.password"
              type="password"
              placeholder="Leave blank for no password"
              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
          </div>

          <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Expiry date (optional)</label>
            <input
              v-model="form.expires_at"
              type="datetime-local"
              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
          </div>
        </div>

        <button
          @click="submit"
          :disabled="form.processing || !form.file"
          class="mt-6 w-full bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-semibold py-2.5 rounded-lg transition"
        >
          {{ form.processing ? 'Uploading…' : 'Upload File' }}
        </button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
