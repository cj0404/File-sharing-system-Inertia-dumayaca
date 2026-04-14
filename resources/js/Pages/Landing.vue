<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

const publicFiles = ref([])
const search = ref('')
const loading = ref(false)

const fetchFiles = async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/files', { params: { search: search.value } })
    publicFiles.value = res.data.data
  } finally {
    loading.value = false
  }
}

fetchFiles()
</script>

<template>
  <Head>
    <title>FileShare — Secure File Sharing</title>
    <meta name="description" content="Upload and share files securely with FileShare. Fast, private, and reliable file sharing for everyone." />
    <meta name="keywords" content="file sharing, secure upload, share files online, private file storage" />
  </Head>

  <div class="min-h-screen bg-gray-950 text-white">
    <!-- NAV -->
    <nav class="border-b border-gray-800 px-6 py-4 flex items-center justify-between">
      <span class="text-xl font-bold tracking-tight">⬡ FileShare</span>
      <div class="flex gap-4">
        <Link :href="route('login')"    class="text-gray-400 hover:text-white transition">Log in</Link>
        <Link :href="route('register')" class="bg-indigo-600 hover:bg-indigo-500 transition px-4 py-1.5 rounded-lg text-sm font-medium">
          Get started
        </Link>
      </div>
    </nav>

    <!-- HERO -->
    <section class="max-w-4xl mx-auto px-6 py-24 text-center">
      <h1 class="text-5xl font-extrabold tracking-tight mb-6 leading-tight">
        Share files.<br/>
        <span class="text-indigo-400">Stay secure.</span>
      </h1>
      <p class="text-gray-400 text-lg mb-10 max-w-xl mx-auto">
        Upload any file, generate a shareable link, and control access with passwords and expiry dates.
      </p>
      <Link :href="route('register')" class="bg-indigo-600 hover:bg-indigo-500 transition text-white font-semibold px-8 py-3 rounded-xl text-lg">
        Start sharing for free →
      </Link>
    </section>

    <!-- PUBLIC FILES via API + Axios -->
    <section class="max-w-5xl mx-auto px-6 pb-24">
      <h2 class="text-2xl font-bold mb-6">Publicly shared files</h2>
      <div class="mb-6 flex gap-3">
        <input
          v-model="search"
          @input="fetchFiles"
          placeholder="Search public files..."
          class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500"
        />
      </div>

      <div v-if="loading" class="text-gray-500 text-sm">Loading…</div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="file in publicFiles"
          :key="file.id"
          class="bg-gray-900 border border-gray-800 rounded-xl p-4 hover:border-indigo-500 transition"
        >
          <p class="font-medium truncate">{{ file.name }}</p>
          <p class="text-sm text-gray-500 mt-1">{{ file.size }} · {{ file.download_count }} downloads</p>
          <p class="text-xs text-gray-600 mt-1">by {{ file.owner }} · {{ file.created_at }}</p>
          <a
            :href="file.share_url"
            class="mt-3 block text-center text-xs bg-indigo-600 hover:bg-indigo-500 transition py-1.5 rounded-lg"
          >
            Download
          </a>
        </div>

        <div v-if="publicFiles.length === 0" class="col-span-3 text-center text-gray-600 py-12">
          No public files found.
        </div>
      </div>
    </section>
  </div>
</template>
