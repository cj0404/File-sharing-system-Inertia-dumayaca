<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const publicFiles = ref([])

</script>

<template>
  <Head title="FileShare" />

  <div class="min-h-screen bg-gray-950 text-white">
    <nav class="border-b border-gray-800 px-6 py-4 flex items-center justify-between">
      <span class="text-xl font-bold tracking-tight">⬡ FileShare</span>
      <div class="flex gap-4">
        <Link :href="route('login')" class="text-gray-400 hover:text-white transition">Log in</Link>
        <Link :href="route('register')" class="bg-indigo-600 hover:bg-indigo-500 transition px-4 py-1.5 rounded-lg text-sm font-medium">
          Get started
        </Link>
      </div>
    </nav>

    <section class="max-w-4xl mx-auto px-6 py-24 text-center">
      <h1 class="text-5xl font-extrabold tracking-tight mb-6 leading-tight">
        Share files.<br/>
        <span class="text-indigo-400">Stay secure.</span>
      </h1>

    <p class="text-gray-400 text-lg mb-10 max-w-xl mx-auto">
        Upload any file, generate a shareable link, and control access with passwords and expiry dates.
    </p>
    <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-8 mb-12 border border-gray-800/50">
        <h3 class="text-2xl font-bold mb-6 text-indigo-400">🚀 Demo Accounts (for testing)</h3>
        <div class="grid md:grid-cols-2 gap-6 text-left">
            <div>
                <h4 class="font-bold text-lg mb-2">👩 Alice</h4>
                <p class="text-gray-300 mb-1">Email: alice@example.com</p>
                <p class="text-gray-300">Password: password</p>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-2">👨 Bob</h4>
                <p class="text-gray-300 mb-1">Email: bob@example.com</p>
                <p class="text-gray-300">Password: password</p>
            </div>
        </div>
    </div>

      <Link :href="route('register')" class="bg-indigo-600 hover:bg-indigo-500 transition text-white font-semibold px-8 py-3 rounded-xl text-lg">
        Start sharing for free →
      </Link>
    </section>
  </div>
</template>
