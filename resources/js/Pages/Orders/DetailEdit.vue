<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { reactive, computed, onMounted, ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        errors: Object,
        detail: Object,
        detail_statuses: Array,
    });

    const form = useForm({
        order_id: props.detail.order_id,
        detail_id: props.detail.detail_id,
       // props の値も数値化
        detail_status: props.detail.detail_status !== null ? Number(props.detail.detail_status) : null,
        detail_info: props.detail.detail_info,
    });





    const updateDetail = () => {

        form.put(route('order_detail.update', props.detail.detail_id), {
            onSuccess: () => {
                // 更新成功時の処理
            },
            onError: (errors) => {
                console.error(errors);
            }
        });
    };

    // 戻るボタンの処理
    const goBack = () => {
        window.history.back();
    };


    </script>

    <template>
        <Head title="Detail編集" />

        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">作業詳細編集</h2>
                <div class="mt-4">
                    <button
                        type="button"
                        @click="goBack"
                        class="w-32 h-8 ml-24 text-gray-700 bg-gray-200 border border-gray-300 focus:outline-none hover:bg-gray-300 rounded text-ml">
                        戻る
                    </button>
                </div>
            </template>

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <section class="text-gray-600 body-font relative">
                                <form @submit.prevent="updateDetail">
                                    <div class="container px-5 py-8 mx-auto">
                                        <div class="lg:w-1/2 md:w-full mx-auto">
                                            <div class="flex flex-wrap -m-2">

                                                <div class="p-0 w-full">

                                                    <div class="flex p-0 w-full">
                                                    <div class="relative">
                                                        <label class="leading-7 text-sm text-gray-600">ID</label>
                                                        <div id="detail_id" name="detail_id" class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ props.detail.detail_id }}</div>
                                                    </div>
                                                    <div class="ml-2 relative">
                                                        <label class="leading-7 text-sm text-gray-600">Order_ID</label>
                                                        <div id="order_id" name="orderl_id" class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ props.detail.order_id }}</div>
                                                    </div>


                                                    <div class="p-0 ml-2 relative">
                                                        <label for="detail_status" class="leading-7 text-sm text-gray-600">Status</label>
                                                        <select v-model.number="form.detail_status" id="detail_status" name="detail_status"
                                                            class="w-full bg-gray-100 rounded border border-gray-300 text-base text-gray-700 py-1 px-3">
                                                            <option value="" disabled>Status選択</option>
                                                            <option v-for="status in detail_statuses" :key="status.id" :value="status.id">
                                                                {{ status.detail_status_name }}
                                                            </option>
                                                        </select>
                                                    <div v-if="errors.detail_status" class="text-red-500">{{ errors.detail_status }}</div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="p-0 w-full">
                                                    <label for="detail_info" class="leading-7 text-sm text-gray-600">備考</label>
                                                    <textarea v-model="form.detail_info" id="detail_info" name="detail_info"
                                                        class="w-full bg-gray-100 rounded border border-gray-300 h-32 text-base text-gray-700 py-1 px-3 resize-none"></textarea>
                                                    <div v-if="errors.detail_info" class="text-red-500">{{ errors.detail_info }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="p-2 w-1/2 mx-auto">
                                        <button type="submit"
                                            class="flex mx-auto text-white bg-indigo-500 py-2 px-8 hover:bg-indigo-600 rounded text-lg">
                                            更新
                                        </button>
                                    </div>


                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </template>
