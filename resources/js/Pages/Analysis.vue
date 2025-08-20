<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref ,reactive ,onMounted} from 'vue';
import { usePage } from '@inertiajs/vue3';
import {getToday} from '@/common';
import Chart from '@/Components/Chart.vue';

onMounted(() => {
    // Initialize the form with today's date
    const today = getToday();
    form.startDate = today;
    form.endDate = today;
});

const form = reactive({
    startDate: null,
    endDate: null,
    type:'perDay'
});

const data = reactive({});

const getData = async() => {
    try{
        await axios.get('/api/analysis', {
            params:{
            startDate: form.startDate,
            endDate: form.endDate,
            type: form.type
            }
        })
        .then(res => {
            data.data = res.data.data;
            data.labels = res.data.labels;
            data.totals = res.data.totals;
            console.log(res.data)
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
                            From:<input type="date" name="startDate" v-model="form.startDate">
                            To:<input type="date" name="endDate" v-model="form.endDate">
                            <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded">分析</button>
                        </form>
                        <Chart v-show="data.data" :data="data" />
                    </div>
                </div>
            </div>
        </div>


        <div v-show="data.data" class="mx-auto w-full sm:px-4 lg:px-4 border">
            <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead>
                    <tr>

                        <th class="w-2/12 md:3/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年月日</th>
                        <th class="w-2/12 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Total</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="item in data.data" :key="item.date">
                        <td class="border-b-2 border-gray-200 text-center"  style="font-variant-numeric:tabular-nums">{{ item.date }}</td>
                        <td class="border-b-2 border-gray-200 text-center"  style="font-variant-numeric:tabular-nums">{{ Number(item.total ?? 0).toLocaleString() }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </AuthenticatedLayout>
</template>
