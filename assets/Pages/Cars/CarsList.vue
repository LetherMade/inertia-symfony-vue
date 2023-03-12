<template>
    <div>
        <Head><title>All Cars</title></Head>

        <h1 class="text-4xl font-bold text-teal-500 mb-8">Cars</h1>
        <div class="pt-4 pb-8 mb-8 border-y border-y-gray-300">
            <h3 class="mb-4 text-xl font-bold">Filters</h3>
            <div class="space-y-4">
                <Input v-model="form.search" name="search" place-holder="Search..." @update:modelValue="search"/>
                <Input type="number" v-model="form.minPrice" name="minPrice" @update:modelValue="search"/>
                <Input type="number" v-model="form.maxPrice" name="maxPrice" @update:modelValue="search"/>
            </div>
        </div>
        <Cars :cars="cars.data"/>
        <Pagination :links="cars.links"/>
    </div>
</template>

<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import Cars from "../../Components/Cars.vue";
import Pagination from "../../Components/Pagination.vue";
import Input from "../../Components/Input.vue";
import {debounce} from "lodash";
import {generateRoute} from "../../Utils/Routing";

interface IFilter {
    search: string
    minPrice: number
    maxPrice: number
}

const props = defineProps<{
    filters: IFilter,
    cars: IPaginator<ICar>
}>()

const form = useForm({
    search: props.filters?.search,
    minPrice: props.filters?.minPrice,
    maxPrice: props.filters?.maxPrice
})

const search = debounce(() => {
    form.get(generateRoute('cars'), {preserveState: true})
}, 500);
</script>
