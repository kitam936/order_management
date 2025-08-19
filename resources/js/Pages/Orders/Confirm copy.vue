<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { reactive, computed, onMounted, ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { getToday } from '@/common';

    const props = defineProps({
        order_total: Number,
        staffs: Array,
        cars: Array,
        items: Array,
        shops: Array,
        errors: Object,
        order_h: Object,
        order_fs: Array,
    });

    onMounted(()=>{
        form.seikyu_date = getToday()

    })

    // pcs（選択肢）を文字列ではなく数値で定義する
    const pcs = Array.from({ length: 10 }, (_, i) => i); // [0, 1, 2, ..., 9]

    const line = Array.from({ length: 20 }, (_, i) => `${i + 1}`);

    const itemList = ref(
        Array.from({ length: 20 }, (_, index) => {
            const detail = props.order_fs[index];
            if (detail) {
                const item = props.items.find(i => i.id === detail.item_id);
                return {
                    id: detail.item_id,
                    name: item ? item.item_name : '',
                    price: item ? item.item_price : 0,
                    sales_price: detail.sales_price,
                    pcs: detail.item_pcs,
                    work_fee: detail.work_fee,
                    detail_info: detail.detail_info || '',

                };
            } else {
                return {
                    id: null,
                    name: '',
                    price: 0,
                    sales_price: 0,
                    pcs: 0,
                    work_fee: 0,
                    detail_info: '',

                };
            }
        })
    );

    const totalPrice = computed(() => {
        return itemList.value.reduce((sum, item) => {
            return sum + (item.pcs * item.sales_price + (item.work_fee || 0));
        }, 0);
    });

    const form = useForm({
        order_id: props.order_h.order_id,
        seikyu_date: null,
        shop_id: props.order_h.shop_id,
        car_id: props.order_h.car_id,
        seikyu_kingaku: Math.floor(totalPrice.value * 1.1),
        seikyu_info: props.order_h.order_info,
        items: [],
    });

    const storeSeikyu = () => {
        form.seikyu_kingaku = Math.floor(totalPrice.value * 1.1);
        form.items = itemList.value
            .filter(item => item.pcs >= 0 && item.id !== null)
            .map(item => ({
                item_id: item.id,
                pcs: item.pcs,
                work_fee: item.work_fee,
                sales_price: item.sales_price,
                detail_info: item.detail_info,

            }));

        form.post(route('seikyu.store'), {
            onSuccess: () => {
                // 更新成功時の処理
            },
            onError: (errors) => {
                console.error(errors);
            }
        });
    };



    function onItemChange(index) {
        const selectedId = itemList.value[index].id;
        const selectedItem = props.items.find(item => item.id === selectedId);
        if (selectedItem) {
            itemList.value[index].name = selectedItem.item_name;
            itemList.value[index].price = selectedItem.item_price;
            itemList.value[index].sales_price = selectedItem.item_price;
            itemList.value[index].work_fee = selectedItem.work_fee || 0;
            itemList.value[index].detail_info = selectedItem.detail_info || '';

        } else {
            itemList.value[index].name = '';
            itemList.value[index].price = 0;
            itemList.value[index].sales_price = 0;
            itemList.value[index].pcs = 0;
            itemList.value[index].work_fee = 0;
            itemList.value[index].detail_info = '';

        }
    }

    const downloadPDF = () => {
        window.open(route('orders.invoice', props.order_h.order_id), '_blank');
    };
    </script>

    <template>
        <Head title="売上確定" />

        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">売上確定</h2>
            </template>

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <section class="text-gray-600 body-font relative">
                                <form @submit.prevent="storeSeikyu">
                                    <div class="container px-5 py-4n mx-auto">
                                        <div class="lg:w-full md:w-full mx-auto">
                                            <div class="flex flex-wrap -m-2">
                                                <div class="flex">
                                                    <div class="p-0 w-60">
                                                        <label for="seikyu_date" class="leading-7 text-sm text-gray-600">請求日</label>
                                                        <input type="date" v-model="form.seikyu_date" id="seikyu_date" name="seikyu_date"
                                                            class="w-60 bg-gray-100 rounded border border-gray-300 text-base text-gray-700 py-1 px-3" />
                                                    </div>
                                                    <div class="p-0 ml-2 relative">
                                                        <label for="shop_id" class="leading-7 text-sm text-gray-600">Shop</label>
                                                        <div id="car_id" name="car_id" class="h-8 text-sm w-60 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_h.shop_name }}</div>

                                                    </div>
                                                    <div class="ml-2 relative">
                                                        <label for="car_id" class="leading-7 text-sm text-gray-600">User</label>
                                                        <div id="car_id" name="car_id" class="h-8 text-sm w-60 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_h.customer_name }}--{{ order_h.car_name }}</div>
                                                    </div>
                                                </div>



                                                <div class="p-0 w-full">
                                                    <label for="seikyu_info" class="leading-7 text-sm text-gray-600">備考</label>
                                                    <textarea v-model="form.seikyu_info"
                                                        class="w-full bg-gray-100 rounded border border-gray-300 h-16 text-base text-gray-700 py-1 px-3 resize-none"></textarea>
                                                    <div v-if="errors.seikyu_info" class="text-red-500">{{ errors.seikyu_info }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex">

                                    <div class="ml-2 w-60 ">
                                        <label class="leading-7 text-sm text-gray-600">本体計</label>
                                        <div class="w-full bg-gray-100 rounded border py-1 px-3 text-gray-700">{{ totalPrice }}円</div>
                                    </div>

                                    <div class="ml-2 w-40 ">
                                        <label class="leading-7 text-sm text-gray-600">消費税</label>
                                        <div class="w-full bg-gray-100 rounded border py-1 px-3 text-gray-700">{{ Math.floor(totalPrice * 0.1)}}円</div>
                                    </div>

                                    <div class="ml-2 w-60 " >
                                        <label for="seikyu_kingaku" class="leading-7 text-sm text-gray-600">請求額</label>
                                        <div class="w-full bg-gray-100 rounded border py-1 px-3 text-gray-700">{{ Math.floor(totalPrice * 1.1) }}円</div>
                                    </div>
                                </div>
                                    <div class="flex p-2 w-1/2 mx-auto">
                                        <button type="submit"
                                            class="w-40 h-10 flex mx-auto text-white bg-pink-500 py-2 pl-12 hover:bg-pink-600 rounded text-ml">
                                            売上確定
                                        </button>

                                        <button
                                        type="button"
                                        @click="downloadPDF"
                                        class="w-40 h-10 flex mx-auto mt-0 text-white bg-green-500 py-2 pl-10 hover:bg-green-600 rounded text-ml"
                                        >
                                            請求書発行
                                        </button>
                                    </div>

                                    <div class="mt-8 p-2 mx-auto w-full sm:px-4 lg:px-0 rounded border">
                                        <table class="bg-white table-auto w-full text-center">
                                            <thead>
                                                <tr>
                                                    <th>id</th><th>品名</th><th>マスタ単価</th><th>販売単価</th><th>数量</th><th>工賃</th><th>小計</th><th>詳細</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in itemList" :key="index">
                                                    <td>{{ index + 1 }}</td>
                                                    <td>
                                                        <select v-model="item.id" @change="onItemChange(index)" class="w-80 rounded">
                                                            <option value="">商品選択</option>
                                                            <option v-for="itm in props.items" :key="itm.id" :value="itm.id">
                                                                {{ itm.id }}--{{ itm.item_name }}--{{ itm.item_category_name }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>{{ item.price }}</td>
                                                    <td><input type="number" v-model.number="item.sales_price" class="w-24 text-right rounded" /></td>
                                                    <td>
                                                        <select v-model.number="item.pcs" class="w-16 rounded">
                                                            <option v-for="q in pcs" :key="q" :value="q">{{ q }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" v-model.number="item.work_fee" class="w-24 text-right rounded" /></td>
                                                    <td>{{ item.pcs * item.sales_price + item.work_fee }}</td>
                                                    <td><input type="text" v-model="item.detail_info" class="w-full rounded" placeholder="詳細情報" /></td>
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
