<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import FlashMessage from '@/Components/FlashMessage.vue';
    import Pagination from '@/Components/Pagination.vue';
    import { Inertia } from '@inertiajs/inertia';
    import { ref } from 'vue';

    defineProps({
        seikyus: Object,
        total:Object,
        customers: Array,
        car_categories: Array,

    });

    const search = ref('');
    const customer_id = ref('');
    const car_category_id = ref('');

    const searchOrders = () => {
        Inertia.get(route('seikyu.index', { search: search.value, car_category_id: car_category_id.value, customer_id: customer_id.value }));
    }
</script>

<template>
    <Head title="請求一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">請求一覧</h2>

            <div class="flex p-2 w-1/2 mx-auto">
                <div class="">
                    <label for="total" class="leading-7 text-sm text-gray-600">総請求額</label>
                    <div class="h-8 text-sm w-60 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ total.total_seikyu_kingaku }}円</div>

                </div>
                <div class="">
                    <label for="total" class="leading-7 text-sm text-gray-600">総入金額</label>
                    <div class="h-8 text-sm w-60 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ total.total_paid_kingaku }}円</div>

                </div>
            </div>
        </template>

        <div class="flex ml-12 mb-8">
            <div class="p-2 relative mt-2">
                <select id="customer_id" name="customer_id" v-model="customer_id" class="h-8 w-32 rounded border border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out">
                    <option value="" selected>User選択</option>
                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                </select>
            </div>

            <div class="p-2 relative mt-2">
                <select id="car_category_id" name="car_category_id" v-model="car_category_id" class="h-8 w-32 rounded border border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out">
                    <option value="" selected>車種選択</option>
                    <option v-for="car_category in car_categories" :key="car_category.id" :value="car_category.id">{{ car_category.car_name }}</option>
                </select>
            </div>

            <div class="h-8 mr-8 mt-4">
                <input class="h-8 w-80 rounded" type="text" name="search" v-model="search" placeholder="ワード検索/ 空欄で検索すれば全件表示">
                <button class="ml-2 bg-blue-300 text-white px-2 h-8 rounded" @click="searchOrders">検索</button>
            </div>
        </div>



        <div class="mx-auto w-full sm:px-4 lg:px-4 border">
            <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead>
                    <tr>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">User</th>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">車種</th>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">請求額</th>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">入金額</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="seikyu in seikyus.data" :key="seikyu.seikyu_id">
                        <td class="border-b-2 border-gray-200">{{ seikyu.seikyu_id }}</td>
                        <td class="border-b-2 border-gray-200">{{ new Date(seikyu.seikyu_date).toLocaleDateString('ja-JP') }}</td>
                        <td class="border-b-2 border-gray-200">{{ seikyu.customer_name }}</td>
                        <td class="border-b-2 border-gray-200">{{ seikyu.car_name }}</td>
                        <!-- <td class="border-b-2 border-gray-200 text-right">{{ seikyu.staff_name }}</td> -->
                        <td class="border-b-2 border-gray-200 text-right">{{ seikyu.seikyu_kingaku }}円</td>
                        <td class="border-b-2 border-gray-200 text-right">{{ seikyu.paid_kingaku }}円</td>
                    </tr>
                </tbody>
            </table>
            <!-- <Pagination :links="orders.links" class="mt-4" ></Pagination> -->
        </div>


    </AuthenticatedLayout>
</template>

