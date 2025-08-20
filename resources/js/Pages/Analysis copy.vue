<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { ref ,reactive ,onMounted} from 'vue';
    import { getToday } from '@/common';
    import Chart from '@/Components/Chart.vue';
    import axios from 'axios';

    const form = reactive({
        startDate: null,
        endDate: null,
        type: 'perMonth', // 初期値
        customer_id: '',
        car_category_id: ''
    });

    const data = reactive({
        customers: [],
        car_categories: [],
        data: [],
        labels: [],
        totals: []
    });

    // 初期ロード
    onMounted(() => {
        const today = getToday();
        form.startDate = today;
        form.endDate = today;
        getData();
    });

    const getData = async() => {
        try{
            await axios.get('/api/analysis', {
                params:{
                    startDate: form.startDate,
                    endDate: form.endDate,
                    type: form.type,
                    customer_id: form.customer_id,
                    car_category_id: form.car_category_id
                }
            })
            .then(res => {
                data.data = res.data.data;
                data.labels = res.data.labels;
                data.totals = res.data.totals;
                data.customers = res.data.customers;
                data.car_categories = res.data.car_categories;
            })
        }catch(e) {
            console.log(e.message)
        }
    }
    </script>

    <template>
        <Head title="データ分析" />

        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    データ分析
                </h2>
            </template>

            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <form @submit.prevent="getData">
                                <label class="mr-2">分析タイプ:</label>
                                <div class="mr-2">
                                    <label><input type="radio" value="perYear" v-model="form.type" /> 年別</label>
                                    <label><input type="radio" value="perMonth" v-model="form.type" /> 月別</label>
                                    <label><input type="radio" value="perWeek" v-model="form.type" /> 週別</label>
                                    <label><input type="radio" value="perDay" v-model="form.type" /> 日別</label>
                                </div><br>



                                <div class=" md:flex mt-2">
                                    <div class="flex items-center">
                                    <label class="mr-2">開始日:</label>
                                    <input v-model="form.startDate" type="date" class="h-8 w-40 rounded border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out" />
                                    <label class="ml-2 mr-2">終了日:</label>
                                    <input v-model="form.endDate" type="date" class="h-8 w-40 rounded border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out" />
                                    </div>
                                    <div class="flex items-center ml-4">
                                    <label class="ml-3 mr-2">絞り込み:</label>
                                    <!-- User選択 -->
                                    <div class="flex">
                                    <div class="relative ">
                                        <select v-model="form.customer_id" class="h-8 w-40 rounded border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out">
                                            <option value="">User選択なし</option>
                                            <option v-for="customer in data.customers" :key="customer.id" :value="customer.id">
                                                {{ customer.car_name }}--{{ customer.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <!-- 車種選択 -->
                                    <div class="relative ml-2">
                                        <select v-model="form.car_category_id" class="h-8 w-40 rounded border focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-1 leading-8 transition-colors duration-200 ease-in-out">
                                            <option value="">車種選択なし</option>
                                            <option v-for="car_category in data.car_categories" :key="car_category.id" :value="car_category.id">
                                                {{ car_category.car_name }}
                                            </option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <br>

                                <button type="submit" class="w-32 ml-2 px-4 py-2 bg-blue-500 text-white rounded">分析</button>
                            </form>

                            <Chart v-show="data.data.length" :data="data" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- 集計結果テーブル -->
            <div v-show="data.data.length" class="w-1/2 mx-auto sm:px-4 lg:px-4 border">
                <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                    <thead>
                        <tr>
                            <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年月日</th>
                            <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data.data" :key="item.date">
                            <td class="border-b-2 border-gray-200 text-center" style="font-variant-numeric:tabular-nums">{{ item.date }}</td>
                            <td class="border-b-2 border-gray-200 text-right pr-10" style="font-variant-numeric:tabular-nums">{{ Number(item.total ?? 0).toLocaleString() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </AuthenticatedLayout>
    </template>
