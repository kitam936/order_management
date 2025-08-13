<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { reactive, computed, onMounted, ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        order_total: Number,
        staffs: Array,
        cars: Array,
        items: Array,
        shops: Array,
        errors: Object,
        order_h: Object,
        order_fs: Array,
        order_statuses: Array,
    });

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

    const form = useForm({
        order_id: props.order_h.order_id,
        pitin_date: props.order_h.pitin_date,
        order_status: Number(props.order_h.order_status),
        shop_id: props.order_h.shop_id,
        car_id: props.order_h.car_id,
        order_info: props.order_h.order_info,
        items: [],
    });

    const updateOrder = () => {
        form.items = itemList.value
            .filter(item => item.pcs >= 0 && item.id !== null)
            .map(item => ({
                item_id: item.id,
                pcs: item.pcs,
                work_fee: item.work_fee,
                sales_price: item.sales_price,
                detail_info: item.detail_info,

            }));

        form.put(route('orders.update', props.order_h.order_id), {
            onSuccess: () => {
                // 更新成功時の処理
            },
            onError: (errors) => {
                console.error(errors);
            }
        });
    };

    const totalPrice = computed(() => {
        return itemList.value.reduce((sum, item) => {
            return sum + (item.pcs * item.sales_price + (item.work_fee || 0));
        }, 0);
    });

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
    </script>

    <template>
        <Head title="Order編集" />

        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order編集</h2>
            </template>

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <section class="text-gray-600 body-font relative">
                                <form @submit.prevent="updateOrder">
                                    <div class="container px-5 py-8 mx-auto">
                                        <div class="lg:w-1/2 md:w-full mx-auto">
                                            <div class="flex flex-wrap -m-2">
                                                <div class="flex">
                                                    <div class="p-0 w-60">
                                                        <label for="pitin_date" class="leading-7 text-sm text-gray-600">入庫日</label>
                                                        <input type="date" v-model="form.pitin_date"
                                                            class="w-60 bg-gray-100 rounded border border-gray-300 text-base text-gray-700 py-1 px-3" />
                                                    </div>
                                                    <div class="p-0 ml-2 relative">
                                                        <label for="shop_id" class="leading-7 text-sm text-gray-600">Shop</label>
                                                        <select v-model="form.shop_id"
                                                            class="w-full bg-gray-100 rounded border border-gray-300 text-base text-gray-700 py-1 px-3">
                                                            <option value="" disabled>Shop選択</option>
                                                            <option v-for="shop in shops" :key="shop.shop_id" :value="shop.shop_id">
                                                                {{ shop.shop_name }}
                                                            </option>
                                                        </select>
                                                        <div v-if="errors.shop_id" class="text-red-500">{{ errors.shop_id }}</div>
                                                    </div>

                                                </div>

                                                <div class="p-0 w-full">
                                                    <!-- <label for="car_id" class="leading-7 text-sm text-gray-600">User</label> -->
                                                    <!-- <select v-model="form.car_id"
                                                        class="w-full bg-gray-100 rounded border border-gray-300 text-base text-gray-700 py-1 px-3">
                                                        <option value="" disabled>User選択</option>
                                                        <option v-for="customer in customers" :key="customer.car_id" :value="customer.car_id">
                                                            {{ customer.car_name }}--{{ customer.customer_name }}--{{ customer.tel }}
                                                        </option>
                                                    </select> -->
                                                    <div class="flex p-0 w-full">
                                                    <div class="relative">
                                                        <label for="car_id" class="leading-7 text-sm text-gray-600">User</label>
                                                        <div id="car_id" name="car_id" class="h-8 text-sm w-60 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ order_h.customer_name }}--{{ order_h.car_name }}</div>
                                                    </div>
                                                    <div v-if="errors.car_id" class="text-red-500">{{ errors.car_id }}</div>
                                                    <div class="p-0 ml-2 relative">
                                                        <label for="order_status" class="leading-7 text-sm text-gray-600">Status</label>
                                                        <select v-model="form.order_status"
                                                            class="w-full bg-gray-100 rounded border border-gray-300 text-base text-gray-700 py-1 px-3">
                                                            <option value="" disabled>Status選択</option>
                                                            <option v-for="status in order_statuses" :key="status.order_status" :value="Number(status.order_status)">
                                                                {{ status.order_status_name }}
                                                            </option>
                                                        </select>
                                                    <div v-if="errors.order_status" class="text-red-500">{{ errors.order_status }}</div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="p-0 w-full">
                                                    <label for="order_info" class="leading-7 text-sm text-gray-600">備考</label>
                                                    <textarea v-model="form.order_info"
                                                        class="w-full bg-gray-100 rounded border border-gray-300 h-16 text-base text-gray-700 py-1 px-3 resize-none"></textarea>
                                                    <div v-if="errors.order_info" class="text-red-500">{{ errors.order_info }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2 w-1/2 mx-auto">
                                        <label class="leading-7 text-sm text-gray-600">Total</label>
                                        <div class="w-full bg-gray-100 rounded border py-1 px-3 text-gray-700">{{ totalPrice }}円</div>
                                    </div>

                                    <div class="p-2 w-1/2 mx-auto">
                                        <button type="submit"
                                            class="flex mx-auto text-white bg-indigo-500 py-2 px-8 hover:bg-indigo-600 rounded text-lg">
                                            更新
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
