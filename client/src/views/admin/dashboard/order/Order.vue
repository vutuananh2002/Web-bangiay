<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Danh sách đơn hàng</h1>
    <ElTable :data="tableData" max-height="600" v-loading="loading" class="w-full">
        <ElTableColumn prop="updated_at" label="Cập nhật" width="120" />
        <ElTableColumn prop="transaction_id" label="Mã vận đơn" width="150" />
        <ElTableColumn prop="customer.full_name" label="Tên khách hàng" width="150" />
        <ElTableColumn prop="receiver_name" label="Tên người nhận" width="200" />
        <ElTableColumn prop="receiver_address" label="Địa chỉ người nhận" width="350" />
        <ElTableColumn prop="receiver_phone_number" label="Số điện thoại người nhận" width="270" />
        <ElTableColumn label="Tổng giá" width="170">
            <template #default="scope">
                <span v-text="`${scope.row.total_price} VND`" />
            </template>
        </ElTableColumn>
        <ElTableColumn label="Trạng thái" :filters="filters" :filter-method="filterStatus" width="150">
            <template #default="scope">
                <ElTag
                    :type="scope.row.status === 'Chờ duyệt' ? 'info' : scope.row.status === 'Đã hủy' ? 'danger' : 'success'"
                    v-text="scope.row.status" />
            </template>
        </ElTableColumn>
        <ElTableColumn fixed="right" width="200">
            <template #default="scope">
                <ElButton size="small">
                    <RouterLink :to="`/admin/dashboard/order/detail/${scope.row.id}`">Chi tiết</RouterLink>
                </ElButton>
                <ElButton size="small" type="danger">Xóa</ElButton>
            </template>
        </ElTableColumn>
    </ElTable>
    <ElPagination layout="prev, pager, next" :total="pagination?.total ?? 10" @current-change="setPage"
        :current-page="currentPage" hide-on-single-page />
</template> 

<script setup lang="ts">
import { getAllOrders } from '@/api/order/Order';
import { ref, reactive, computed, onBeforeMount } from "vue";
import { useAdminStore } from '@/stores/admin/Admin';
import type { OrderData, OrdersDataWithPaginateResponse } from '@/types/Type';

const loading = ref<boolean>(false);
const { accessToken } = useAdminStore();

const orderData = reactive<OrdersDataWithPaginateResponse>({
    orders: {
        data: [],
    },
    links: {
        next: '',
        prev: '',
        first: '',
        last: ''
    },
    success: false,
    message: ''
});
const tableData = computed(() => orderData.orders.data);
const pagination = computed(() => orderData.orders.pagination);

const setOrderData = (data: OrdersDataWithPaginateResponse) => {
    orderData.links = data.links;
    orderData.message = data.message;
    orderData.orders = data.orders;
    orderData.success = data.success;
}

const filters = [
    {
        text: 'Chờ duyệt',
        value: 'Chờ duyệt',
    },
    {
        text: 'Đang giao',
        value: 'Đang giao',
    },
    {
        text: 'Đã giao',
        value: 'Đã giao'
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
    return value === row.status;
}

const currentPage = ref<number>(1);
const setPage = async (page: number) => {
    loading.value = true;
    const res = await getAllOrders(page, accessToken!);
    setOrderData(res);
    currentPage.value = page;
    loading.value = false;
}

onBeforeMount(async () => {
    loading.value = true;
    const res = await getAllOrders(1, accessToken!);
    setOrderData(res);
    loading.value = false;
});
</script>

<style scoped>

</style>