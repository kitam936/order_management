<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head ,Link} from '@inertiajs/vue3';
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm ,usePage } from '@inertiajs/vue3'


defineProps({
    errors : Object,
    categories: Array,
})


const page = usePage();

const form = useForm({
    item_category_id: page.props.old?.item_category_id ?? null,
    prod_code: page.props.old?.prod_code ?? null,
    item_name: page.props.old?.item_name ?? null,
    item_info: page.props.old?.item_info ?? null,
    item_price: page.props.old?.item_price ?? null,
    item_cost: page.props.old?.item_cost ?? null,
    item_image: null,
});



const handleFileUpload = (event) => {
    form.item_image = event.target.files[0];
}

const storeItem = () => {
    Inertia.post('/items', form)
}
</script>

<template>
    <Head title="商品登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">商品登録</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">

                            <form @submit.prevent="storeItem" enctype="multipart/form-data">
                            <div class="container px-5 py-8 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                <div class="flex flex-wrap -m-2">

                                    <div class="p-2 relative">
                                        <label for="item_category_id" class="leading-7 text-sm text-gray-600">種別ID</label>
                                        <select id="item_category_id" name="item_category_id" v-model="form.item_category_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            <option value="" disabled>種別選択</option>
                                            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.item_category_name }}</option>
                                        </select>
                                        <div v-if="errors.item_category_id" class="text-red-500">{{ errors.item_category_id }}</div>
                                    </div>
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="prod_code" class="leading-7 text-sm text-gray-600">商品番号</label>
                                            <input type="text" id="prod_code" name="prod_code" v-model="form.prod_code" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            <div v-if="errors.prod_code" class="text-red-500">{{ errors.prod_code }}</div>
                                        </div>
                                        <div class="relative">
                                            <label for="item_name" class="leading-7 text-sm text-gray-600">商品名</label>
                                            <input type="text" id="item_name" name="item_name" v-model="form.item_name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            <div v-if="errors.item_name" class="text-red-500">{{ errors.item_name }}</div>
                                        </div>
                                    </div>
                                    <div class="flex p-2 w-full">
                                    <div class="relative">
                                        <label for="item_price" class="leading-7 text-sm text-gray-600">Price</label>
                                        <input type="number" id="item_price" name="item_price" v-model="form.item_price" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <div v-if="errors.item_price" class="text-red-500">{{ errors.item_price }}</div>
                                    </div>
                                    <div class="ml-2 relative">
                                        <label for="item_cost" class="leading-7 text-sm text-gray-600">Cost</label>
                                        <input type="number" id="item_cost" name="item_cost" v-model="form.item_cost" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <div v-if="errors.item_cost" class="text-red-500">{{ errors.item_cost }}</div>
                                    </div>
                                    </div>
                                    <div class="p-2 w-full">
                                    <div class="relative">
                                        <label for="item_info" class="leading-7 text-sm text-gray-600">詳細</label>
                                        <textarea id="item_info" name="item_info"  v-model="form.item_info" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                        <div v-if="errors.item_info" class="text-red-500">{{ errors.item_info }}</div>
                                    </div>
                                    </div>
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="item_image" class="leading-7 text-sm mt-2 text-gray-800 dark:text-gray-200 ">画像</label>
                                            <input type="file" id="item_image" name="item_image" accept="image/png,image/jpeg,image/jpg" @change="handleFileUpload" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
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
