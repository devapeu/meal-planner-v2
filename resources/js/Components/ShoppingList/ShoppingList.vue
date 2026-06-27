<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import draggable from 'vuedraggable';
import ShoppingListItem from '@/Components/ShoppingList/ShoppingListItem.vue';
import { ShoppingListItem as ShoppingListItemType } from '@/types';

const props = defineProps<{
    shoppingList: ShoppingListItemType[];
}>();

const items = ref([...props.shoppingList]);
const adding = ref(false);
const newItemValue = ref('');
const newItemInput = ref<HTMLInputElement | null>(null);

watch(() => props.shoppingList, (val) => {
    items.value = [...val];
});

async function startAdding() {
    adding.value = true;
    await nextTick();
    newItemInput.value?.focus();
}

function commitAdd() {
    const trimmed = newItemValue.value.trim();
    if (!trimmed) {
        cancelAdd();
        return;
    }
    router.post('/shopping-list', { item: trimmed }, { preserveScroll: true });
    newItemValue.value = '';
    adding.value = false;
}

function cancelAdd() {
    adding.value = false;
    newItemValue.value = '';
}

function onReorder() {
    router.post('/shopping-list/reorder', {
        items: items.value.map(({ id }) => ({ id })),
    }, { preserveScroll: true });
}
</script>

<template>
    <div class="bg-white border border-brand-300 rounded pb-1">
        <div id="shopping-list-title">
            <span>Shopping List</span>
            <button
                class="inline-block mb-1 ml-auto text-sm border border-brand-300 bg-brand-50 hover:bg-brand-100 px-2 py-1 rounded cursor-pointer"
                @click="startAdding">
                + Add new
            </button>
        </div>
        <ul v-if="adding">
            <li class="flex items-center gap-2 py-2 px-4">
                <input
                    ref="newItemInput"
                    v-model="newItemValue"
                    placeholder="New item..."
                    class="flex-1 border border-brand-300 rounded px-2 py-0.5 text-sm"
                    @keyup.enter="commitAdd"
                    @keyup.escape="cancelAdd"
                    @blur="commitAdd"
                />
            </li>
        </ul>
        <draggable
            v-model="items"
            tag="ul"
            item-key="id"
            handle=".drag-handle"
            :force-fallback="true"
            @end="onReorder"
        >
            <template #item="{ element }">
                <ShoppingListItem :item="element" />
            </template>
        </draggable>
        <div
            v-if="items.length === 0 && !adding"
            class="m-5 mt-4 p-5 border border-gray-200 bg-gray-50 rounded text-gray-500 text-center">
            No items yet
        </div>
    </div>
</template>

<style scoped lang="sass">
#shopping-list-title
    padding: 16px 16px 5px
    display: flex
    flex-wrap: wrap
    align-items: center
    span
        margin: 0 8px
        font-weight: 600
        font-size: 18px
    &:after
        content: ""
        height: 1px
        border-top: 2px dotted var(--color-brand-500)
        width: 100%
        margin: 5px auto 0
        display: block
</style>
