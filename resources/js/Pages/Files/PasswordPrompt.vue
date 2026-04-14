<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ token: String, error: String })
const pwd   = ref('')
</script>

<template>
  <Head title="Protected File" />
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-4">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 w-full max-w-sm text-white">
      <h1 class="text-xl font-bold mb-2">🔒 Protected File</h1>
      <p class="text-gray-400 text-sm mb-6">This file is password protected. Enter the password to download.</p>

      <form :action="`/share/${token}`" method="GET">
        <input
          v-model="pwd"
          name="pwd"
          type="password"
          placeholder="Enter password…"
          class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500 mb-3"
          required
          autofocus
        />
        <p v-if="error" class="text-red-400 text-xs mb-3">{{ error }}</p>
        <button
          type="submit"
          class="w-full bg-indigo-600 hover:bg-indigo-500 transition font-semibold py-2.5 rounded-lg text-sm"
        >
          Access File
        </button>
      </form>
    </div>
  </div>
</template>
