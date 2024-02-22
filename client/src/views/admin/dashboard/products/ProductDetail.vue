<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] uppercase">Chi tiết sản phẩm</h1>
        <div class="flex space-x-[7rem]">
            <div class="w-[45rem] mt-[2rem]">
                <!-- Main Swiper -> pass thumbs swiper instance -->
                <Swiper :modules="modules" :thumbs="{ swiper: thumbsSwiper }" :navigation="true" :grabCursor="true"
                    :autoplay="{ delay: 1500, disableOnInteraction: false }" class="border-2 border-purple">
                    <SwiperSlide v-for="(image, index) in productData.images" :key="image.id">
                        <img :src="image.url" :alt="image.url">
                    </SwiperSlide>
                </Swiper>
                <!-- Thumbs Swiper -> store swiper instance -->
                <!-- It is also required to set watchSlidesProgress prop -->
                <swiper :modules="modules" @swiper="setThumbsSwiper" :slidesPerView="2" :spaceBetween="15"
                    :grabCursor="true" class="mt-[0.2rem]">
                    <SwiperSlide v-for="(image, index) in productData.images" :key="image.id">
                        <img :src="image.url" :alt="image.url">
                    </SwiperSlide>
                </swiper>
            </div>
            <div class="mt-[3rem]">
                <div class="flex flex-col space-y-[1.5rem]">
                    <div class="flex space-x-[1rem]">
                        <ElTag v-for="category in productData.categories" :key="category.id" effect="dark" round
                            v-text="category.name" color="rgba(136,108,255)" />
                    </div>
                    <h2 class="text-3xl max-h-[4rem] h-[4rem] font-medium truncate-two-line"
                        v-text="productData.name" />
                    <div class="flex items-center space-x-[1.5rem]">
                        <div class="flex justify-center items-center space-x-[0.5rem] text-purple">
                            <span v-text="productData.rating" />
                            <div v-if="isNumber(productData.rating)">
                                <span v-for="star in round(productData.rating)" class="material-symbols-outlined text-purple"
                                    style="font-variation-settings: 'FILL' 1;">
                                    star
                                </span>
                                <span v-for="i in remainRating" class="material-symbols-outlined">
                                    star
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between space-x-[2rem]">
                            <span v-text="`${productData.total_review} đánh giá`" class="text-gray font-light capitalize"></span>
                            <span v-text="`${productData.sold} đã bán`" class="text-gray font-light capitalize"></span>
                            <span v-text="`${productData.stock} hiện có`"
                                class="text-gray font-light capitalize"></span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-[1rem]">
                        <h3 class="font-medium">Giá bán:</h3>
                        <span v-text="`${productData.price} VND`"
                            class="font-bold text-3xl max-w-[65rem] truncate bg-purple-gradient bg-clip-text text-transparent" />
                    </div>
                    <div class="">
                        <h3 class="font-medium">Mô tả sản phẩm:</h3>
                        <p v-text="productData.description" class="p-4"/>
                    </div>
                    <div class="flex flex-col space-y-[2rem]">
                        <div class="flex items-center space-x-[1rem]">
                            <h3 class="font-medium">Loại: </h3>
                            <div class="grid grid-cols-5 gap-2">
                                <ElTag v-for="(type) in productData.types" :key="type.id" v-text="type.type" effect="plain" type="info" class="truncate"></ElTag>
                            </div>
                        </div>
                        <div class="flex items-center space-x-[1rem]">
                            <h3 class="font-medium">Màu: </h3>
                            <div class="grid grid-cols-5 gap-2">
                                <ElTag v-for="color in productData.colors" :key="color.id" v-text="color.color_code"
                                    effect="plain" type="info" class="truncate" />
                            </div>
                        </div>
                        <div class="flex items-center space-x-[1rem]">
                            <h3 class="font-medium">Size: </h3>
                            <div class="grid grid-cols-5 gap-2">
                                <ElTag v-for="size in productData.sizes" :key="size.id" v-text="size.name"
                                    effect="plain" type="info" class="truncate" />
                            </div>
                        </div>
                    </div>
                    <RouterLink :to="`/admin/dashboard/products/edit-product/${productData.slug}`">
                        <button class="btn-primary mt-[4rem]">
                            <span>Sửa thông tin</span>
                        </button>
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { getProductDetails } from '../../../../api/products/Products';
import { onBeforeMount, reactive, ref, computed } from 'vue';
import type { ProductData } from '../../../../types/Type';
import { Thumbs, Autoplay, Navigation } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { ElLoading } from 'element-plus';
import { numberFormat } from '../../../../helpers/NumberFormat';
import { isNumber, round } from 'lodash';

interface Props {
    slug: string,
}

const props = defineProps<Props>();

// Swiper Instance
const modules = [Thumbs, Autoplay, Navigation];
const thumbsSwiper = ref(null);
const setThumbsSwiper = (swiper: any) => {
    thumbsSwiper.value = swiper;
};


const productData = reactive<ProductData>({
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
    images: [{
        id: 0,
        url: '',
        expires_at: '',
    }],
    stock: '',
    price: 0,
    total_review: 0,
    rating: '',
    sold: 0,
    slug: '',
    description: '',
    created_at: '',
    updated_at: ''
});
const setProductData = (data: ProductData) => {
    productData.id = data.id;
    productData.categories = data.categories;
    productData.colors = data.colors;
    productData.images = data.images;
    productData.manufacture = data.manufacture;
    productData.name = data.name;
    productData.price = data.price;
    productData.rating = data.rating;
    productData.sizes = data.sizes;
    productData.types = data.types;
    productData.created_at = data.created_at;
    productData.slug = data.slug;
    productData.stock = data.stock;
    productData.total_review = isNumber(data.total_review) ? numberFormat(data.total_review) : data.total_review;
    productData.sold = data.sold;
    productData.updated_at = data.updated_at;
    productData.description = data.description;
}
const TOTAL_RATING = 5;
const remainRating = computed(() => (
    isNumber(productData.rating) ? TOTAL_RATING - round(productData.rating) : 0
));

onBeforeMount(async () => {
    const loading = ElLoading.service({
        lock: true,
    });
    // Fetch product data.
    const res = await getProductDetails(props.slug);
    setProductData(res);
    loading.close();
});
</script>

<style scoped>

</style>