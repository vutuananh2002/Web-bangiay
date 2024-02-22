<template>
    <div>
        <h1 class="text-[2.6rem] font-medium uppercase">Danh sách nhà sản xuất</h1>
        <ElTable :data="tableData" min-height="600" v-loading="loading" class="w-full">
            <ElTableColumn prop="updated_at" label="Cập nhật" sortable width="150" />
            <ElTableColumn prop="name" label="Tên nhà sản xuất" sortable width="250" />
            <ElTableColumn prop="address" label="Địa chỉ" sortable width="300" />
            <ElTableColumn prop="phone_number" label="Số điện thoại" width="150" />
            <ElTableColumn prop="email" label="Email" sortable width="300" />
            <ElTableColumn prop="information" label="Thông tin" width="300" />
            <ElTableColumn prop="slug" label="Slug" width="250" />
            <ElTableColumn label="Logo" width="200">
                <template #default="scope">
                    <div>
                        <ElImage preview-teleported class="w-[10rem] h-[10rem] flex justify-center items-center" :src="scope.row.logo?.url" />
                    </div>
                </template>
            </ElTableColumn>
            <ElTableColumn fixed="right" width="200">
                <template #header>
                    <ElInput size="small" placeholder="Tìm kiếm nhà sản xuất" v-model="search" @input="handleSearchManufacture" />
                </template>
                <template #default="scope">
                    <ElButton size="small">
                        <RouterLink :to="`/admin/dashboard/manufactures/edit-manufacture/${scope.row.slug}`">
                            Sửa
                        </RouterLink>
                    </ElButton>
                    <ElButton size="small" type="danger" @click="handleDeleteManufacture(scope.$index, scope.row)">Xóa</ElButton>
                </template>
            </ElTableColumn>
        </ElTable>
        <ElPagination layout="prev, pager, next" :total="pagination?.total ?? 10" hide-on-single-page
            @current-change="setPage" :current-page="currentPage" />
    </div>
</template>

<script setup lang="ts">
import { getAllManufacturesWithPaginate, deleteManufacture } from '@/api/manufactures/Manufactures';
import { onBeforeMount, ref, reactive, computed } from 'vue';
import type { ManufacturesDataWithPaginateResponse, ManufactureData } from '@/types/Type';
import { useDebounceFn } from '@vueuse/shared';
import { ElMessage, ElMessageBox } from 'element-plus';
import { useAdminStore } from '@/stores/admin/Admin';

const { accessToken } = useAdminStore();

const manufactureData = reactive<ManufacturesDataWithPaginateResponse>({
    links: {},
    manufactures: {
        data: [],
    },
    success: false,
    message: ''
});
const tableData = computed(() => manufactureData.manufactures.data);
const pagination = computed(() => manufactureData.manufactures.pagination);
const search = ref<string>('');
const loading = ref<boolean>(false);
const currentPage = ref<number>(1);

const setManufactureData = (data: ManufacturesDataWithPaginateResponse): void => {
    manufactureData.links = data.links;
    manufactureData.manufactures = data.manufactures;
    manufactureData.message = data.message;
    manufactureData.success = data.success;
}

// Handle when changing page
const setPage = async (page: number) => {
    loading.value = true;
    // Fetch paged data
    const res = await getAllManufacturesWithPaginate(search.value, page);
    // Set data to the table
    setManufactureData(res);
    currentPage.value = page;
    loading.value = false;
}

const handleSearchManufacture = useDebounceFn(async () => {
    loading.value = true;
    const res = await getAllManufacturesWithPaginate(search.value, currentPage.value);
    setManufactureData(res);
    loading.value = false;
}, 1500, { maxWait: 3000 });

const handleDeleteManufacture = (index: number, row: ManufactureData): void => {
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
            const res = await deleteManufacture(row.id, accessToken!);
            manufactureData.manufactures.data!.splice(index, 1);
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
    });
}

onBeforeMount(async () => {
    loading.value = true;
    // Fetch manufatures data
    const res = await getAllManufacturesWithPaginate();
    // Set data to the table
    setManufactureData(res);
    loading.value = false;
});
</script>

<style scoped>

</style>