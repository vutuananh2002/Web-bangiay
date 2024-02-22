<template>
    <div class="min-h-[100vh] w-full flex flex-col">
        <div class="flex space-x-[7rem] w-full justify-center items-start">
            <ElSkeleton :loading="loading" animated :count="1">
                <template #template>
                    <div class="flex flex-col space-y-[1rem] mt-[2rem]">
                        <ElSkeletonItem variant="image" style="width: 60rem; height: 40rem;" />
                        <div class="flex space-x-[1rem]">
                            <ElSkeletonItem v-for="i in 2" variant="image" style="width: 29.5rem; height: 15rem;" />
                        </div>
                    </div>
                </template>
                <template #default>
                    <div class="w-[calc(40%-7rem)] mt-[2rem]">
                        <!-- Main Swiper -->
                        <Swiper :modules="modules" :thumbs="{ swiper: thumbsSwiper }" :navigation="true"
                            :pagination="true" :grab-cursor="true" :autoplay="autoplay">
                            <SwiperSlide v-for="image in productData.images" :key="image.id">
                                <ElImage :src="image.url" lazy />
                            </SwiperSlide>
                        </Swiper>
                        <!-- Thumbs Swiper -->
                        <Swiper :modules="modules" @swiper="setThumbsSwiper" :slides-per-view="2" :space-between="15"
                            :grab-cursor="true">
                            <SwiperSlide v-for="image in productData.images" :key="image.id">
                                <ElImage :src="image.url" lazy />
                            </SwiperSlide>
                        </Swiper>
                    </div>
                </template>
            </ElSkeleton>
            <ElSkeleton :loading="loading" animated :count="1">
                <template #template>
                    <ElSkeletonItem variant="rect" style="width: 60rem; height: 80rem; margin-top: 3rem;" />
                </template>
                <template #default>
                    <div
                        class="mt-[3rem] w-[calc(60%-7rem)] bg-light p-8 rounded-2xl before:w-[40rem] before:h-[40rem] before:bg-text-gradient before:absolute before:rounded-full before:opacity-40 before:blur-lg before:top-[-40%] before:left-[-20%] after:absolute after:w-[40rem] after:h-[40rem] after:bg-text-gradient after:opacity-40 after:rounded-full after:bottom-[-30%] after:right-[-20%] after:blur-lg overflow-hidden backdrop-blur-md drop-shadow-lg">
                        <div class="flex flex-col space-y-[1.5rem] relative z-10">
                            <div class="flex space-x-[1rem]">
                                <ElTag v-for="category in productData.categories" :key="category.id" effect="dark" round
                                    v-text="category.name" color="rgba(136,108,255)" />
                            </div>
                            <h2 v-text="productData.name"
                                class="text-4xl max-h-[4rem] h-[4rem] font-medium truncate-two-line" />
                            <div class="flex items-center space-x-[1.5rem]">
                                <div class="flex justify-center items-center space-x-[0.5rem] text-purple">
                                    <span v-text="productData.rating" />
                                    <div v-if="isNumber(productData.rating)">
                                        <span v-for="star in round(productData.rating)"
                                            class="material-symbols-outlined text-purple"
                                            style="font-variation-settings: 'FILL' 1;">
                                            star
                                        </span>
                                        <span v-for="star in remainRating" class="material-symbols-outlined text-blue">
                                            star
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between space-x-[2rem]">
                                    <span v-text="`${productData.total_review} đánh giá`"
                                        class="text-gray font-light capitalize"></span>
                                    <span v-text="`${productData.sold} đã bán`"
                                        class="text-gray font-light capitalize"></span>
                                    <span v-text="`${productData.stock === 'Hết hàng' ? 'Hết hàng' : `${productData.stock} Hiện có`}`"
                                        class="text-gray font-light capitalize"></span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-[1rem] text-3xl">
                                <h3 class="font-medium">Giá bán: </h3>
                                <span v-text="`${productData.price} VND`"
                                    class="font-bold max-w-[65rem] truncate bg-purple-gradient bg-clip-text text-transparent" />
                            </div>
                            <div class="font-light max-h-[20rem]">
                                <h3 class="">Mô tả sản phẩm: </h3>
                                <p class="p-4 font-light truncate-five-line h-[13rem] max-h-[13rem]"
                                    v-text="productData.description" />
                            </div>
                            <div class="flex flex-col space-y-[2rem]">
                                <div class="flex items-center space-x-[1rem]">
                                    <h3 class="font-medium">Loại: </h3>
                                    <ElRadioGroup v-model="selectedType" size="default">
                                        <ElRadioButton v-for="item in productData.types" :key="item.id"
                                            :label="item.id">
                                            <span v-text="item.type" />
                                        </ElRadioButton>
                                    </ElRadioGroup>
                                </div>
                                <div class="flex items-center space-x-[1rem]">
                                    <h3 class="font-medium">Màu: </h3>
                                    <ElRadioGroup v-model="selectedColor" size="default">
                                        <ElRadioButton v-for="item in productData.colors" :key="item.id"
                                            :label="item.id">
                                            <span :style="{ 'background-color': item.color_code }"
                                                class="w-[4rem] aspect-square block rounded-full shadow-lg" />
                                        </ElRadioButton>
                                    </ElRadioGroup>
                                </div>
                                <div class="flex items-center space-x-[1rem]">
                                    <h3 class="font-medium">Size: </h3>
                                    <ElRadioGroup v-model="selectedSize" size="default">
                                        <ElRadioButton v-for="item in productData.sizes" :key="item.id"
                                            :label="item.id">
                                            <span v-text="item.name" />
                                        </ElRadioButton>
                                    </ElRadioGroup>
                                </div>
                            </div>
                        </div>
                        <div class="mt-[3rem] flex flex-col justify-center items-start space-y-[3rem]">
                            <div class="flex space-x-[1rem] justify-center items-center">
                                <span>Số lượng:</span>
                                <ElInputNumber v-model="quantity" :min="1" />
                            </div>
                            <button class="btn-primary flex justify-center items-center space-x-[1rem]"
                                @click="handleAddToCart">
                                <span class="material-symbols-outlined">
                                    shopping_cart
                                </span>
                                <span>Thêm vào giỏ hàng</span>
                            </button>
                        </div>
                    </div>
                </template>
            </ElSkeleton>
        </div>
        <Comment :loading="loading" :comments="productData.review_details" :product_id="productData.id" />
    </div>
