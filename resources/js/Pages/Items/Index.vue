<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head ,Link } from '@inertiajs/vue3';
    import FlashMessage from '@/Components/FlashMessage.vue';

    defineProps({
        items: Array,
    });

</script>

<template>
    <Head title="商品一覧" />

    <AuthenticatedLayout>
        <template #header>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">商品一覧</h2>
        </template>

        <div class="py-12">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <FlashMessage/>
                    <div class="p-6 text-gray-900">
                        <div class="ml-24 mb-8">
                            <Link as="button" :href="route('items.create')" class="w-32 h-8 bg-indigo-500 text-sm text-white ml-0 hover:bg-indigo-600 rounded">商品登録</Link>
                        </div>
                        <div class=" mx-auto w-full sm:px-4 lg:px-4 border ">

                        <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                            <thead>
                                <tr>
                                    <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>

                                    <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">種別</th>
                                    <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品名</th>
                                    <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">price</th>
                                    <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">cost</th>
                                    <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">info</th>

                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="item in items" :key="item.id">
                                    <td class="border-b-2 boder-gray-200">
                                        <Link class="text-indigo-500" :href="route('items.show',{item:item.id})">{{ item.id }} </Link>
                                    </td>

                                    <td class="border-b-2 boder-gray-200">{{ item.item_category_name || 'N/A' }} </td>
                                    <td class="border-b-2 boder-gray-200">{{ item.item_name || 'N/A' }} </td>
                                    <td class="border-b-2 boder-gray-200 text-right">{{ item.item_price ? item.item_price.toLocaleString() : 'N/A' }} </td>
                                    <td class="border-b-2 boder-gray-200 text-right">{{ item.item_cost ? item.item_cost.toLocaleString() : 'N/A' }} </td>
                                    <td class="border-b-2 boder-gray-200">{{ item.item_info || 'N/A' }} </td>

                                </tr>
                            </tbody>

                        </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
