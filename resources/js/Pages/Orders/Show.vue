<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head ,Link} from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    order_h: Object,
    order_fs: Array,
    order_total: Number,
})

const deleteOrder = (id) => {
    Inertia.delete(route('orders.destroy', { order: id }), {
        onBefore: () => confirm('本当に削除しますか？')
    })
}

</script>

<template>
    <Head title="order詳細" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order詳細</h2>
            <div class="flex">
            <div class="h-10 p-2 w-full">
                <Link as="button" :href="route('orders.edit',{order:order_h.order_id})" class="w-36 flex mx-auto text-white bg-indigo-500 border-0 py-2 px-12 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集</Link>
            </div>
            <div class="h-10 p-2 w-full">
                <button class="w-36 flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg" @click="deleteOrder(order_h.order_id)" >削除する</button>
            </div>
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-2 text-gray-900">
                        <section class="text-gray-600 body-font relative">

                            <div class="container px-5 py-8 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                <div class="flex flex-wrap -m-2">


                                    <div class="p-2 w-full">
                                        <div class="flex">
                                        <div class="relative">
                                            <label for="id" class="leading-7 text-sm text-gray-600">ID</label>
                                            <div type="text" id="order_id" name="order_id"  class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_h.order_id }}</div>
                                        </div>
                                        <div class="ml-2 relative">
                                            <label for="pitin_date" class="leading-7 text-sm text-gray-600">入庫日</label>
                                            <div type="date" id="pitin_date" name="pitin_date" class="h-8 text-sm w-40 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ new Date(order_h.pitin_date).toLocaleDateString('ja-JP') }}</div>
                                        </div>
                                        <div class="ml-2 relative">
                                            <label for="order_status" class="leading-7 text-sm text-gray-600">Status</label>
                                            <div type="text" id="order_status" name="order_status" class="h-8 text-sm w-40 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_h.order_status_name }}</div>

                                        </div>
                                        </div>
                                        <div class="relative">
                                            <label for="user_id" class="leading-7 text-sm text-gray-600">User</label>
                                            <div id="user_id" name="user_id" class="h-8 text-sm w-60 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_h.customer_name }}</div>

                                        </div>
                                    </div>
                                    <div class="flex ml-2 w-full">
                                        <div class="p-0 relative">
                                            <label for="shop_id" class="w-32 leading-7 text-sm text-gray-600">Shop</label>
                                            <div type="shop_id" id="shop_id" name="shop_id" class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_h.shop_name }}</div>

                                        </div>
                                        <div class="p-0 w-40 ml-2 relative">
                                            <label for="staff_id" class="leading-7 text-sm text-gray-600">Staff</label>
                                            <div type="staff_id" id="staff_id" name="staff_id" class="h-8 text-sm w-40 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_h.staff_name }}</div>
                                        </div>
                                    </div>
                                    <div class="ml-2 w-full">
                                    <div class="relative">
                                        <label for="order_info" class="leading-7 text-sm text-gray-600">備考</label>
                                        <textarea id="order_info" name="order_info" :value="order_h.order_info" readonly class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-24 text-base outline-none text-gray-700 py-0 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>

                                    </div>
                                    </div>

                                    <div class="p-2 w-1/2 mx-auto">
                                        <div class="">
                                            <label for="total" class="leading-7 text-sm text-gray-600">Total</label>
                                            <div class="h-8 text-sm w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_total }}円</div>

                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                        </section>
                    </div>
                    </div>
                </div>
            </div>

            <div class=" mt-8 p-2 mx-auto w-full sm:px-4 lg:px-0 rounded border ">

                <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                    <thead>
                        <tr>
                            <th class="w-1/13 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                            <th class="w-1/13 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品名</th>
                            <th class="w-3/13 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">マスタ単価</th>
                            <th class="w-3/13 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">販売単価</th>
                            <th class="w-3/13 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数量</th>
                            <th class="w-3/13 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">工賃</th>
                            <th class="w-3/13 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">小計</th>
                            <th class="w-3/13 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">詳細</th>
                            <th class="w-3/13 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="order_f in order_fs" :key="order_f.detail_id">
                            <td >{{ order_f.detail_id }}</td>
                            <td class="w-40">{{ order_f.item_name }}</td>
                            <td class="w-40">{{ order_f.item_price }}</td>
                            <td class="w-40">{{ order_f.sales_price }}</td>
                            <td class="w-60">{{ order_f.item_pcs }}</td>
                            <td class="w-60">{{ order_f.work_fee }}</td>
                            <td class="w-60">{{ order_f.item_pcs * order_f.sales_price + order_f.work_fee }}</td>
                            <td class="w-60">{{ order_f.detail_info }}</td>
                            <td class="w-60">{{ order_f.detail_status_name }}</td>
                        </tr>


                    </tbody>
                </table>

                </div>

    </AuthenticatedLayout>
</template>