</template>

<script setup lang="ts">
import Comment from './Comment/Comment.vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Thumbs, Autoplay, Pagination, Navigation } from 'swiper';
import { ref, reactive, onBeforeMount, computed } from 'vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import type { CartItem, ProductData } from '@/types/Type';
import { find, isNumber, round } from 'lodash';
import { numberFormat } from '@/helpers/NumberFormat';
import { getProductDetails } from '@/api/products/Products';
import { useCartStore } from '@/stores/user/Cart';
import { ElMessage } from 'element-plus';

interface Props {
    slug: string,
}
const props = defineProps<Props>();
const loading = ref<boolean>(false);

const { addToCart } = useCartStore();


// Swiper Instance
const modules = [Thumbs, Autoplay, Pagination, Navigation];
const thumbsSwiper = ref(null);
const setThumbsSwiper = (swiper: any) => {
    thumbsSwiper.value = swiper;
}
const autoplay = {
    delay: 2000,
    disableOnInteraction: false,
}

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
    review_details: Array.from({ length: 4 }).map(_ => {
        return {
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
    })
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
    productData.review_details = data.review_details;
}

// Product Rating
const TOTAL_RATING = 5;
const remainRating = computed(() => (
    isNumber(productData.rating) ? TOTAL_RATING - round(productData.rating) : 0
));

// Handle when the user selects the product properties (color, size, and type)
const selectedType = ref();
const selectedSize = ref();
const selectedColor = ref();
const quantity = ref<number>(1);

const cartItem = computed((): CartItem => {
    return {
        id: productData.id,
        name: productData.name,
        price: productData.price,
        quantity: quantity.value,
        image: productData.images[0].url,
        color: {
            id: selectedColor.value,
            color_code: find(productData.colors, (o) => o.id === selectedColor.value)!.color_code,
        },
        size: {
            id: selectedSize.value,
            name: find(productData.sizes, (o) => o.id === selectedSize.value)!.name,
        },
        type: {
            id: selectedType.value,
            type: find(productData.types, (o) => o.id === selectedType.value)!.type,
        },
        slug: productData.slug,
    }
});

const handleAddToCart = async () => {
    if (!selectedType.value) {
        ElMessage.error('Vui lòng chọn loại sản phẩm!');
        return;
    }

    if (!selectedColor.value) {
        ElMessage.error('Vui lòng chọn màu sản phẩm!');
        return;
    }

    if (!selectedSize.value) {
        ElMessage.error('Vui lòng chọn size sản phẩm!');
        return;
    }

    await addToCart(cartItem.value);
}

onBeforeMount(async () => {
    loading.value = true;
    const res = await getProductDetails(props.slug);
    setProductData(res);
    loading.value = false;
});
</script>

<style lang="scss" scoped>

</style>