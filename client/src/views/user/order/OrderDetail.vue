<template>
    <ElCard class="w-full">
        <template #header>
            <h3 class="text-4xl font-bold text-purple drop-shadow-md">Chi tiết đơn hàng</h3>
        </template>
        <ElTable :data="orderProducts" v-loading="loading">
            <ElTableColumn prop="updated_at" label="Ngày đặt hàng" width="140" />
            <ElTableColumn prop="name" label="Tên sản phẩm" width="200" />
            <ElTableColumn label="Loại" prop="type.type" width="100" />
            <ElTableColumn label="Màu sắc" width="100">
                <template #default="scope"> 
                    <span :style="{'background-color': scope.row.color.color_code}" class="block w-[3rem] h-[3rem] rounded-full drop-shadow-md" />
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
        <h3 class="block p-4 bg-slate-200 w-full text-purple">Tổng tiền: <span v-text="`${currencyFormat(totalPrice)} VND`" /></h3>
        <button v-if="orderStatus === 'Đã giao'" class="btn-primary m-4" @click="handleConfirmReceived">
            <span>Xác nhận đã nhận hàng</span>
        </button>
    </ElCard>
</template>

<script setup lang="ts">
import { confirmReceivedOrder, getOrderDetail } from '@/api/order/Order';
import { ref, onBeforeMount } from "vue";
import { useUserStore } from '@/stores/user/User';
import type { CartItem } from '@/types/Type';
import { currencyFormat } from '@/helpers/NumberFormat';

interface Props {
    id: number,
}

const props = defineProps<Props>();

const { accessToken } = useUserStore();

const loading = ref<boolean>(false);
const orderProducts = ref<CartItem[]>([]);
const totalPrice = ref<string>('');
const orderStatus = ref<string>();

const handleConfirmReceived = async () => {
    try {
        const res = await confirmReceivedOrder(props.id, accessToken!);
        orderStatus.value = res.data.status;
    } catch (err: any) {
        console.log(err);
    }
}

onBeforeMount(async () => {
    loading.value = true;
    const res = await getOrderDetail(props.id, accessToken!);
    orderProducts.value = res.data.products;
    totalPrice.value = res.data.total_price;
    orderStatus.value = res.data.status;
    loading.value = false;
})
</script>

<style scoped>

</style>