<template>
    <ElSkeleton :loading="loading" animated :count="1">
        <template #template>
            <ElSkeletonItem variant="image" style="width: 100%; height: 30rem; border-radius: 1.4rem;" />
            <div style="padding: 1rem;">
                <ElSkeletonItem variant="text" style="width: 100%; height: 2rem;" />
                <div class="">
                    <ElSkeletonItem variant="text" />
                    <ElSkeletonItem variant="text" />
                </div>
            </div>
        </template>
        <template #default>
            <div
                class="w-full aspect-square cursor-pointer bg-base transition-all duration-500 drop-shadow-md rounded-[1.4rem] product-card">
                <div class="w-full h-[80%] rounded-[1.4rem] overflow-hidden"
                    :style="{ 'background-color': `${randomColor()}` }">
                    <RouterLink :to="`/product-detail/${product.slug}`">
                        <ElImage :src="product.images[0].url" class="w-full h-full object-cover" />
                    </RouterLink>
                </div>
                <div class="flex items-center justify-between p-6 space-x-[0.5rem]">
                    <div class="w-[calc(80%-0.5rem)]">
                        <div v-if="isNumber(product.rating)">
                            <span v-for="star in round(product.rating)" class="material-symbols-outlined text-primary"
                                style="font-variation-settings: 'FILL' 1;">
                                star
                            </span>
                            <span v-for="i in remainRating" class="material-symbols-outlined text-secondary">
                                star
                            </span>
                        </div>
                        <div v-else>
                            <span v-for="i in TOTAL_RATING" class="material-symbols-outlined text-secondary">
                                star
                            </span>
                        </div>
                        <RouterLink :to="`/product-detail/${product.slug}`"
                            class="block font-medium text-[1.8rem] text-black drop-shadow-md truncate mb-2"
                            v-text="product.name" />
                        <span class="font-light drop-shadow-md text-slate-700 truncate"
                            v-text="`${product.price} VND`" />
                    </div>
                    <ElTooltip placement="bottom" content="Thêm vào giỏ hàng">
                        <button class="btn-primary flex justify-center items-center" @click="addToCart(cartItem)">
                            <span class="material-symbols-outlined">
                                add_shopping_cart
                            </span>
                        </button>
                    </ElTooltip>
                </div>
            </div>
        </template>
    </ElSkeleton>
</template>

<script setup lang="ts">
import type { CartItem, ProductData } from '@/types/Type';
import { ElSkeletonItem, ElTooltip, type ElSkeleton } from 'element-plus';
import { isNumber, round } from 'lodash';
import { randomColor } from '@/helpers/RandomColor';
import { useCartStore } from '@/stores/user/Cart';
import { useUserStore } from '@/stores/user/User';
import { computed } from 'vue';

interface Props {
    loading: boolean,
    product: ProductData,
}

const props = defineProps<Props>();

const { addToCart } = useCartStore();
const { isAuthenticated } = useUserStore();

const TOTAL_RATING = 5;
const remainRating = computed(() => {
    return isNumber(props.product!.rating) ? TOTAL_RATING - round(props.product!.rating) : 0;
});

const cartItem = computed((): CartItem => {
    return {
        id: props.product!.id,
        name: props.product?.name,
        image: props.product!.images[0].url,
        price: props.product!.price,
        quantity: 1,
        color: {
            id: props.product!.colors?.[0]?.id,
            color_code: props.product!.colors?.[0]?.color_code,
        },
        size: {
            id: props.product!.sizes?.[0]?.id,
            name: props.product!.sizes?.[0]?.name,
        },
        type: {
            id: props.product!.types?.[0]?.id,
            type: props.product!.types?.[0]?.type,
        },
        slug: props.product.slug,
    }
});
</script>

<style lang="scss" scoped>
.product-card {
  box-shadow: 0 0.5rem 0 #61DCDF;

  &:hover {
    @apply drop-shadow-xl;
  }
}
</style>