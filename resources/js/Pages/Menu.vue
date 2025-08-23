<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { usePage ,router} from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();

const logout = () => {
    router.post(route('logout'), {}, {
        onSuccess: () => {
            // ログアウト後にログイン画面へ
            window.location.href = route('login');
        },
        onError: (error) => {
            console.error('Logout failed:', error);
        },
    });
};

</script>

<template>
    <Head title="Menu" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Demo005_受注管理</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Menu</h2>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">
                                <div class="flex ">

                                    <div class="p-2 ">
                                        <Link as="button" :href="route('orders.index')" class="w-40 flex mx-auto text-white bg-indigo-500 border-0 h-10 py-2 pl-11 focus:outline-none hover:bg-indigo-600 rounded text-ml">Order管理</Link>
                                    </div>
                                    <div class="p-2 ">
                                        <Link as="button" :href="route('seikyu.index')" class="w-40 flex mx-auto text-white bg-indigo-500 border-0 h-10 py-2 pl-12 focus:outline-none hover:bg-indigo-600 rounded text-ml">請求管理</Link>
                                    </div>
                                    <div class="p-2 ">
                                        <Link as="button" :href="route('analysis')" class="w-40 flex mx-auto text-white bg-indigo-500 border-0 h-10 py-2 pl-14 focus:outline-none hover:bg-indigo-600 rounded text-ml">分析</Link>
                                    </div>
                                </div>

                                <div class="flex ">
                                    <div class="p-2 ">
                                        <Link as="button" :href="route('items.index')" class="w-40 flex mx-auto text-white bg-indigo-500 border-0 h-10 py-2 pl-12 focus:outline-none hover:bg-indigo-600 rounded text-ml">商品管理</Link>
                                    </div>
                                    <div class="p-2 ">
                                        <Link as="button" :href="route('users.index')" class="w-40 flex mx-auto text-white bg-indigo-500 border-0 h-10 py-2 pl-11 focus:outline-none hover:bg-indigo-600 rounded text-ml">User管理</Link>
                                    </div>
                                    <div class="p-2 ">
                                        <Link as="button" :href="route('csv.import.index')" class="w-40 flex mx-auto text-white bg-indigo-500 border-0 h-10 py-2 pl-8 focus:outline-none hover:bg-indigo-600 rounded text-ml">CSVインポート</Link>
                                    </div>
                                </div>
                                <div class="flex ">
                                    <div class="p-2 ">
                                        <Link as="button" :href="route('orders.my_order_index')" class="w-40 flex mx-auto text-white bg-indigo-500 border-0 h-10 py-2 pl-12 focus:outline-none hover:bg-indigo-600 rounded text-ml">MyOrder</Link>
                                    </div>
                                    <!-- ログアウトボタン -->
                                    <button @click="logout" class="w-40 flex ml-2 mt-2 text-white bg-gray-500 h-10 py-2 pl-12 hover:bg-red-gray rounded">
                                        ログアウト
                                    </button>
                                </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>



    </AuthenticatedLayout>
</template>
