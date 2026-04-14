<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Link Expired" />
  <div class="min-h-screen bg-gray-950 flex items-center justify-center text-white">
    <div class="text-center">
      <p class="text-5xl mb-4">⏰</p>
      <h1 class="text-2xl font-bold mb-2">Link Expired</h1>
      <p class="text-gray-400 text-sm mb-6">This file link has expired and is no longer accessible.</p>
      <Link :href="route('home')" class="text-indigo-400 hover:underline text-sm">← Back to home</Link>
    </div>
  </div>
</template>
