<script setup lang="ts">
</script>

<template>
    <div class="flex min-h-screen">
        <aside class="w-64 bg-brand-50 border-r border-brand-300 p-4 flex flex-col gap-6">
            <div class="text-lg font-semibold text-brand-500">Meal Planner</div>
            <nav class="flex flex-col gap-1">
                <a
                    href="/"
                    class="px-3 py-2 rounded text-sm hover:bg-brand-100">
                    Home
                </a>
            </nav>
            <nav class="flex mt-auto">
                <a
                    href="/logout"
                    class="px-3 py-2 rounded text-sm hover:bg-brand-100">
                    Log Out
                </a>
            </nav>
        </aside>
        <main class="flex-1 p-6">
            <slot />
        </main>
    </div>
</template>
