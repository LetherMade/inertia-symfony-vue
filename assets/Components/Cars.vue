<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div v-for="car in cars" :key="car.id"
             class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
            <CarItem :car="car"/>
        </div>
    </div>
</template>

<script setup lang="ts">

import CarItem from "./CarItem.vue";

defineProps<{
    cars: ICar[]
}>()
</script>