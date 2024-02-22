<template>
    <ElSkeleton :loading="loading" animated :count="2" class="flex mt-[5rem] space-x-[3rem]">
        <template #template>
            <ElSkeletonItem variant="rect" style="width: calc(100%/2 -3rem); height: 50rem;" />
        </template>
        <template #default>
            <div class="mt-[5rem] h-[50rem]">
                <h2
                    class="text-[2.6rem] p-6 drop-shadow-md font-medium text-black uppercase">
                    Phản hồi khách hàng</h2>
                <Swiper :modules="modules" :slides-per-view="2" :space-between="15" :pagination="pagination"
                    :grab-cursor="true" :loop="true" :autoplay="autoplay">
                    <SwiperSlide v-for="review in reviews" :key="review.id">
                        <div
                            class="h-[40rem] rounded-[2.4rem] bg-primary flex justify-center items-center flex-col space-y-[2rem]">
                            <div
                                class="w-[10rem] aspect-square rounded-full overflow-hidden border-[0.4rem] border-solid border-purple">
                                <img :src="review.customer.avatar" :alt="review.customer.name" class="w-full h-full">
                            </div>
                            <p v-text="review.comment" class="p-4 text-center text-white drop-shadow-md" />
                            <span v-text="`${review.customer.name}`" class="text-center text-white drop-shadow-md" />
                            <div class="">
                                <span v-for="star in review.star" class="material-symbols-outlined text-secondary"
                                    style="font-variation-settings: 'FILL' 1">
                                    star
                                </span>
                            </div>
                        </div>
                    </SwiperSlide>
                </Swiper>
            </div>
        </template>
    </ElSkeleton>
</template>

<script setup lang="ts">
import type { ReviewData } from '@/types/Type';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Navigation, Pagination, } from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { ElSkeleton, ElSkeletonItem } from 'element-plus';

interface Props {
    loading: boolean,
    reviews: ReviewData[],
}

const props = defineProps<Props>();

const modules = [Autoplay, Navigation, Pagination];
const pagination = {
    dynamicBullets: true,
}
const autoplay = {
    delay: 2000,
    disableOnInteraction: true,
}
</script>

<style lang="scss" scoped>

</style>