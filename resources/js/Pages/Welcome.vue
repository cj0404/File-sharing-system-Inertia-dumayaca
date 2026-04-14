<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <nav class="border-b border-gray-200 bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600">📁 FileShare</span>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:gap-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition"
                            >
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 mb-6">
                    Secure File Sharing, Made Simple
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Upload, organize, and share your files with powerful features like tagging, comments, and access controls.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md"
                    >
                        Go to Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('register')"
                            class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md"
                        >
                            Get Started
                        </Link>
                        <Link
                            :href="route('login')"
                            class="inline-block px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition"
                        >
                            Log In
                        </Link>
                    </template>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">✨ Features</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-4xl mb-4">📤</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Upload</h3>
                        <p class="text-gray-600">Drag and drop or click to upload. Support for all file types with secure storage.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-4xl mb-4">🔐</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Sharing</h3>
                        <p class="text-gray-600">Share with password protection, expiry dates, and granular access controls.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-4xl mb-4">⭐</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Star Favorites</h3>
                        <p class="text-gray-600">Mark important files as favorites for quick access and easy organization.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-4xl mb-4">🏷️</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tags & Categories</h3>
                        <p class="text-gray-600">Organize files with custom tags in different colors for better categorization.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-4xl mb-4">📝</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Comments & Notes</h3>
                        <p class="text-gray-600">Add notes and comments to files for better collaboration and documentation.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-4xl mb-4">📊</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Analytics</h3>
                        <p class="text-gray-600">Track downloads, storage usage, and file statistics in real-time dashboard.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-blue-600 mb-2">100MB</div>
                        <p class="text-gray-600">Max file size per upload</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-blue-600 mb-2">Unlimited</div>
                        <p class="text-gray-600">Files & Storage</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-blue-600 mb-2">100%</div>
                        <p class="text-gray-600">Secure & Private</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-blue-600">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold text-white mb-6">Ready to get started?</h2>
                <p class="text-xl text-blue-100 mb-8">Join us and start sharing files securely today.</p>
                <Link
                    v-if="!$page.props.auth.user"
                    :href="route('register')"
                    class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition shadow-md"
                >
                    Create Free Account
                </Link>
                <Link
                    v-else
                    :href="route('dashboard')"
                    class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition shadow-md"
                >
                    Go to Dashboard
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-8 bg-gray-50 border-t border-gray-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center text-gray-600">
                <p>&copy; 2026 FileShare. All rights reserved. Built with Laravel, Vue 3 & Inertia.js</p>
            </div>
        </footer>
    </div>
</template>
