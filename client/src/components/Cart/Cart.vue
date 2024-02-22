<template>
    <ElCard class="cart w-full">
        <!-- Cart Header -->
        <template #header>
            <div class="cart-header flex justify-between p-6 text-[1.8rem]">
                <span class="font-bold text-[1.8rem] drop-shadow-md text-purple">Giỏ
                    hàng của tôi</span>
                <p class="text-purple drop-shadow-md font-bold">Tổng tiền: <span
                        v-text="`${currencyFormat(totalPrice)} VND`" /></p>
            </div>
        </template>
        <!-- Cart List Item -->
        <ElScrollbar height="400px" v-if="cartDetail().length">
            <TransitionGroup tag="ul" class="cart__list overflow-hidden" appear @leave="leave">
                <CartItem v-for="(item, index) in cartDetail()" :key="item.id" :index="index" :item="item" />
            </TransitionGroup>
        </ElScrollbar>
        <!-- Cart Footer -->
        <div v-if="cartDetail().length" class="cart-footer flex space-x-[4rem] p-6">
            <button class="btn-primary">
                <RouterLink to="/checkout" class="block w-full h-full">Đặt hàng</RouterLink>
            </button>
            <button class="btn-delete" @click.prevent="removeAllItemFromCart">Xóa tất cả</button>
        </div>
        <p v-if="cartDetail().length === 0" class="text-center">Giỏ hàng đang trống.</p>
    </ElCard>
</template>

<script setup lang="ts">
import CartItem from './CartItem.vue';
import { ElCard, ElScrollbar } from 'element-plus';
import { useCartStore } from '@/stores/user/Cart';
import { storeToRefs } from 'pinia';
import { currencyFormat } from '@/helpers/NumberFormat';
import { onMounted } from 'vue';
import { gsap } from "gsap";

const { cartDetail, removeAllItemFromCart } = useCartStore();
const { totalPrice } = storeToRefs(useCartStore());

const leave = (el: any, done: any) => {
    gsap.to(el, {
        opacity: 0,
        x: -1000,
        duration: 0.5,
        onComplete: done,
    });
}

onMounted(() => {
    gsap.from('.cart', {
        opacity: 0,
        y: '-100%',
        ease: 'bounce.out',
        duration: 0.5,
    });
});
</script>

<style lang="scss" scoped>
</style>