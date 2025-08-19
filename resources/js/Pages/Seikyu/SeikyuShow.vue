<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head ,Link} from '@inertiajs/vue3';
    import { reactive } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import { useForm ,usePage } from '@inertiajs/vue3'
    import { onMounted } from 'vue';
    import { getToday } from '@/common';


    defineProps({
        errors : Object,
        seikyu: Object,
        pays: Array,
    })



    const deleteSeikyu = (id) => {
        Inertia.delete(route('seikyu.destroy', { id: id }), {
            onBefore: () => confirm('本当に削除しますか？')
        })
    }
</script>

<template>
    <Head title="請求詳細" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">請求詳細</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 text-gray-900">
                        <div class="container px-5 py-4 ">
                            <div class="lg:w-full md:w-full ">
                            <div class="md:flex ">

                                <div class="flex relative">
                                    <div class="w-32">
                                        <label for="seikyu_id" class="ml-2 leading-7 text-sm text-gray-600">請求ID</label>
                                        <div name="seikyu_id" class="bg-gray-100 ml-2 w-20 rounded ">{{ seikyu.seikyu_id }}</div>

                                    </div>
                                    <div class="ml-2 w-40">
                                        <label for="seikyu_date" class="ml-2 leading-7 text-sm text-gray-600">請求日</label>
                                        <div name="seikyu_date" class="bg-gray-100 ml-2 w-40 rounded text-base"  >{{ seikyu.seikyu_date}}</div>
                                    </div>
                                    <!-- <div class="w-60">
                                        <label for="user_id" class="leading-7 text-sm text-gray-600">User_ID</label>
                                        <div name="user_id" class="w-60 h-8 rounded ">{{ seikyu.user_id }}</div>
                                    </div> -->
                                    <div class="ml-2 w-60">
                                        <label for="customer_name" class="ml-2 leading-7 text-sm text-gray-600">User名</label>
                                        <div name="customer_name" class="bg-gray-100 ml-2 w-50 rounded ">{{ seikyu.customer_name }}</div>
                                    </div>
                                </div>

                                <div class="flex w-full">
                                <div class="w-60">
                                    <label for="seikyu_kingaku" class="ml-2 leading-7 text-sm text-gray-600 " >請求額</label>
                                    <div name="seikyu_kingaku" class="bg-gray-100 ml-2 w-full rounded " style="font-variant-numeric:tabular-nums">{{ seikyu.seikyu_kingaku.toLocaleString() }}円</div>
                                </div>
                                </div>
                                </div>
                                <div class="md:flex md:mt-2">
                                <div class="ml-2 relative">
                                    <label for="seikyu_status" class="leading-7 text-sm text-gray-600">Status</label>
                                    <div name="seikyu_status" class="bg-gray-100 w-32 rounded " >{{ seikyu.seikyu_status_name }}</div>

                                </div>
                                <div class="md:ml-2 relative">
                                    <label for="seikyu_info" class="ml-2 leading-7 text-sm text-gray-600">Info</label>
                                    <div name="seikyu_info" class="bg-gray-100 ml-2 w-60 rounded ">{{ seikyu.seikyu_info }}</div>
                                </div>
                                </div>
                                <!-- <div class="p-2 w-full">
                                    <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集</button>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto w-full sm:px-4 lg:px-4 border">
            <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead>
                    <tr>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">入金Id</th>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">入金日</th>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">入金額</th>
                        <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">支払方法</th>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">操作</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="pay in pays" :key="pay.pay_id">
                        <td class="border-b-2 border-gray-200">{{ pay.pay_id }}</td>
                        <!-- <td>
                            <Link :href="route('pay.show', { id: pay.pay_id })" class="text-blue-500 hover:text-blue-700">{{ pay.pay_id }}</Link>
                        </td> -->
                        <td class="border-b-2 border-gray-200">{{ new Date(pay.paid_date).toLocaleDateString('ja-JP') }}</td>
                        <td class="border-b-2 border-gray-200 text-right" style="font-variant-numeric:tabular-nums">{{ pay.paid_kingaku.toLocaleString() }}円</td>
                        <td class="border-b-2 border-gray-200 text-right">{{ pay.pay_method_name }}</td>
                        <td class="border-b-2">
                            <Link :href="route('pay.edit', { id: pay.pay_id })" class="text-blue-500 hover:text-blue-700">編集</Link>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </AuthenticatedLayout>
</template>
