<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import FlashMessage from '@/Components/FlashMessage.vue';
    import Pagination from '@/Components/Pagination.vue';
    import { Inertia } from '@inertiajs/inertia';
    import { ref } from 'vue';

    defineProps({
        orders: Object,
        customers: Array,
        car_categories: Array,
        errors: Object,
    });

    const search = ref('');
    const customer_id = ref('');
    const car_category_id = ref('');

    const searchOrders = () => {
        Inertia.get(route('orders.my_order_index', { search: search.value, car_category_id: car_category_id.value, customer_id: customer_id.value }));
    }

    const resetFilters = () => {
        search.value = '';
        customer_id.value = '';
        car_category_id.value = '';
        Inertia.get(route('orders.my_order_index'), { preserveState: true });
    }

    const downloadCSV_all = () => {
        Inertia.get(route('orders.my_csv_all'));
    }

    const downloadCSV = () => {
        Inertia.get(route('orders.my_csv', { search: search.value, car_category_id: car_category_id.value, customer_id: customer_id.value }));
    }

</script>

<template>
    <Head title="MyOrder一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">MyOrder一覧</h2>
            <div class="flex">
                <!-- <div class="ml-24 mb-4">
                    <Link as="button" :href="route('orders.create')" class="w-32 h-8 bg-green-500 text-sm text-white ml-0 hover:bg-green-600 rounded">Order登録</Link>
                </div> -->
                <div class="ml-24 mt-4 mb-0">
                    <Link as="button" :href="route('menu')" class="w-32 h-8 bg-indigo-500 text-sm text-white ml-0 hover:bg-indigo-600 rounded">Menu</Link>
                </div>
                </div>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <FlashMessage />
                    <div class="p-6 text-gray-900">



                        <div class="flex ml-12 mb-2">
                            <!-- <div class="p-2 relative mt-0">
                                <select id="customer_id" name="customer_id" v-model="customer_id" class="h-8 w-32 rounded border border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out">
                                    <option value="" selected>User選択</option>
                                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                                </select>
                            </div> -->

                            <div class="p-2 relative mt-0">
                                <select id="car_category_id" name="car_category_id" v-model="car_category_id" class="h-8 w-32 rounded border border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out">
                                    <option value="" selected>車種選択</option>
                                    <option v-for="car_category in car_categories" :key="car_category.id" :value="car_category.id">{{ car_category.car_name }}</option>
                                </select>
                            </div>

                            <div class="h-8 mr-8 ml-2 mt-2">
                                <input class="h-8 w-80 rounded" type="text" name="search" v-model="search" placeholder="ワード検索">
                                <button class="ml-2 bg-blue-300 text-white px-2 h-8 rounded" @click="searchOrders">検索</button>
                                <button class="ml-2 bg-gray-300 text-white px-2 h-8 rounded" @click="resetFilters">リセット</button>
                            </div>
                        </div>

                        <div class="flex ml-60 mb-6">
                            <button
                                type="button"
                                @click="downloadCSV_all"
                                class="w-40 h-8 flex mt-2 text-white bg-blue-500 py-2 pl-6 hover:bg-blue-600 rounded text-sm"
                            >
                                CSVダウンロードALL
                            </button>


                            <button
                                type="button"
                                @click="downloadCSV"
                                class="w-40 h-8 flex ml-8 mt-2 text-white bg-blue-500 py-2 pl-5 hover:bg-blue-600 rounded text-sm"
                            >
                                CSV検索ダウンロード
                            </button>
                        </div>

                        <div class="mx-auto w-full sm:px-4 lg:px-4 border">
                            <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                                <thead>
                                    <tr>
                                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                                        <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">pitinDate</th>
                                        <!-- <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">User</th> -->
                                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">車種</th>
                                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Staff</th>
                                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="order in orders.data" :key="order.order_id">
                                        <td class="border-b-2 border-gray-200">
                                            <Link class="text-indigo-500" :href="route('orders.my_order_show', { order: order.order_id })">{{ order.order_id }}</Link>
                                        </td>
                                        <td class="border-b-2 border-gray-200">{{ new Date(order.pitin_date).toLocaleDateString('ja-JP') }}</td>
                                        <!-- <td class="border-b-2 border-gray-200">{{ order.customer_name }}</td> -->
                                        <td class="border-b-2 border-gray-200">{{ order.car_name }}</td>
                                        <td class="border-b-2 border-gray-200 text-left">{{ order.staff_name }}</td>
                                        <td class="border-b-2 border-gray-200 text-left">{{ order.order_status_name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination :links="orders.links" class="mt-4" ></Pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

