<template>
    <div
        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4">
        <label
            :for="name"
            class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
            :class="{'text-red-500': !!error}"
        >{{ capitalize(name) }}</label>
        <div class="mt-2 sm:col-span-2 sm:mt-0">
            <div class="relative w-full max-w-lg sm:max-w-xs">
                <input
                    :type="type"
                    :name="name"
                    :id="name"
                    :value="modelValue"
                    :model-value="modelValue"
                    @input="emit('update:modelValue', $event.target.value)"
                    :autocomplete="autoComplete ?? name"
                    :placeholder="placeHolder"
                    class="block w-full max-w-lg rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                    :class="{'ring-red-500 text-red-900 focus:ring-red-600 placeholder:text-red-300': !!error}"
                />
                <div v-if="!!error" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true"/>
                </div>
            </div>
            <span v-if="error" :id="`${name}-error`" class="text-xs text-red-500 mt-1" v-text="error"/>
        </div>
    </div>
</template>

<script setup lang="ts">
import {capitalize} from "@vue/shared";
import {ExclamationCircleIcon} from "@heroicons/vue/24/solid";

withDefaults(defineProps<{
    name: string,
    type?: string,
    modelValue: any,
    autoComplete?: string,
    placeHolder?: string,
    error?: string,
}>(), {
    type: 'text'
})

const emit = defineEmits(['update:modelValue'])

</script>
