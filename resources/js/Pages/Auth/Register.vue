<script setup lang="ts">
import {useForm} from "@inertiajs/vue3";
import FormField from "@/Components/FormField.vue";

defineOptions({ layout: '' })

const form = useForm({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
})

function handleRegister() {
    form.post('/register')
}
</script>

<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-8 min-w-md shadow-lg">
            <h1 class="text-center text-2xl font-semibold">Register</h1>
            <form
                class="grid gap-2"
                @submit.prevent="handleRegister">
                <FormField
                    v-model="form.username"
                    label="Username"
                    placeholder="Enter your username"
                    :disabled="form.processing"
                    :error="form.errors.username"
                    required/>
                <FormField
                    v-model="form.email"
                    label="Email"
                    type="email"
                    placeholder="Enter your email"
                    :disabled="form.processing"
                    :error="form.errors.email"
                    required/>
                <FormField
                    v-model="form.password"
                    label="Password"
                    type="password"
                    placeholder="Choose a password"
                    :disabled="form.processing"
                    :error="form.errors.password"
                    required/>
                <FormField
                    v-model="form.password_confirmation"
                    label="Confirm password"
                    type="password"
                    placeholder="Confirm your password"
                    :disabled="form.processing"
                    :error="form.errors.password_confirmation"
                    required/>
                <button
                    class="bg-brand-500 text-white p-2 rounded cursor-pointer"
                    type="submit">
                    {{ form.processing ? 'Loading...' : ' Register' }}
                </button>
                <p class="text-sm">Already have an account? <a class="text-brand-400" href="/login">Login</a>.</p>
            </form>
        </div>
    </div>
</template>
