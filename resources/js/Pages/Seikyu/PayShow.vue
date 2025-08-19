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
        pay: Object,
    })



    const deletePay = (id) => {
        Inertia.delete(route('pay.destroy', { id: id }), {
            onBefore: () => confirm('本当に削除しますか？')
        })
    }
</script>

<template>
    <Head title="入金詳細" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">入金詳細</h2>
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
                                        <label for="pay_id" class="ml-2 leading-7 text-sm text-gray-600">入金ID</label>
                                        <div name="pay_id" class="bg-gray-100 ml-2 w-20 rounded ">{{ pay.pay_id }}</div>

                                    </div>
                                    <div class="ml-2 w-40">
                                        <label for="pay_date" class="ml-2 leading-7 text-sm text-gray-600">入金日</label>
                                        <div name="pay_date" class="bg-gray-100 ml-2 w-40 rounded text-base"  >{{ pay.paid_date}}</div>
                                    </div>

                                </div>

                                <div class="flex w-full">
                                <div class="w-60">
                                    <label for="paid_kingaku" class="ml-2 leading-7 text-sm text-gray-600">入金額</label>
                                    <div name="paid_kingaku" class="bg-gray-100 ml-2 w-full rounded ">{{ pay.paid_kingaku }}円</div>
                                </div>
                                </div>
                                </div>
                                <div class="md:flex md:mt-2">
                                <div class="ml-2 relative">
                                    <label for="pay_method" class="leading-7 text-sm text-gray-600">Status</label>
                                    <div name="pay_method" class="bg-gray-100 w-32 rounded ">{{ pay.pay_method_name }}</div>

                                </div>
                                </div>
                                <div class="w-full">
                                    <Link type="button" :href="route('pay.edit', { id: pay.pay_id })" class="w-32 h-8 bg-indigo-500 text-sm text-white ml-0 hover:bg-indigo-600 rounded">編集</Link>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>



    </AuthenticatedLayout>
</template>
