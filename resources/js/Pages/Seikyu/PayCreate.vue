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
        seikyu: Object,
        pay_methods: Array,
    })

    onMounted(()=>{
        form.paid_date = getToday()

    })


    const page = usePage();

    const form = useForm({
        seikyu_id: page.props.seikyu.seikyu_id ?? null,
        seikyu_date: page.props.seikyu.seikyu_date ?? null,
        user_id: page.props.seikyu.user_id ?? null,
        customer_name: page.props.seikyu.customer_name ?? null,
        seikyu_kingaku: page.props.seikyu.seikyu_kingaku ?? null,
        paid_kingaku: page.props.old?.paid_kingaku ?? null,
        paid_date: page.props.old?.paid_date ?? null,
        pay_method: page.props.old?.pay_method ?? "",


    });


    const storePay = () => {
        Inertia.post('/pay_store', form)
    }
</script>

<template>
    <Head title="入金登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">入金登録</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 text-gray-900">
                        <section class="text-gray-600 body-font relative">

                            <form @submit.prevent="storePay" >
                            <div class="container px-5 py-8 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                <div class="flex flex-wrap -m-2">

                                    <div class="flex p-2 relative">
                                        <div class="w-60">
                                            <label for="seikyu_id" class="leading-7 text-sm text-gray-600">請求ID</label>
                                            <input readonly ="seikyu_id" name="seikyu_id" v-model="form.seikyu_id" class="w-60 h-8 rounded ">

                                        </div>
                                        <div class="ml-2 w-60">
                                            <label for="seikyu_date" class="leading-7 text-sm text-gray-600">請求日</label>
                                            <input readonly type="date" v-model="form.seikyu_date" id="seikyu_date" name="seikyu_date"
                                                class="w-60 rounded text-base h-8" required />
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
                                    <div class="p-2 relative">

                                        <div class="p-0 w-60">
                                            <label for="paid_date" class="leading-7 text-sm text-gray-600">入金日</label>
                                            <input type="date" v-model="form.paid_date" id="paid_date" name="paid_date"
                                                class="w-60 bg-gray-100 rounded border border-gray-300 text-base text-gray-700 py-1 px-3" />
                                            <div v-if="errors.paid_date" class="text-red-500">{{ errors.paid_date }}</div>
                                        </div>

                                    </div>
                                    <div class="flex p-2 w-full">
                                    <div class="relative">
                                        <label for="seikyu_kingaku" class="leading-7 text-sm text-gray-600">請求額</label>
                                        <input type="number" id="seikyu_kingaku" name="seikyu_kingaku" v-model="form.seikyu_kingaku" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
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


                                    <div class="p-2 w-full">
                                        <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"> 登録</button>
                                    </div>

                                </div>
                                </div>
                            </div>
                            </form>

                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
