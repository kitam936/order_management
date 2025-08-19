<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
    import { Head, useForm } from '@inertiajs/vue3'
    import { ref, onMounted, onUnmounted } from 'vue'
    import FlashMessage from '@/Components/FlashMessage.vue'


    const form = useForm({ file: null })
    const progress = ref(0)
    const total = ref(0)
    const current = ref(0)
    let intervalId = null

    function submit() {
        progress.value = 0
        total.value = 0
        current.value = 0

        form.post(route('csv.import.store'), { forceFormData: true })
    }

    function fetchProgress() {
        fetch(route('csv.import.progress'))
            .then(res => res.json())
            .then(data => {
                total.value = data.total
                current.value = data.current
                progress.value = data.total ? Math.floor((data.current / data.total) * 100) : 0

                if (data.current >= data.total && data.total > 0) {
                    clearInterval(intervalId)
                }
            })
    }

    onMounted(() => {
        intervalId = setInterval(fetchProgress, 1000) // 1秒ごとに取得
    })
    onUnmounted(() => {
        clearInterval(intervalId)
    })
    </script>

    <template>
        <AuthenticatedLayout>
            <Head title="CSVインポート" />

            <FlashMessage />

            <div class="p-4">
                <h1 class="text-xl font-bold mb-4">CSVインポート</h1>

                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <input type="file" @change="e => form.file = e.target.files[0]" class="mb-4" />
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        アップロード
                    </button>
                </form>

                <div class="mt-6">
                    <div class="w-full bg-gray-200 rounded h-6 overflow-hidden">
                        <div class="bg-green-500 h-6" :style="{ width: progress + '%' }"></div>
                    </div>
                    <p class="mt-2">{{ current }} / {{ total }} 件 ({{ progress }}%)</p>
                </div>

                <div v-if="$page.props.flash.success" class="mt-4 text-green-600">
                    {{ $page.props.flash.success }}
                </div>
            </div>
        </AuthenticatedLayout>
    </template>

