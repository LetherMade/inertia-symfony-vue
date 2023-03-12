<template>
    <Head><title>Create Car</title></Head>

    <h1 class="text-4xl text-teal-500 font-bold mb-8">Create a Car</h1>

    <form class="space-y-8 divide-y divide-gray-200" @submit.prevent="submit">
        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
            <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                <div class="space-y-6 sm:space-y-5">
                    <div class="sm:border-t sm:border-gray-200 sm:pt-5">
                        <Input
                            name="name"
                            v-model="form.name"
                            place-holder="Mazda CX-5"
                            auto-complete="car-name"
                            :error="form.errors.name"
                        />
                    </div>
                    <div class="sm:border-t sm:border-gray-200 sm:pt-5">
                        <Input
                            name="price"
                            v-model="form.price"
                            type="number"
                            auto-complete="car-price"
                            :error="form.errors.price"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-5">
            <div class="flex justify-end gap-x-3">
                <button type="button"
                        class="rounded-md bg-white py-2 px-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="inline-flex justify-center rounded-md bg-teal-500 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Save
                </button>
            </div>
        </div>
    </form>
</template>

<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import {generateRoute} from "../../Utils/Routing";
import Input from "../../Components/Input.vue";

const form = useForm({
    name: '',
    price: 0
})

const submit = () => {
    form.post(generateRoute('cars_store'), {
        preserveState: true
    });
}
</script>
