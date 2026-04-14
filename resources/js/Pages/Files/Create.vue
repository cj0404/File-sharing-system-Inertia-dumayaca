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
      <h2 class="text-2xl font-bold leading-tight text-gray-900">Upload File</h2>
    </template>

    <div class="py-8 max-w-xl mx-auto px-4">
      <div class="bg-white rounded-lg shadow p-6">

        <!-- Drop Zone -->
        <div
          class="border-2 border-dashed rounded-lg p-12 text-center cursor-pointer transition mb-6"
          :class="dragOver ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
          @dragover.prevent="dragOver = true"
          @dragleave="dragOver = false"
          @drop.prevent="e => { dragOver = false; onFile(e) }"
          @click="$refs.fileInput.click()"
        >
          <p class="text-gray-700 text-sm font-medium">{{ fileLabel }}</p>
          <input ref="fileInput" type="file" class="hidden" @change="onFile" />
        </div>
        <div v-if="form.errors.file" class="text-red-600 text-sm mb-4 bg-red-50 p-3 rounded">{{ form.errors.file }}</div>

        <!-- Options -->
        <div class="space-y-4">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" v-model="form.is_public" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" />
            <span class="text-sm text-gray-700 font-medium">Make publicly accessible</span>
          </label>

          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Password (optional)</label>
            <input
              v-model="form.password"
              type="password"
              placeholder="Leave blank for no password"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-900 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Expiry date (optional)</label>
            <input
              v-model="form.expires_at"
              type="datetime-local"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
        </div>

        <button
          @click="submit"
          :disabled="form.processing || !form.file"
          class="mt-6 w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-2.5 rounded-lg transition duration-150 ease-in-out"
        >
          {{ form.processing ? 'Uploading…' : 'Upload File' }}
        </button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
