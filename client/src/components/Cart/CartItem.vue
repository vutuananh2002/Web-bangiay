<template>
    <li ref="cartItem" :data-index="index"
        class="cart__item w-full p-6 border-b-[0.1rem] flex space-x-[3rem] justify-between">
        <div class="w-[calc(70%-3rem)] flex space-x-[2rem]">
            <div class="w-[calc(25%-2rem)] aspect-square rounded-lg overflow-hidden">
                <RouterLink :to="`/product-detail/${item.slug}`">
                    <ElImage :src="item.image" class="w-full h-full object-cover"/>
                </RouterLink>
            </div>
            <div class="w-[calc(20%-2rem)] flex flex-col space-y-[2rem]">
                <h3 class="font-medium truncate" v-text="item.name" />
                <ElTag v-text="item.type.type" />
                <ElTag v-text="item.size.name" />
                <ElTag :color="item.color.color_code" />
                <ElInputNumber v-model="item.quantity" :min="1" @change="handleQuantityChange(item)" />
            </div>
        </div>
        <div class="w-[calc(30%-3rem)] flex flex-col space-y-[2rem] text-right">
            <p class="">Giá tiền: <span v-text="`${item.price} VND x ${item.quantity}`" /></p>
            <button class="btn-delete w-[15rem] ml-auto" @click="removeItemFromCart(item)">Xóa sản
                phẩm</button>
        </div>
    </li>
</template>

<script setup lang="ts">
import { useCartStore } from '@/stores/user/Cart';
import type { CartItem } from '@/types/Type';
import { onMounted, ref, watch } from 'vue';
import { useDebounceFn, useIntersectionObserver } from '@vueuse/core';

interface Props {
    index: number,
    item: CartItem,
}

const props = defineProps<Props>();
const { removeItemFromCart, addToCart } = useCartStore();

const handleQuantityChange = useDebounceFn(async (cartItem: CartItem) => {
    await addToCart(cartItem);
}, 1500, {
    maxWait: 3000,
});

const cartItem = ref<HTMLElement>();

onMounted(() => {
    useIntersectionObserver(cartItem.value, ([{ isIntersecting }]) => {
        cartItem.value?.classList.toggle('show', isIntersecting);
    });

    watch(cartItem, () => {
        useIntersectionObserver(cartItem, ([{ isIntersecting }]) => {
            cartItem.value?.classList.toggle('show', isIntersecting);
        });
    });
})
</script>

<style lang="scss" scoped>
.cart {
    &__item {
        opacity: 0;
        transform: translateX(100rem);
        transition: all 0.33s cubic-bezier(0.19, 1, 0.22, 1);

        &.show {
            opacity: 1;
            transform: translateX(0);
        }
    }
}
</style>