<template>
    <div class="min-w-0 flex-1 flex items-center w-full">
        <div class="flex-grow">
            <p class="text-sm font-medium text-gray-900">{{ car.name }}</p>
            <p class="truncate text-sm text-gray-500">{{ price }}</p>
        </div>
        <Link :href="`/${car.id}/edit`" class="p-2 rounded-full hover:bg-teal-100 cursor-pointer">
            <PencilIcon class="w-6 h-6"/>
        </Link>
    </div>
</template>

<script setup lang="ts">
import {computed} from "vue";
import {PencilIcon} from "@heroicons/vue/24/outline";
import {Link} from '@inertiajs/vue3'

const props = defineProps<{
    car: ICar
}>()

const price = computed(
    () => {
        return new Intl.NumberFormat('nl-NL', {
            style: 'currency',
            currency: 'EUR'
        }).format(props.car.price)
    }
)
</script>
