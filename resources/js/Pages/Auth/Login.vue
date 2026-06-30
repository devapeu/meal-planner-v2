<script setup lang="ts">
import {useForm} from "@inertiajs/vue3";
import FormField from "@/Components/FormField.vue";

defineOptions({ layout: '' })

const form = useForm({
    username: '',
    password: '',
})

function handleLogin() {
    form.post('/login');
}
</script>

<template>
    <div class="flex items-center justify-center min-h-svh p-4">
        <div class="bg-white rounded-lg p-8 w-full md:max-w-md shadow-lg">
            <h1 class="text-center text-2xl font-semibold">Login</h1>
            <form
                class="grid gap-2"
                @submit.prevent="handleLogin">
                <FormField
                    v-model="form.username"
                    label="Username"
                    placeholder="Enter your username"
                    :disabled="form.processing"
                    :error="form.errors.username"
                    required/>
                <FormField
                    v-model="form.password"
                    label="Password"
                    type="password"
                    placeholder="Enter your password"
                    :disabled="form.processing"
                    :error="form.errors.password"
                    required/>
                <button
                    class="bg-brand-500 text-white p-2 rounded cursor-pointer"
                    type="submit">
                    {{ form.processing ? 'Loading...' : ' Login' }}
                </button>
                <p class="text-sm mt-1 text-gray-500">Don't have an account? <a class="text-brand-400" href="/register">Register</a>.</p>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
