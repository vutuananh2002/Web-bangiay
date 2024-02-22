<template>
    <ElCard class="w-full">
        <template #header>
            <h3 class="text-4xl font-bold text-purple drop-shadow-md">Danh sách đơn hàng</h3>
        </template>
        <ElTable :data="orderData" v-loading="loading">
            <ElTableColumn prop="updated_at" label="Cập nhật" width="120" sortable />
            <ElTableColumn prop="transaction_id" label="Mã vận đơn" width="150" />
            <ElTableColumn prop="receiver_name" label="Tên người nhận" width="150" />
            <ElTableColumn prop="receiver_address" label="Địa chỉ người nhận" width="230" />
            <ElTableColumn prop="receiver_phone_number" label="Số điện thoại người nhận" width="200" />
            <ElTableColumn label="Giá tiền" width="150">
                <template #default="scope">
                    <span v-text="`${scope.row.total_price} VND`" />
                </template>
            </ElTableColumn>
            <ElTableColumn label="Trạng thái" width="150" fixed="right" :filters="filters" :filter-method="filterStatus"
                filter-placement="bottom">
                <template #default="scope">
                    <ElTag :type="scope.row.status === 'Chờ duyệt' ? 'info' : scope.row.status === 'Đã hủy' ? 'danger' : 'success'" v-text="scope.row.status" />
                </template>
            </ElTableColumn>
            <ElTableColumn fixed="right" width="200">
                <template #default="scope">
                    <ElButton size="small">
                        <RouterLink :to="`/user/order/detail/${scope.row.id}`">Chi tiết</RouterLink>
                    </ElButton>
                    <ElButton v-if="scope.row.status !== 'Đã hủy'" type="danger" size="small" @click.prevent="cancelOrder(scope.$index, scope.row)">
                        Hủy đơn
                    </ElButton>
                </template>
            </ElTableColumn>
        </ElTable>
        <ElPagination layout="prev, pager, next" :total="pagination?.total ?? 10" hide-on-single-page
            @current-change="setPage" :current-page="currentPage" />
    </ElCard>
</template>

<script setup lang="ts">
import { getAllOrders } from '@/api/order/Order';
import type { OrderData, PaginationData } from '@/types/Type';
import { ref, onBeforeMount } from 'vue';
import { useUserStore } from '@/stores/user/User';
import { cancelOrder as ApiCancelOrder } from '@/api/order/Order';
import { ElLoading, ElMessage } from 'element-plus';

const { accessToken } = useUserStore();

const loading = ref<boolean>(false);
const orderData = ref<OrderData[]>([]);
const pagination = ref<PaginationData>();
const currentPage = ref<number>(1);

const setPage = async (page: number) => {
    loading.value = true;
    const res = await getAllOrders(page, accessToken!);
    orderData.value = res.orders.data;
    pagination.value = res.orders.pagination;
    loading.value = false;
}

const filters = [
    {
        text: 'Chờ duyệt',
        value: 'Chờ duyệt'
    },
    {
        text: 'Đang giao',
        value: 'Đang giao'
    },
    {
        text: 'Đã giao',
        value: 'Đã giao',
    },
    {
        text: 'Đã nhận',
        value: 'Đã nhận',
    },
    {
        text: 'Đã hủy',
        value: 'Đã hủy',
    }
];
const filterStatus = (value: string, row: OrderData) => {
    return row.status === value;
}

const cancelOrder = async (index: number, row: OrderData) => {
    const loading = ElLoading.service({
        lock: true,
        text: 'Đang xử lý...',
    });
    try {
        const res = await ApiCancelOrder(row.id, accessToken!);
        orderData.value.splice(index, 1, res.data);
        ElMessage.success(res.message);
    } catch (err: any) {
        console.log(err);
    }
    loading.close();
}

onBeforeMount(async () => {
    loading.value = true;
    const res = await getAllOrders(1, accessToken!);
    orderData.value = res.orders.data;
    pagination.value = res.orders.pagination;
    loading.value = false;
});
</script>

<style scoped>

</style>