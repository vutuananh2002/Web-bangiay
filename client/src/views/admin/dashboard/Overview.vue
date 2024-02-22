<template>
    <div class="flex flex-col space-y-[2rem]">
        <div
            class="w-full flex flex-col space-y-[3.5rem] md:grid md:grid-cols-2 md:space-y-0 md:gap-[3rem]">
            <div
                class="flex justify-between space-x-[3rem] bg-orange-300 shadow-orange-500 p-[2rem] rounded-lg shadow-lg text-black">
                <ElIcon :size="50">
                    <Box />
                </ElIcon>
                <div class="text-right max-w-full text-black">
                    <p class="truncate max-w-[20rem]">Tổng số sản phẩm</p>
                    <span class="text-[3.4rem] font-medium" v-text="reportData?.total_product" />
                </div>
            </div>
            <div
                class="flex justify-between space-x-[3rem] bg-green-400 shadow-green-500 p-[2rem] rounded-lg shadow-lg text-black">
                <ElIcon :size="50">
                    <Coin />
                </ElIcon>
                <div class="text-right max-w-full text-black">
                    <p class="truncate max-w-[20rem] block">Tổng doanh thu</p>
                    <span class="text-[3.4rem] font-medium" v-text="`${numberFormat(reportData?.total_revenue ?? 0)} VND`" />
                </div>
            </div>
            <div
                class="flex justify-between space-x-[3rem] bg-blue bg-opacity-70 shadow-blue p-[2rem] rounded-lg shadow-lg text-black">
                <ElIcon :size="50">
                    <ShoppingTrolley />
                </ElIcon>
                <div class="text-right max-w-full text-black">
                    <p class="truncate max-w-[20rem]">Tổng số đơn hàng</p>
                    <span class="text-[3.4rem] font-medium" v-text="reportData?.total_order" />
                </div>
            </div>
            <div
                class="flex justify-between space-x-[3rem] bg-purple bg-opacity-80 shadow-purple p-[2rem] rounded-lg shadow-lg text-black">
                <ElIcon :size="50">
                    <Service />
                </ElIcon>
                <div class="text-right max-w-full text-black">
                    <p class="truncate max-w-[20rem]">Số lượng khách hàng</p>
                    <span class="text-[3.4rem] font-medium" v-text="reportData?.total_customer" />
                </div>
            </div>
        </div>
        <div class="mt-[10rem] flex flex-col space-y-[4rem]">
            <Bar v-if="!loading" :data="topRatingChartData" :options="chartOptions" />
            <Bar v-if="!loading" :data="salesByMonthChartData" :options="chartOptions" />
            <Bar v-if="!loading" :data="revenueByMonthChartData" :options="chartOptions" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { getReports } from '@/api/admin/Report';
import { Box, Coin, ShoppingTrolley, Service } from '@element-plus/icons-vue';
import { onBeforeMount, ref, computed } from 'vue';
import { useAdminStore } from '@/stores/admin/Admin';
import { currencyFormat, numberFormat } from '@/helpers/NumberFormat';
import type { ReportData } from '@/types/Type';
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

const { accessToken } = useAdminStore();
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);
const chartOptions = {
    responsive: true,
    maintainAspectRatio: true,
}

const loading = ref<boolean>(false);
const reportData = ref<ReportData>();
const topRatingChartData = computed(() => reportData.value?.top_rating_chart);
const salesByMonthChartData = computed(() => reportData.value?.sales_by_month_chart);
const revenueByMonthChartData = computed(() => reportData.value?.revenue_by_month_chart);


onBeforeMount(async () => {
    loading.value = true;
    const res = await getReports(accessToken!);
    reportData.value = res.data;
    loading.value = false;
});
</script>

<style lang="scss" scoped>

</style>