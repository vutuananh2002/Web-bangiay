<template>
    <div class="common-layout w-full">
        <ElContainer>
            <ElAside width="370px" class="sticky left-0 top-0 bottom-0">
                <ElScrollbar max-height="60rem">
                    <div class="flex justify-between items-center">
                        <h3 class="text-4xl uppercase my-[2rem] text-purple">Lọc sản phẩm</h3>
                        <button class="btn-cancel mr-[2rem]" @click="resetSelected">Reset</button>
                    </div>
                    <ElMenu :default-openeds="['1', '2', '3']">
                        <ElSubMenu index="1">
                            <template #title>
                                <span class="text-3xl text-blue">Danh mục</span>
                            </template>
                            <ElCheckboxGroup class="w-full" v-model="selected.categories">
                                <ElMenuItem v-for="category, index in categories" :key="category.id"
                                    :index="`1-${index + 1}`">
                                    <ElCheckbox :label="category.id">
                                        <span v-text="`${category.name} (${category.products_count})`" />
                                    </ElCheckbox>
                                </ElMenuItem>
                            </ElCheckboxGroup>
                        </ElSubMenu>
                        <ElSubMenu index="2">
                            <template #title>
                                <span class="text-3xl text-blue">Giá tiền</span>
                            </template>
                            <ElCheckboxGroup class="w-full" v-model="selected.prices">
                                <ElMenuItem v-for="(price, index) in prices" class="w-full" :index="`2-${index + 1}`">
                                    <ElCheckbox :label="index">
                                        <span v-text="`${price.name} (${price.products_count})`" />
                                    </ElCheckbox>
                                </ElMenuItem>
                            </ElCheckboxGroup>
                        </ElSubMenu>
                        <ElSubMenu index="3">
                            <template #title>
                                <span class="text-3xl text-blue">Brands</span>
                            </template>
                            <ElCheckboxGroup class="w-full" v-model="selected.manufactures">
                                <ElMenuItem v-for="brand, index in brands" :key="brand.id" :index="`3-${index + 1}`">
                                    <ElCheckbox :label="brand.id">
                                        <span v-text="`${brand.name} (${brand.products_count})`" />
                                    </ElCheckbox>
                                </ElMenuItem>
                            </ElCheckboxGroup>
                        </ElSubMenu>
                        <ElSubMenu index="4">
                            <template #title>
                                <span class="text-3xl text-blue">Màu sắc</span>
                            </template>
                            <ElCheckboxGroup class="w-full" v-model="selected.colors">
                                <ElMenuItem v-for="color, index in colors" :key="color.id" :index="`4-${index + 1}`">
                                    <ElCheckbox :label="color.id">
                                        <span v-text="`${color.color_code} (${color.products_count})`" />
                                    </ElCheckbox>
                                </ElMenuItem>
                            </ElCheckboxGroup>
                        </ElSubMenu>
                        <ElSubMenu index="5">
                            <template #title>
                                <span class="text-3xl text-blue">Kích cỡ</span>
                            </template>
                            <ElCheckboxGroup class="w-full" v-model="selected.sizes">
                                <ElMenuItem v-for="size, index in sizes" :key="size.id" :index="`5-${index + 1}`">
                                    <ElCheckbox :label="size.id">
                                        <span v-text="`${size.name} (${size.products_count})`" />
                                    </ElCheckbox>
                                </ElMenuItem>
                            </ElCheckboxGroup>
                        </ElSubMenu>
                        <ElSubMenu index="6">
                            <template #title>
                                <span class="text-3xl text-blue">Đánh giá</span>
                            </template>
                            <ElRadioGroup class="w-full flex-col" v-model="selected.rating">
                                <ElMenuItem class="w-full" v-for="rate, index in rates" :key="rate.rate" :index="`6-${index + 1}`">
                                    <ElRadio :label="rate.rate">
                                        <span v-for="i in rate.rate" class="material-symbols-outlined text-purple" style="font-variation-settings: 'FILL' 1;">
                                            star
                                        </span>
                                        <span v-text="`(${rate.products_count})`" />
                                    </ElRadio>
                                </ElMenuItem>
                            </ElRadioGroup>
                        </ElSubMenu>
                    </ElMenu>
                </ElScrollbar>
            </ElAside>
            <ElContainer>
                <ElHeader class="flex justify-between items-center">
                    <div class="flex justify-center items-center text-white">
                        <span
                            class="material-symbols-outlined absolute left-[0.4rem] top-[50%] translate-y-[-50%] z-10">
                            search
                        </span>
                        <input type="search" v-model="search"
                            class="search-input bg-base border-secondary border-2 px-12 py-4 rounded-full placeholder:text-slate-500 placeholder:text-[1.4rem] outline-none text-black drop-shadow-lg focus:outline-none focus:ring-2 focus:ring-secodary"
                            placeholder="Bạn cần tìm gì?">
                        <Transition name="fade">
                            <span v-if="search" class="material-symbols-outlined absolute right-[0.5rem] cursor-pointer"
                                @click="clearSearch">
                                close
                            </span>
                        </Transition>
                    </div>
                    <div class="ml-auto">
                        <ElSelect placeholder="Sắp xếp theo" v-model="selected.order">
                            <ElOption v-for="item in orderBy" :key="item.value" :label="item.label" :value="item.value" />
                        </ElSelect>
                    </div>
                </ElHeader>
                <ElMain>
                    <RouterView v-slot="{ Component }">
                        <KeepAlive>
                            <component :is="Component" :key="$route.path" />
                        </KeepAlive>
                    </RouterView>
                </ElMain>
            </ElContainer>
        </ElContainer>
    </div>
