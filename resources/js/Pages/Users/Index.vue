<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head ,Link } from '@inertiajs/vue3';
    import FlashMessage from '@/Components/FlashMessage.vue';
    import Pagination from '@/Components/Pagination.vue';
    import { Inertia } from '@inertiajs/inertia';
    import { ref } from 'vue';

    defineProps({
        users: Object,
        shops:Array
    });

    const search = ref('')
    const shop_id = ref('')
    // ref の値を取得するには .valueが必要
    const searchUsers = () => {
        Inertia.get(route('users.index', { search: search.value ,shop_id: shop_id.value}))
    }


</script>

<template>
    <Head title="User一覧" />

    <AuthenticatedLayout>
        <template #header>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User一覧</h2>
        </template>

        <div class="py-12">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <FlashMessage/>
                    <div class="p-6 text-gray-900">
                        <div class="ml-12 mb-8">
                            <Link as="button" :href="route('users.create')" class="w-32 h-8 bg-indigo-500 text-sm text-white ml-0 hover:bg-indigo-600 rounded">User登録</Link>
                        </div>
                        <div class="flex ml-12 mb-8">
                            <div class="p-2 relative mt-2 ">
                                <!-- <label for="role_id" class="leading-7 text-sm text-gray-600">Role</label> -->
                                <select id="shop_id" name="shop_id" v-model="shop_id" class="h-8 w-32 rounded border border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out">
                                    <option value="" selected>Shop選択</option> <!-- 変更: 選択なしのオプションを追加 -->
                                    <option v-for="shop in shops" :key="shop.id" :value="shop.id">{{ shop.shop_name }}</option>
                                </select>
                            </div>

                            <div class="h-8 mr-8 mt-4">

                                <input class="h-8 w-80 rounded" type="text" name="search" v-model="search" placeholder="ワード検索/ 空欄で検索すれば全件表示">
                                <button class="ml-2 bg-blue-300 text-white px-2 h-8 rounded "
                                @click="searchUsers">検索</button>
                            </div>

                        </div>

                        <div class=" mx-auto w-full sm:px-4 lg:px-4 border ">

                        <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                            <thead>
                                <tr>
                                    <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                                    <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Shop</th>
                                    <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Name</th>
                                    <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Mail</th>
                                    <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">info</th>
                                    <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">adress</th>
                                    <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">tel</th>
                                    <th class="w-2/12 md:5/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メール設定</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="user in users.data" :key="user.user_id">
                                    <td class="border-b-2 boder-gray-200">
                                        <Link class="text-indigo-500" :href="route('users.show',{user:user.user_id})">{{ user.user_id }} </Link>
                                    </td>
                                    <td class="border-b-2 boder-gray-200">{{ user.shop_name }} </td>
                                    <td class="border-b-2 boder-gray-200">{{ user.name }} </td>
                                    <td class="border-b-2 boder-gray-200">{{ user.email }} </td>
                                    <td class="border-b-2 boder-gray-200 text-right">{{ user.user_info}} </td>
                                    <td class="border-b-2 boder-gray-200 text-right">{{ user.address }} </td>
                                    <td class="border-b-2 boder-gray-200">{{ user.tel }} </td>
                                    <td class="border-b-2 boder-gray-200">
                                        <span v-if="user.mailService == 1 ">〇</span>
                                        <span v-if="user.mailService == 0 ">×</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <Pagination :links="users.links" class="mt-4" ></Pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
