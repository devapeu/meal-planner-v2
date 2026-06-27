<script setup lang="ts">
import { ref, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import { EditPencil, Trash } from '@iconoir/vue';
import { ShoppingListItem } from '@/types';

const props = defineProps<{
    item: ShoppingListItem;
}>();

const editing = ref(false);
const editingValue = ref('');
const editInput = ref<HTMLInputElement | null>(null);

async function startEdit() {
    editing.value = true;
    editingValue.value = props.item.item;
    await nextTick();
    editInput.value?.focus();
}

function commitEdit() {
    const trimmed = editingValue.value.trim();
    if (!trimmed || trimmed === props.item.item) {
        cancelEdit();
        return;
    }
    router.put(`/shopping-list/${props.item.id}`, { item: trimmed }, { preserveScroll: true });
    editing.value = false;
}

function cancelEdit() {
    editing.value = false;
    editingValue.value = '';
}

function deleteItem() {
    router.delete(`/shopping-list/${props.item.id}`, { preserveScroll: true });
}
</script>

<template>
    <li class="flex items-center gap-2 py-2 px-4 border-b border-b-slate-300 last:border-0">
        <span class="drag-handle cursor-grab active:cursor-grabbing text-gray-400 select-none">⠿</span>
        <template v-if="editing">
            <input
                ref="editInput"
                v-model="editingValue"
                class="flex-1 border border-brand-300 rounded p-0 pl-1.5 -ml-1.5 h-7"
                @keyup.enter="commitEdit"
                @keyup.escape="cancelEdit"
                @blur="commitEdit"
            />
        </template>
        <template v-else>
            <span class="flex-1 flex items-center h-7 whitespace-nowrap overflow-hidden">{{ item.item }}</span>
            <button
                class="text-gray-400 hover:text-brand-600"
                @click="startEdit">
                <EditPencil class="w-4 h-4" />
            </button>
            <button
                class="text-gray-400 hover:text-red-500"
                @click="deleteItem">
                <Trash class="w-4 h-4" />
            </button>
        </template>
    </li>
</template>

<style lang="sass">
.sortable-fallback
    rotate: 2deg !important
    transform-origin: top left !important
    box-shadow: 4px 6px 16px rgba(0, 0, 0, 0.15) !important
    opacity: 1 !important
    background: #fff !important
    list-style: none !important
    margin: 0 !important
</style>
