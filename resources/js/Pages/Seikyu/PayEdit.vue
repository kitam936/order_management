<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head ,Link} from '@inertiajs/vue3';
    import { reactive } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import { useForm ,usePage } from '@inertiajs/vue3'
    import { onMounted } from 'vue';
    import { getToday } from '@/common';


    const props =defineProps({
        errors : Object,
        pay: Object,
        pay_methods: Array,
    })

    onMounted(()=>{
        form.paid_date = getToday()

    })


    const page = usePage();

    const form = useForm({
        pay_id: page.props.pay.pay_id ?? null,
        seikyu_id: page.props.pay.seikyu_id ?? null,
        paid_date: page.props.pay.paid_date ?? null,
        user_id: page.props.pay.user_id ?? null,
        customer_name: page.props.pay.customer_name ?? null,
        paid_kingaku: page.props.pay.paid_kingaku ?? null,
        pay_method: page.props.pay.pay_method ?? "",
    });


    const updatePay = () => {
        form.put(route('pay.update', { id: form.pay_id }));
    };

    const deletePay = (id) => {
        Inertia.delete(route('pay.destroy', { id: form.pay_id }), {
            onBefore: () => confirm('本当に削除しますか？')
        })
    }
</script>

<template>
    <Head title="入金編集" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">入金編集</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 text-gray-900">
                        <section class="text-gray-600 body-font relative">

                            <form @submit.prevent="updatePay" >
                            <div class="container px-5 py-4 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                <div class="flex flex-wrap -m-2">

                                    <div class="flex p-2 relative">
                                        <div class="w-60">
                                            <label for="pay_id" class="leading-7 text-sm text-gray-600">入金ID</label>
                                            <input readonly name="pay_id" v-model="form.pay_id" class="w-60 h-8 rounded ">
                                        </div>
                                        <div class="ml-2 w-60">
                                            <label for="seikyu_id" class="leading-7 text-sm text-gray-600">請求ID</label>
                                            <input readonly ="seikyu_id" name="seikyu_id" v-model="form.seikyu_id" class="w-60 h-8 rounded ">

                                        </div>

                                    </div>

                                    <div class="flex p-2 relative">
                                        <div class="w-60">
                                            <label for="user_id" class="leading-7 text-sm text-gray-600">User_ID</label>
                                            <input readonly ="user_id" name="user_id" v-model="form.user_id" class="w-60 h-8 rounded ">
                                        </div>
                                        <div class="ml-2 w-60">
                                            <label for="customer_name" class="leading-7 text-sm text-gray-600">User名</label>
                                            <input readonly type="text" id="customer_name" name="customer_name" v-model="form.customer_name" class="w-60 h-8 rounded ">
                                        </div>
                                    </div>
                                    <div class="flex p-2 relative">

                                        <div class="p-0 w-60">
                                            <label for="paid_date" class="leading-7 text-sm text-gray-600">入金日</label>
                                            <input type="date" v-model="form.paid_date" id="paid_date" name="paid_date"
                                                class="w-60 h-10 bg-gray-100 rounded border border-gray-300 text-base text-gray-700 py-1 px-3" />
                                            <div v-if="errors.paid_date" class="text-red-500">{{ errors.paid_date }}</div>
                                        </div>
                                        <div class="ml-2 relative">
                                            <label for="paid_kingaku" class="leading-7 text-sm text-gray-600">入金額</label>
                                            <input type="number" id="paid_kingaku" name="paid_kingaku" v-model="form.paid_kingaku" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            <div v-if="errors.paid_kingaku" class="text-red-500">{{ errors.paid_kingaku }}</div>
                                        </div>

                                    </div>

                                    <div class="w-60">
                                        <div class="p-0 ml-2 relative">
                                            <label for="pay_method" class="leading-7 text-sm text-gray-600">PayMethod</label>
                                            <select id="pay_method" name="pay_method" v-model="form.pay_method" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <option value="" > 支払方法選択</option>
                                                <option v-for="pay_method in pay_methods" :key="pay_method.id" :value="pay_method.id">{{ pay_method.pay_method_name }}</option>
                                            </select>
                                            <div v-if="errors.shop_id" class="text-red-500">{{ errors.shop_id }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div   div class="mt-4 flex">
                                    <div class="p-2 w-full">
                                        <button class="w-32 h-10 flex mx-auto text-white bg-indigo-500 border-0 py-2 pl-12 focus:outline-none hover:bg-indigo-600 rounded text-lg"> 更新</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </form>
                            <div class="h-10 p-2 w-full">
                                <button class="w-32 h-10 flex mx-auto text-white bg-red-500 border-0 py-2 pl-12 focus:outline-none hover:bg-red-600 rounded h-10 text-lg" @click="deletePay(pay.pay_id)" >削除</button>
                            </div>

                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
