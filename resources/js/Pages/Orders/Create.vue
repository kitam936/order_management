<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head ,Link} from '@inertiajs/vue3';
    import { reactive ,computed } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import { useForm ,usePage } from '@inertiajs/vue3'
    import { getToday } from '@/common';
    import { onMounted } from 'vue';
    import { ref } from 'vue';
    import Micromodal from '@/Components/Micromodal.vue';

    const props = defineProps({
        customers : Array,
        staffs : Array,
        cars : Array,
        items : Array,
        shops : Array,
        errors: Object,
    });

    const itemList = ref(
        Array.from({ length: 20 }, () => ({
            id: null,
            name: '',
            price: 0,
            sales_price: 0,
            pcs: 0,
            work_fee: 0,
        }))
    );

    const line = [  "1", "2", "3", "4", "5", "6", "7", "8", "9","10", "11", "12", "13", "14", "15", "16", "17", "18", "19","20"] // option用

    onMounted(()=>{
        form.pitin_date = getToday()

    })

    const pcs = [ "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"] // option用


    const form = useForm({
        pitin_date:null,
        shop_id: "",
        car_id: "",
        order_info: '',
        items:[],
    })


    const storeOrder = () => {
        form.items = itemList.value
        .filter(item => item.pcs > 0 && item.id !== null)
        .map(item => ({
            item_id: item.id,
            pcs: item.pcs,
            work_fee: item.work_fee,
            sales_price: item.sales_price,
            detail_info: item.detail_info,
        }));

        form.post(route('orders.store'), {
            onSuccess: () => {
            form.reset();
            // 必要ならitemListもリセット
            itemList.value = Array.from({ length: 20 }, () => ({
            id: null,
            name: '',
            price: 0,
            sales_price: 0,
            pcs: 0,
            work_fee: 0,
            detail_info: '',
            }));
        },
        onError: (errors) => {
            console.error(errors);
        }
        });
      };

    const totalPrice = computed(()=>{
        let total =0
        itemList.value.forEach(item=>{
            total += item.pcs*item.sales_price+ (item.work_fee || 0) ;
        })
        return total
    })

     // 商品選択時の処理関数
    function onItemChange(index) {
        const selectedId = itemList.value[index].id;
        const selectedItem = props.items.find(item => item.id === selectedId);
        if (selectedItem) {
        itemList.value[index].name = selectedItem.item_name;
        itemList.value[index].price = selectedItem.item_price;
        itemList.value[index].sales_price = selectedItem.item_price; // 初期値は仕入単価
        itemList.value[index].work_fee = selectedItem.work_fee || 0; // 作業費があれば設定
        itemList.value[index].detail_info = selectedItem.detail_info || ''; // 詳細情報があれば設定
        } else {
        // 選択解除時（クリア）
        itemList.value[index].name = '';
        itemList.value[index].price = 0;
        itemList.value[index].sales_price = 0;
        itemList.value[index].pcs = 0;
        itemList.value[index].work_fee = 0; // 作業費もリセット
        itemList.value[index].detail_info = ''; // 詳細情報もリセット
        }
    }

    // 戻るボタンの処理
    const goBack = () => {
        window.history.back();
    };

</script>



