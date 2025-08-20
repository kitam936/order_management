<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        data: {
            type: Object,
            required: true
        },
        type: {
            type: String,
            required: true
        }
    });

    // デシル分析かどうかの判定
    const isDecile = computed(() => props.type === 'decile');
    </script>

    <template>
      <div class="w-2/3 mx-auto sm:px-4 lg:px-4 border">
        <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
          <thead>
            <tr>
              <th v-if="!isDecile" class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                年月日
              </th>
              <th v-if="!isDecile" class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                Total
              </th>

              <th v-if="isDecile" class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                グループ
              </th>
              <th v-if="isDecile" class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                平均
              </th>
              <th v-if="isDecile" class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                合計金額
              </th>
              <th v-if="isDecile" class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                構成比
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in props.data.data" :key="item.id || item.decile || item.date">
              <!-- 通常集計 -->
              <td v-if="!isDecile" class="border-b-2 border-gray-200 text-center" style="font-variant-numeric:tabular-nums">
                {{ item.date }}
              </td>
              <td v-if="!isDecile" class="border-b-2 border-gray-200 text-right pr-10" style="font-variant-numeric:tabular-nums">
                {{ Number(item.total ?? 0).toLocaleString() }}
              </td>

              <!-- デシル分析 -->
              <td v-if="isDecile" class="border-b-2 border-gray-200 text-center" style="font-variant-numeric:tabular-nums">
                {{ item.decile }}
              </td>
              <td v-if="isDecile" class="border-b-2 border-gray-200 text-right pr-10" style="font-variant-numeric:tabular-nums">
                {{ Number(item.avg ?? 0).toLocaleString() }}
              </td>
              <td v-if="isDecile" class="border-b-2 border-gray-200 text-right pr-10" style="font-variant-numeric:tabular-nums">
                {{ Number(item.totalPerGroup ?? 0).toLocaleString() }}
              </td>
              <td v-if="isDecile" class="border-b-2 border-gray-200 text-right pr-10" style="font-variant-numeric:tabular-nums">
                {{ Number(item.ratio ?? 0).toLocaleString() }}%
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
