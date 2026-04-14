<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-2xl font-bold leading-tight text-gray-900"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-md rounded-lg"
                >
                    <div class="p-6 text-gray-700 text-lg">
                        Welcome back, <strong>{{ $page.props.auth.user.name }}</strong>! 👋
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
