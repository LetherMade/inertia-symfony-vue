<template>
    <div>
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="relative z-40 lg:hidden" @close="sidebarOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                                 enter-from="opacity-0" enter-to="opacity-100"
                                 leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                                 leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75"/>
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                     enter-from="-translate-x-full" enter-to="translate-x-0"
                                     leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                     leave-to="-translate-x-full">
                        <DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-gray-800">
                            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                                             enter-to="opacity-100" leave="ease-in-out duration-300"
                                             leave-from="opacity-100" leave-to="opacity-0">
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button type="button"
                                            class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                            @click="sidebarOpen = false">
                                        <span class="sr-only">Close sidebar</span>
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                                <div class="flex flex-shrink-0 items-center px-4">
                                    <img class="h-8 w-auto"
                                         src="https://www.lethermade.nl/assets/logo-dark.svg"
                                         alt="LetherMade"/>
                                </div>
                                <nav class="mt-5 space-y-1 px-2">
                                    <a v-for="item in navigation" :key="item.name" :href="item.href"
                                       :class="[item.component === $page.component ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'group flex items-center rounded-md px-2 py-2 text-base font-medium']">
                                        <component :is="item.icon"
                                                   :class="[item.component === $page.component ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300', 'mr-4 h-6 w-6 flex-shrink-0']"
                                                   aria-hidden="true"/>
                                        {{ item.name }}
                                    </a>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="w-14 flex-shrink-0">
                        <!-- Force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex min-h-0 flex-1 flex-col bg-gray-800">
                <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <img class="h-16 w-auto m-auto" src="https://www.lethermade.nl/assets/logo-dark.svg"
                             alt="LetherMade"/>
                    </div>
                    <nav class="mt-5 flex-1 space-y-1 px-2">
                        <Link v-for="item in navigation" :key="item.name" :href="item.href"
                              :class="[item.component === $page.component ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'group flex items-center rounded-md px-2 py-2 text-sm font-medium']">
                            <component :is="item.icon"
                                       :class="[item.component === $page.component ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300', 'mr-3 h-6 w-6 flex-shrink-0']"
                                       aria-hidden="true"/>
                            {{ item.name }}
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
        <div class="flex flex-1 flex-col lg:pl-64">
            <div class="sticky top-0 z-10 bg-gray-100 pl-1 pt-1 sm:pl-3 sm:pt-3 lg:hidden">
                <button type="button"
                        class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                        @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true"/>
                </button>
            </div>
            <main class="flex-1">
                <div class="py-6">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <slot/>
                    </div>
                </div>
            </main>
        </div>

        <Teleport to="body">
            <FlashMessages />
        </Teleport>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue'
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {Link} from "@inertiajs/vue3";
import {Bars3Icon, HomeIcon, PlusCircleIcon, XMarkIcon,} from '@heroicons/vue/24/outline'
import FlashMessages from "./FlashMessages.vue";

const navigation = [
    {name: 'Cars', href: '/', icon: HomeIcon, component: 'Cars/CarsList' },
    {name: 'Create Car', href: '/create', icon: PlusCircleIcon, component: 'Cars/CarsCreate'},
]

const sidebarOpen = ref(false)
</script>