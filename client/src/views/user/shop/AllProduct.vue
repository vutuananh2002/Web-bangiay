<template>
    <div class="flex flex-col space-y-[2rem] mb-[4rem]">
        <h2 class="text-4xl font-medium text-purple truncate">Danh sách sản phẩm</h2>
        <ElScrollbar>
            <TransitionGroup @before-enter="onBeforeEnter" @enter="onEnter" @leave="onLeave" tag="div"
                class="grid grid-cols-4 min-h-[5rem] max-h-[70rem]" v-infinite-scroll="loadMoreProduct"
                :infinite-scroll-distance="50" :infinite-scroll-disabled="disabledInfScroll">
                <h3 v-if="products.length === 0" class="text-3xl text-gray absolute">Không tìm thấy sản phẩm phù hợp.
                </h3>
                <div v-else v-for="product, index in products" :key="product.id" :data-index="index" class="m-[1rem]">
                    <ProductCard :loading="loading" :product="product" />
                </div>
                <span v-if="loadingMore"
                    class="block m-auto w-[4rem] h-[4rem] border-[0.5rem] border-slate-300 border-t-[0.5rem] border-b-purple rounded-full animate-spin" />
            </TransitionGroup>
        </ElScrollbar>
    </div>
</template>

<script setup lang="ts">
import ProductCard from '@/components/ProductCard.vue';
import { getAllProducts } from '@/api/products/Products';
import type { ProductData, PaginationLink } from '@/types/Type';
import { ref, reactive, onBeforeMount, computed, watch } from 'vue';
import { toNumber } from 'lodash';
import { gsap } from 'gsap';
import { Power4 } from 'gsap';
import { useGlobalStore } from '@/stores/Global';
import { useDebounceFn } from '@vueuse/shared';

const globalStore = useGlobalStore();

const loading = ref<boolean>(false);
const loadingMore = ref<boolean>(false);

// For skeleton loading.
const productData = {
    id: 0,
    name: '',
    manufacture: {
        id: 0,
        name: ''
    },
    categories: [],
    colors: [],
    types: [],
    sizes: [],
    images: [],
    stock: '',
    price: 0,
    total_review: '',
    rating: '',
    sold: 0,
    slug: '',
    description: '',
    created_at: '',
    updated_at: '',
    review_details: [
        {
            id: 0,
            comment: '',
            customer: {
                name: '',
                avatar: '',
            },
            created_at: '',
            updated_at: '',
            star: 0,
        }
    ]
}
const products = reactive<ProductData[]>(
    Array.from({ length: 10 }).map(_ => {
        return productData;
    })
);
const pagination = reactive({
    first: '',
    next: '',
    last: '',
    prev: '',
});
const setPagination = (data: PaginationLink) => {
    pagination.first = data.first;
    pagination.last = data.last;
    pagination.next = data.next;
    pagination.prev = data.prev;
}

// Load more product while use reach the bottom of the page
const noMore = ref<boolean>(false);
const loadMoreProduct = async () => {
    if (!pagination.next) {
        noMore.value = true;
        return;
    };
    loadingMore.value = true;
    const page = pagination.next.match(/[\?|\&]page=([0-9]+)/)?.[1];
    const res = await getAllProducts('', toNumber(page), globalStore.selected);
    products.push(...res.products.data!);
    setPagination(res.links as PaginationLink);
    loadingMore.value = false;
}
const disabledInfScroll = computed(() => loadingMore.value || noMore.value);

// Transition group
const onBeforeEnter = (el: any) => {
    el.style.transform = 'translateY(50rem)';
    el.style.opacity = 0;
}

const onEnter = (el: any, done: any) => {
    gsap.to(el, {
        opacity: 1,
        y: 0,
        delay: el.dataset.index / 2 * 0.033,
        ease: Power4.easeOut,
        duration: 0.5,
        onComplete: done,
    })
}

const onLeave = (el: any, done: any) => {
    gsap.to(el, {
        opacity: 0,
        y: 500,
        delay: el.dataset.index / 2 * 0.003,
        ease: Power4.easeIn,
        onComplete: done,
    })
}

const loadProducts = useDebounceFn(async () => {
    loading.value = true;
    const res = await getAllProducts(globalStore.search, 1, globalStore.selected);
    products.length = 0;
    products.push(...res.products.data!);
    setPagination(res.links as PaginationLink);
    noMore.value = false;
    loading.value = false;
}, 1500, {
    maxWait: 3000,
});

onBeforeMount(async () => {
    loading.value = true;
    const res = await getAllProducts();
    // Remove skeleton.
    products.length = 0;
    products.push(...res.products.data!);
    setPagination(res.links as PaginationLink);
    loading.value = false;

    watch([globalStore.selected, () => globalStore.search], loadProducts);
});
</script>

<style scoped>

</style>