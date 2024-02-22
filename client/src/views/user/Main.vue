<template>
    <div class="w-full">
        <ElSkeleton :loading="loading" animated :count="1">
            <template #template>
                <ElSkeletonItem variant="image" style="width: 100%; height: 50rem; border-radius: 1.6rem;" />
            </template>
            <template #default>
                <Swiper :modules="modules" :slides-per-view="1" :space-between="15" :pagination="pagination"
                    :grab-cursor="true" class="banner" :loop="true" :autoplay="autoplay">
                    <SwiperSlide v-for="item in banners" :key="item.id">
                        <div class="rounded-[2.4rem] block bg-purple h-[50rem] overflow-hidden">
                            <ElImage :src="item.url" class="w-full h-full" />
                        </div>
                    </SwiperSlide>
                </Swiper>
            </template>
        </ElSkeleton>
        <div v-for="item in omit(homeData, ['top_reviews'])" :key="item.title"
            class="products mt-[5rem] flex space-y-[2rem] flex-col">
            <ElSkeleton :loading="loading" animated :count="1">
                <template #template>
                    <ElSkeletonItem variant="h3" style="width: 30rem; height: 3rem;" />
                </template>
                <template #default>
                    <h3 class="text-[2.6rem] font-medium text-black max-w-[30rem] truncate uppercase"
                        v-text="item.title" />
                </template>
            </ElSkeleton>
            <div class="flex space-x-[3rem]">
                <div v-for="product in item.items.slice(0, 4)" :key="product.id"
                    class="w-[calc(100%/4-3rem)] aspect-square">
                    <ProductCard :loading="loading" :product="product" />
                </div>
            </div>
        </div>
        <WhatOurCustomerSay :loading="loading" :reviews="top4Review" />
    </div>
</template>

<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Navigation, Pagination, } from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { onBeforeMount, ref, reactive, computed } from 'vue';
import type { BannerData, HomeData } from '@/types/Type';
import { getHome } from '@/api/home/Home';
import ProductCard from '@/components/ProductCard.vue';
import WhatOurCustomerSay from '@/components/WhatOurCustomerSay.vue';
import { ElSkeleton, ElSkeletonItem } from 'element-plus';
import { omit } from 'lodash';
import { getAllBanners } from '@/api/banner/Banners';

// Swiper modules
const modules = [Autoplay, Navigation, Pagination];
const pagination = {
    dynamicBullets: true,
}
const autoplay = {
    delay: 2000,
    disableOnInteraction: true,
}

const loading = ref<boolean>(false);
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
const homeData = reactive<HomeData>({
    new_arrivals: {
        title: '',
        items: Array.from({ length: 4 }).map(_ => {
            return productData;
        }),
    },
    best_sellers: {
        title: '',
        items: Array.from({ length: 4 }).map(_ => {
            return productData;
        }),
    },
    top_rating: {
        title: '',
        items: Array.from({ length: 4 }).map(_ => {
            return productData;
        }),
    },
    top_reviews: {
        title: '',
        items: [],
    }
});

const setHomeData = (data: HomeData) => {
    homeData.new_arrivals = data.new_arrivals;
    homeData.best_sellers = data.best_sellers;
    homeData.top_rating = data.top_rating;
    homeData.top_reviews = data.top_reviews;
}
const top4Review = computed(() => homeData.top_reviews.items.slice(0, 4));

const banners = reactive<BannerData[]>([]);

onBeforeMount(async () => {
    loading.value = true;
    const res = await getHome();
    const bannerRes = await getAllBanners();
    banners.push(...bannerRes.data);
    setHomeData(res.data);
    loading.value = false;
});
</script>

<style lang="scss" scoped>

</style>