</template>

<script setup lang="ts">
import { onBeforeMount, reactive } from 'vue';
import { getAllCategoriesWithPaginate } from '@/api/categories/Categories';
import type { CategoryData, ColorData, ManufactureData, PriceData, RateData, SizeData } from '@/types/Type';
import { getAllManufacturesWithPaginate } from '@/api/manufactures/Manufactures';
import { getAllColorsWithPaginate } from '@/api/colors/Colors';
import { useGlobalStore } from '@/stores/Global';
import { storeToRefs } from 'pinia';
import { getPrices } from '@/api/prices/Prices';
import { getAllSizesWithPaginate } from '@/api/sizes/Sizes';
import { getRates } from '@/api/rates/Rates';

const { selected, search } = storeToRefs(useGlobalStore());
const { resetSelected } = useGlobalStore();

const orderBy = [
    {
        label: 'Tên A-Z',
        value: 1,
    },
    {
        label: 'Tên Z-A',
        value: 2,
    },
    {
        label: 'Giá tiền Thấp - Cao',
        value: 3,
    },
    {
        label: 'Giá tiền Cao - Thấp',
        value: 4,
    },
]
const categories = reactive<CategoryData[]>([]);
const brands = reactive<ManufactureData[]>([]);
const colors = reactive<ColorData[]>([]);
const prices = reactive<PriceData[]>([]);
const sizes = reactive<SizeData[]>([]);
const rates = reactive<RateData[]>([]);

const clearSearch = () => {
    search.value = '';
}

onBeforeMount(async () => {
    const categoriesRes = await getAllCategoriesWithPaginate();
    const colorsRes = await getAllColorsWithPaginate();
    const manufacturesRes = await getAllManufacturesWithPaginate();
    const pricesRes = await getPrices();
    const sizesRes = await getAllSizesWithPaginate();
    const ratesRes = await getRates();
    categories.push(...categoriesRes.categories.data!);
    colors.push(...colorsRes.colors.data!);
    brands.push(...manufacturesRes.manufactures.data!);
    prices.push(...pricesRes.data!);
    sizes.push(...sizesRes.sizes.data!);
    rates.push(...ratesRes.data);
});
</script>

<style lang="scss" scoped>
.search {
    &-input {
        &::-webkit-search-cancel-button {
            display: none;
        }
    }
}
</style>