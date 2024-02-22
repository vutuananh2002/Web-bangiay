<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Danh sách sản phẩm</h1>
    <div>
        <ElTable :data="tableData" max-height="600" v-loading="loading" class="w-full">
            <ElTableColumn prop="updated_at" label="Cập nhật" width="150" sortable />
            <ElTableColumn prop="name" label="Tên sản phẩm" width="150" sortable />
            <ElTableColumn prop="manufacture.name" label="Nhà sản xuất" width="150" sortable />
            <ElTableColumn prop="price" label="Giá bán" width="140" sortable />
            <ElTableColumn prop="stock" label="Tồn kho" width="110" sortable />
            <ElTableColumn prop="rating" label="Đánh giá" width="120" sortable />
            <ElTableColumn prop="total_review" label="Số lượt đánh giá" width="190" sortable />
            <ElTableColumn label="Ảnh" width="200">
                <template #default="scope">
                    <div>
                        <ElImage preview-teleported class="w-[10rem] h-[10rem] flex justify-center items-center"
                            :src="imageSrc(scope.$index)" :preview-src-list="imageSrcList(scope.$index)" />
                    </div>
                </template>
            </ElTableColumn>
            <ElTableColumn fixed="right" width="200">
                <template #header>
                    <ElInput size="small" placeholder="Tìm kiếm sản phẩm" v-model="search"
                        @input="handleSearchProduct" />
                </template>
                <template #default="scope">
                    <ElButton size="small">
                        <RouterLink :to="`/admin/dashboard/products/${scope.row.slug}`">
                            Chi tiết
                        </RouterLink>
                    </ElButton>
                    <ElButton size="small" type="danger" @click="handleDeleteProduct(scope.$index, scope.row)">Xóa
                    </ElButton>
                </template>
            </ElTableColumn>
        </ElTable>
        <ElPagination layout="prev, pager, next" :total="pagination?.total ?? 10" :hide-on-single-page="true"
            @current-change="setPage" :current-page="currentPage" />
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, reactive } from 'vue';
import { getAllProducts, deleteProduct } from '../../../../api/products/Products';
import type { ProductsDataWithPaginateResponse, ProductData } from '../../../../types/Type';
import { useDebounceFn } from '@vueuse/shared';
import { useAdminStore } from '../../../../stores/admin/Admin';
import { ElMessage, ElMessageBox } from 'element-plus';

const { accessToken } = useAdminStore();
const productData = reactive<ProductsDataWithPaginateResponse>({
    links: {},
    products: {
        data: [],
    },
    success: false,
    message: ''
});

const loading = ref<boolean>(false);
const tableData = computed(() => productData.products.data);
const pagination = computed(() => productData.products.pagination);
const search = ref<string>('');
const currentPage = ref<number>(1);

const setProductData = (data: ProductsDataWithPaginateResponse): void => {
    productData.links = data.links;
    productData.message = data.message;
    productData.products = data.products;
    productData.success = data.success;
}

const setPage = async (page: number) => {
    loading.value = true;
    const res = await getAllProducts(search.value.toLocaleLowerCase(), page);
    setProductData(res);
    loading.value = false;
    currentPage.value = page;
}

const imageSrcList = (index: number) => {
    const imageData = tableData.value?.at(index)?.images;

    return imageData?.map(value => value.url);
}

const imageSrc = (index: number) => {
    return imageSrcList(index)?.at(0);
}

const handleSearchProduct = useDebounceFn(async () => {
    loading.value = true;
    const res = await getAllProducts(search.value.toLocaleLowerCase(), currentPage.value);
    setProductData(res);
    loading.value = false;
}, 1500, { maxWait: 3000 });

const handleDeleteProduct = (index: number, row: ProductData) => {
    ElMessageBox.confirm(
        'Bạn có chắc muốn xóa sản phẩm này?',
        'Cảnh báo',
        {
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            type: 'warning',
            title: 'Cảnh báo'
        }
    ).then(async () => {
        try {
            const res = await deleteProduct(row.id, accessToken!);
            productData.products.data?.splice(index, 1);
            ElMessage({
                type: 'success',
                message: res.message,
            });
        } catch (err: any) {
            ElMessage({
                type: 'error',
                message: err.message,
            });
        }
    }).catch(() => {
        ElMessage({
            type: 'info',
            message: 'Đã hủy',
        })
    });
}

onMounted(async () => {
    loading.value = true;
    const res = await getAllProducts();
    setProductData(res);
    loading.value = false;
});
</script>

<style scoped>

</style>