<template>
    <Head title="Order登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order登録</h2>
            <div class="flex mt-4">
            <div class="">
                <button
                    type="button"
                    @click="goBack"
                    class="w-32 h-8 ml-24 text-gray-700 bg-gray-200 border border-gray-300 focus:outline-none hover:bg-gray-300 rounded text-ml">
                    戻る
                </button>
            </div>
            <div class="ml-24 mb-0">
                <Link as="button" :href="route('orders.index')" class="w-32 h-8 bg-indigo-500 text-sm text-white ml-0 hover:bg-indigo-600 rounded">Order一覧</Link>
            </div>
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-0 text-gray-900">
                        <section class="text-gray-600 body-font relative">

                        <form @submit.prevent="storeOrder" >

                            <div class="container px-5 py-4 mx-auto">
                                <div class="lg:w-1/2 md:w-full mx-auto">
                                <div class="flex flex-wrap -m-2">
                                    <div class="flex">
                                    <div class="p-0 w-60">
                                        <label for="pitin_date" class="leading-7 text-sm text-gray-600">入庫日</label>
                                        <div class="relative">
                                            <input type="date" name="pitin_date" v-model="form.pitin_date" class="w-60 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        </div>
                                    </div>

                                    <div class="p-0 ml-2 relative">
                                        <label for="shop_id" class="leading-7 text-sm text-gray-600">Shop</label>
                                        <select id="shop_id" name="shop_id" v-model="form.shop_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            <option value="" >Shop選択</option> <!-- 修正: disabledを追加 -->
                                            <option v-for="shop in shops" :key="shop.shop_id" :value="shop.shop_id">{{ shop.shop_name }}</option>
                                        </select>
                                        <div v-if="errors.shop_id" class="text-red-500">{{ errors.shop_id }}</div>
                                    </div>
                                    </div>

                                    <div class="p-0 w-full">
                                        <!-- <Micromodal/> -->

                                        <label for="car_id" class="leading-7 text-sm text-gray-600">User</label>
                                        <select id="car_id" name="car_id" v-model="form.car_id" @change="" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            <option value="" disabled>User選択</option>
                                            <option v-for="customer in customers" :key="customer.car_id" :value="customer.car_id">{{ customer.car_name }}--{{ customer.customer_name }}--{{ customer.tel }}</option>
                                        </select>
                                        <div v-if="errors.car_id" class="text-red-500">{{ errors.car_id }}</div>

                                    </div>


                                    <div class="p-0 w-full">
                                    <div class="relative">
                                        <label for="order_info" class="leading-7 text-sm text-gray-600">備考</label>
                                        <textarea id="order_info" name="order_info"  v-model="form.order_info" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-16 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                        <div v-if="errors.order_info" class="text-red-500">{{ errors.order_info }}</div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>


                            <div class="p-2 w-1/2 mx-auto">
                                <div class="">
                                    <label for="total" class="leading-7 text-sm text-gray-600">Total</label>
                                    <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ totalPrice }}円</div>

                                </div>
                            </div>

                            <div class="flex mx-auto">
                            <div class="mx-auto">
                                <button class="w-32 h-8 flex mx-auto text-white bg-pink-500 border-0 py-2 pl-12 focus:outline-none hover:bg-pink-600 rounded text-sm"> 登録</button>
                            </div>
                            <!-- 戻るボタン -->

                            </div>
                            <div class=" mt-4 p-2 mx-auto w-full sm:px-4 lg:px-0 rounded border ">

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
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(item, index) in itemList" :key="index">
                                            <td >{{ index + 1 }}</td>
                                            <td>
                                                <select v-model="item.id" @change="onItemChange(index)" class="w-80 rounded">
                                                    <option value="">商品選択</option>
                                                    <option v-for="itm in props.items" :key="itm.id" :value="itm.id">
                                                        {{ itm.id }}--{{ itm.item_name }}--{{ itm.item_category_name }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="w-40">{{ item.price }}</td>
                                            <td class="w-40">
                                                <input type="number" v-model.number="item.sales_price" class="w-24 text-right rounded" />
                                            </td>
                                            <td class="w-32">
                                                <select v-model="item.pcs" class="w-16 rounded">
                                                    <option v-for="q in pcs" :key="q" :value="q">{{ q }}</option>
                                                </select>
                                            </td>
                                            <td class="w-40">
                                                <input type="number" v-model.number="item.work_fee" class="w-24 text-right rounded" />
                                            </td>
                                            <td class="w-60">{{ item.pcs * item.sales_price + item.work_fee }}</td>
                                            <td class="w-60">
                                                <input type="text" v-model="item.detail_info" class="w-full rounded" placeholder="詳細情報" />
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>

                                </div>



                        </form>

                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

