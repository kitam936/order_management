<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head ,Link} from '@inertiajs/vue3';
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/vue3'
// import { Link } from '@inertiajs/inertia-vue3';


const props = defineProps({
    item: Object,
    errors : Object,
})

// const form = reactive({
const form = useForm({
    id:props.item.id,
    s_name:props.item.name,
    memo:props.item.memo,
    price:props.item.price,
    is_selling:props.item.is_selling,
})

const updateItem = (id) => {
    Inertia.put(route('items.update',{item:id}),form)
}

const deleteItem = id => {
    Inertia.delete(route('items.destroy',{item:id}),{
        onBefore:() => confirm('本当に削除しますか？')
    })
}
</script>

<template>
    <Head title="商品編集" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">商品編集</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">
                            <form @submit.prevent="updateItem(form.id)">
                            <div class="container px-5 py-8 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                <div class="flex flex-wrap -m-2">
                                    <div class="p-2 w-full">
                                    <div class="relative">
                                        <label for="s_name" class="leading-7 text-sm text-gray-600">商品名</label>
                                        <input type="text" id="s_name" name="s_name" v-model="form.s_name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <div v-if="props.errors.s_name" class="text-red-500">{{ errors.s_name }}</div>
                                    </div>
                                    </div>
                                    <div class="p-2 w-full">
                                    <div class="relative">
                                        <label for="memo" class="leading-7 text-sm text-gray-600">詳細</label>
                                        <textarea id="memo" name="memo"  v-model="form.memo" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                        <div v-if="props.errors.memo" class="text-red-500">{{ errors.memo }}</div>
                                    </div>
                                    </div>
                                    <div class="p-2 w-full">
                                    <div class="relative">
                                        <label for="price" class="leading-7 text-sm text-gray-600">価格</label>
                                        <input type="text" id="price" name="price" v-model="form.price" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <div v-if="props.errors.price" class="text-red-500">{{ errors.price }}</div>
                                    </div>
                                    </div>
                                    <div class="p-2 w-full">
                                    <div class="relative">
                                        <label for="is_selling" class="mr-4 leading-7 text-sm text-gray-600">status</label>
                                        <input type="radio" id="is_selling" name="is_selling" v-model="form.is_selling" value="1" >
                                        <label class="ml-2 mr-2">販売中</label>
                                        <input type="radio" id="is_selling" name="is_selling" v-model="form.is_selling" value="0" >
                                        <label class="ml-2 mr-2">停止中</label>
                                    </div>
                                    </div>
                                    <div class="p-2 w-full">
                                    <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"> 更新</button>
                                    </div>
                                    <!-- <div class="p-2 w-full">
                                    <Link class="flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg" @click="deleteItem(form.id)" >削除する</Link>
                                    </div> -->
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
