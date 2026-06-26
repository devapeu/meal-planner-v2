<script setup lang="ts">
import {ShoppingListItem} from "@/types";
import ShoppingList from "@/Components/ShoppingList/ShoppingList.vue";

defineProps<{
    shoppingList: ShoppingListItem[]
}>();
</script>
<template>
    <ShoppingList :shopping-list="shoppingList" />
</template>
