<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] font-medium uppercase">Danh sách danh mục</h1>
        <ElTable :data="tableData" max-height="600" v-loading="loading" class="w-full">
            <ElTableColumn prop="updated_at" label="Cập nhật" width="150" sortable />
            <ElTableColumn label="Tên danh mục" width="150" sortable>
                <template #default="scope">
                    <ElInput v-if="scope.row.isEdit" v-model="scope.row.name"
                        @input="generateSlug(scope.$index, scope.row.name)" />
                    <span v-else v-text="scope.row.name" />
                </template>
            </ElTableColumn>
            <ElTableColumn label="Slug" width="250" sortable>
                <template #default="scope">
                    <ElInput v-if="scope.row.isEdit" v-model="scope.row.slug" />
                    <span v-else v-text="scope.row.slug" />
                </template>
            </ElTableColumn>
            <ElTableColumn fixed="right" width="200">
                <template #header>
                    <ElInput size="small" placeholder="Tìm kiếm danh mục" v-model="search"
                        @input="handleSearchCategory" />
                </template>
                <template #default="scope">
                    <ElButton v-if="!scope.row.isEdit" size="small" @click="enableEdit(scope.$index, scope.row)">Sửa
                    </ElButton>
                    <ElButton v-else size="small" type="success" @click="handleEdit(scope.$index, scope.row)">Lưu</ElButton>
                    <ElButton v-if="scope.row.isEdit" size="small" type="info" @click="cancelEdit(scope.$index)">Hủy
                    </ElButton>
                    <ElButton size="small" type="danger" @click="handleDelete(scope.$index, scope.row)">Xóa</ElButton>
                </template>
            </ElTableColumn>
        </ElTable>
        <ElPagination layout="prev, pager, next" :total="pagination?.total ?? 10" :hide-on-single-page="true"
            @current-change="setPage" :current-page="currentPage" />
    </div>
</template>

<script setup lang="ts">
import { onBeforeMount, ref, reactive, computed } from 'vue';
import { getAllCategoriesWithPaginate, updateCategory, deleteCategory } from '@/api/categories/Categories';
import type { CategoriesDataWithPaginateResponse, CategoryData } from '@/types/Type';
import { useDebounceFn } from '@vueuse/shared';
import { SlugGenerator } from '@/helpers/SlugGenerator';
import { useAdminStore } from '@/stores/admin/Admin';
import { ElMessage, ElMessageBox, ElNotification } from 'element-plus';

const { accessToken } = useAdminStore();

const categoryData = reactive<CategoriesDataWithPaginateResponse>({
    links: {},
    categories: {
        data: [],
    },
    success: false,
    message: ''
});
const tableData = computed(() => categoryData.categories.data);
const pagination = computed(() => categoryData.categories.pagination);
const search = ref<string>('');
const loading = ref<boolean>(false);
const currentPage = ref<number>(1);

// For edit column
const selectedCategory = ref<CategoryData>();

const setCategoryData = (data: CategoriesDataWithPaginateResponse): void => {
    categoryData.message = data.message;
    categoryData.success = data.success;
    categoryData.links = data.links;
    categoryData.categories = data.categories;
}

// Handle when changing page.
const setPage = async (page: number) => {
    loading.value = true;
    // Fetch paged data
    const res = await getAllCategoriesWithPaginate(search.value.toLocaleLowerCase(), page);
    // Set data to the table
    setCategoryData(res);
    currentPage.value = page;
    loading.value = false;
}

const handleSearchCategory = useDebounceFn(async () => {
    loading.value = true;
    const res = await getAllCategoriesWithPaginate(search.value.toLocaleLowerCase(), currentPage.value);
    setCategoryData(res);
    loading.value = false;
}, 1500, { maxWait: 3000 });

// Handle when click edit button
const enableEdit = (index: number, row: CategoryData) => {
    selectedCategory.value = { ...row };
    categoryData.categories.data!.at(index)!.isEdit = true;
}

const cancelEdit = (index: number) => {
    categoryData.categories.data!.at(index)!.slug = selectedCategory.value!.slug;
    categoryData.categories.data!.at(index)!.name = selectedCategory.value!.name;
    categoryData.categories.data!.at(index)!.isEdit = false;
}

// Auto generate slug from category name
const generateSlug = useDebounceFn((index: number, name: string) => {
    const slug = new SlugGenerator(name);
    categoryData.categories.data!.at(index)!.slug = slug.getSlug();
}, 500, { maxWait: 1000 });

const handleEdit = async (index: number, row: CategoryData) => {
    try {
        const res = await updateCategory(row.id, accessToken!, {
            name: row.name,
            slug: row.slug,
        });
        categoryData.categories.data!.splice(index, 1, {...res.data, isEdit: false});
        ElNotification.success({
            title: 'Success',
            message: res.message,
            showClose: false,
            duration: 2000,
        });
    } catch (err: any) {
        ElNotification.error({
            title: 'Error',
            message: err.errors,
            showClose: false,
            duration: 2000,
        })
    }
}

const handleDelete = async (index: number, row: CategoryData) => {
    ElMessageBox.confirm(
        'Bạn có chắc muốn xóa không?',
        'Cảnh báo',
        {
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Huỷ',
            type: 'warning',
            title: 'Cảnh báo'
        }
    ).then(async () => {
        try {
            const res = await deleteCategory(row.id, accessToken!);
            categoryData.categories.data!.splice(index, 1);
            ElMessage.success({
                message: res.message,
            });
        } catch (err: any) {
            ElMessage.error({
                message: err.message,
            });
        }
    }).catch(() => {
        ElMessage.info({
            message: 'Đã hủy',
        });
    })
}

onBeforeMount(async () => {
    loading.value = true;
    // Fetch categories data
    const res = await getAllCategoriesWithPaginate();
    // Set data to the table.
    setCategoryData(res);
    loading.value = false;
});
</script>

<style scoped>

</style>