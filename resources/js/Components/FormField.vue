<script setup lang="ts">
import { useId } from 'vue'

const props = defineProps<{
    label: string
    modelValue: string
    type?: string
    placeholder?: string
    disabled?: boolean
    required?: boolean
    error?: string
}>()

defineEmits<{
    'update:modelValue': [value: string]
}>()

const id = useId()
</script>

<template>
    <div>
        <label
            class="block"
            :for="id">{{ label }}</label>
        <input
            :id="id"
            :type="type ?? 'text'"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :required="required"
            class="bg-white w-full px-3 py-2 rounded border border-brand-200"
            @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        />
        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>
