<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Chi tiết đơn hàng</h1>
    <div class="">
        <ElTable :data="orderProducts" v-loading="loading">
            <ElTableColumn prop="updated_at" label="Ngày đặt hàng" width="140" />
            <ElTableColumn prop="name" label="Tên sản phẩm" width="200" />
            <ElTableColumn label="Loại" prop="type.type" width="100" />
            <ElTableColumn label="Màu sắc" width="100">
                <template #default="scope">
                    <span :style="{ 'background-color': scope.row.color.color_code }"
                        class="block w-[3rem] h-[3rem] rounded-full drop-shadow-md" />
                </template>
            </ElTableColumn>
            <ElTableColumn label="Size" prop="size.name" width="100" />
            <ElTableColumn label="Số lượng" prop="quantity" width="100" />
            <ElTableColumn label="Giá tiền">
                <template #default="scope">
                    <span v-text="`${scope.row.price} VND x${scope.row.quantity}`" />
                </template>
            </ElTableColumn>
        </ElTable>
        <h3 class="block p-4 bg-slate-200 w-full text-purple">Tổng tiền: <span
                v-text="`${currencyFormat(totalPrice)} VND`" /></h3>
        <h3 class="block p-4 bg-slate-200 w-full text-purple">Trạng thái: <span
                v-text="orderData?.status" /></h3>
        <button v-if="orderData?.status === 'Chờ duyệt'" class="btn-primary m-4" @click="handleApproveOrder">
            <span>Duyệt đơn</span>
        </button>
        <button v-if="orderData?.status === 'Đang giao'" class="btn-primary m-4" @click="handleOrderStatusChange">
            <span>Xác nhận đã giao hàng</span>
        </button>
    </div>
</template>

<script setup lang="ts">
import { getOrderDetail, approveOrder, changeOrderStatus } from '@/api/order/Order';
import { ref, onBeforeMount, computed } from "vue";
import { useAdminStore } from '@/stores/admin/Admin';
import type { OrderData } from '@/types/Type';
import { currencyFormat } from '@/helpers/NumberFormat';
import { ElNotification } from 'element-plus';
import { OrderStatus } from '@/types/Enum';

interface Props {
    id: number,
}

const props = defineProps<Props>();

const { accessToken } = useAdminStore();

const loading = ref<boolean>(false);
const orderData = ref<OrderData>();
const orderProducts = computed(() => orderData.value?.products ?? []);
const totalPrice = computed(() => orderData.value?.total_price ?? 0);

const handleApproveOrder = async () => {
    try {
        const res = await approveOrder(props.id, accessToken!);
        orderData.value = res.data;
        ElNotification.success({
            title: 'Success',
            message: res.message,
            showClose: false,
            duration: 3000,
        });
    } catch (err: any) {
        console.log(err);
    }
}

const handleOrderStatusChange = async () => {
    try {
        const res = await changeOrderStatus(props.id, accessToken!, OrderStatus.Delivered);
        orderData.value = res.data;
        ElNotification.success({
            title: 'Success',
            message: res.message,
            showClose: false,
            duration: 3000,
        });
    } catch (err: any) {
        console.log(err);
    }
}

onBeforeMount(async () => {
    loading.value = true;
    const res = await getOrderDetail(props.id, accessToken!);
    orderData.value = res.data;
    loading.value = false;
});
</script>

<style scoped>

</style>