<script setup lang="ts">
</script>

<template>
    <div class="flex flex-col h-svh md:flex-row">
        <!-- Sidebar (desktop) / Bottom bar (mobile) -->
        <aside class="
            fixed bottom-0 left-0 right-0 z-50
            bg-brand-50 border-t border-brand-300
            flex flex-row items-center justify-around px-4 py-2
            md:static md:w-64 md:flex-col md:items-start md:gap-6
            md:border-t-0 md:border-r md:p-4
        ">
            <div class="hidden md:block text-lg font-semibold text-brand-500">Meal Planner</div>
            <nav class="flex flex-row gap-1 md:flex-col">
                <a
                    href="/"
                    class="flex flex-col items-center gap-0.5 px-4 py-1 rounded text-xs md:text-sm md:flex-row md:gap-0 md:px-3 md:py-2 hover:bg-brand-100">
                    Home
                </a>
            </nav>
            <nav class="flex md:mt-auto">
                <a
                    href="/logout"
                    class="flex flex-col items-center gap-0.5 px-4 py-1 rounded text-xs md:text-sm md:flex-row md:gap-0 md:px-3 md:py-2 hover:bg-brand-100">
                    Log Out
                </a>
            </nav>
        </aside>
        <main class="flex-1 p-6 pb-20 md:pb-6">
            <slot />
        </main>
    </div>
</template>
