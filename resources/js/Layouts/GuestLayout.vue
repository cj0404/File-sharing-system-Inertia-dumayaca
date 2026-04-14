<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gray-50 px-4 py-6 sm:px-6 lg:px-8"
    >
        <div class="mb-8">
            <Link href="/">
                <ApplicationLogo class="h-16 w-16 fill-current text-black" />
            </Link>
        </div>

        <div
            class="w-full max-w-md overflow-hidden rounded-lg bg-white p-8 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05]"
        >
            <slot />
        </div>
    </div>
</template>
