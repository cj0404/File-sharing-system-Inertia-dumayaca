<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const stats = ref(null);
const loading = ref(true);

onMounted(async () => {
  try {
    const response = await axios.get('/api/stats');
    stats.value = response.data;
  } catch (error) {
    console.error('Error fetching stats:', error);
  } finally {
    loading.value = false;
  }
});

const formatBytes = (bytes) => {
  if (!bytes) return '0 B';
  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-3xl font-bold leading-tight text-gray-900">
        📊 Dashboard
      </h2>
    </template>

    <div class="py-12 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="mb-8 bg-white shadow-lg rounded-xl border border-blue-100 overflow-hidden">
          <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8">
            <h1 class="text-3xl font-bold text-white mb-2">
              Welcome back, {{ $page.props.auth.user.name }}! 👋
            </h1>
            <p class="text-blue-100">
              Manage your files, track statistics, and organize with tags
            </p>
          </div>
        </div>

        <!-- Stats Grid -->
        <div v-if="!loading && stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Files -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Files</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.total_files }}</p>
              </div>
              <div class="text-4xl">📁</div>
            </div>
          </div>

          <!-- Total Storage -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Storage</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ formatBytes(stats.total_size) }}</p>
              </div>
              <div class="text-4xl">💾</div>
            </div>
          </div>

          <!-- Total Downloads -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Downloads</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.total_downloads }}</p>
              </div>
              <div class="text-4xl">⬇️</div>
            </div>
          </div>

          <!-- Starred Files -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Starred Files</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.starred_files }}</p>
              </div>
              <div class="text-4xl">⭐</div>
            </div>
          </div>

          <!-- Public Files -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Public Files</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.public_files }}</p>
              </div>
              <div class="text-4xl">🌐</div>
            </div>
          </div>

          <!-- Private Files -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Private Files</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.private_files }}</p>
              </div>
              <div class="text-4xl">🔒</div>
            </div>
          </div>

          <!-- Files with Tags -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-pink-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Tagged Files</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.files_with_tags }}</p>
              </div>
              <div class="text-4xl">🏷️</div>
            </div>
          </div>

          <!-- Files with Comments -->
          <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">With Notes</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.files_with_comments }}</p>
              </div>
              <div class="text-4xl">📝</div>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <p class="text-gray-600">Loading statistics...</p>
        </div>

        <!-- Features Section -->
        <div class="bg-white rounded-lg shadow-md p-8 mt-8">
          <h3 class="text-2xl font-bold text-gray-900 mb-6">✨ Features</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex gap-4">
              <div class="text-2xl">📤</div>
              <div>
                <h4 class="font-semibold text-gray-900">Upload Files</h4>
                <p class="text-sm text-gray-600">Upload and manage your files securely</p>
              </div>
            </div>
            <div class="flex gap-4">
              <div class="text-2xl">⭐</div>
              <div>
                <h4 class="font-semibold text-gray-900">Star Files</h4>
                <p class="text-sm text-gray-600">Mark important files as favorites</p>
              </div>
            </div>
            <div class="flex gap-4">
              <div class="text-2xl">🏷️</div>
              <div>
                <h4 class="font-semibold text-gray-900">Organize with Tags</h4>
                <p class="text-sm text-gray-600">Categorize files with custom tags</p>
              </div>
            </div>
            <div class="flex gap-4">
              <div class="text-2xl">📝</div>
              <div>
                <h4 class="font-semibold text-gray-900">Add Notes</h4>
                <p class="text-sm text-gray-600">Add comments and notes to files</p>
              </div>
            </div>
            <div class="flex gap-4">
              <div class="text-2xl">🔐</div>
              <div>
                <h4 class="font-semibold text-gray-900">Secure Sharing</h4>
                <p class="text-sm text-gray-600">Share files with password protection</p>
              </div>
            </div>
            <div class="flex gap-4">
              <div class="text-2xl">📊</div>
              <div>
                <h4 class="font-semibold text-gray-900">Track Analytics</h4>
                <p class="text-sm text-gray-600">Monitor file downloads and views</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
