<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] font-medium uppercase">Danh sách size sản phẩm</h1>
        <ElTable :data="tableData" max-height="600" v-loading="loading" class="w-full">
            <ElTableColumn label="Cập nhật" prop="updated_at" width="150" sortable />
            <ElTableColumn label="Size" width="150" sortable>
                <template #default="scope">
                    <div>
                        <ElInput v-if="scope.row.isEdit" v-model="scope.row.name" />
                        <span v-else v-text="scope.row.name" />
                    </div>
                </template>
            </ElTableColumn>
            <ElTableColumn fixed="right" width="200">
                <template #default="scope">
                    <ElButton v-if="!scope.row.isEdit" size="small" @click="enableEdit(scope.$index, scope.row)">Sửa
                    </ElButton>
                    <ElButton v-else size="small" type="success" @click="handleEdit(scope.$index, scope.row)">Lưu</ElButton>
                    <ElButton v-if="scope.row.isEdit" size="small" type="info"
                        @click="cancelEdit(scope.$index)">Hủy</ElButton>
                    <ElButton size="small" type="danger" @click="handleDelete(scope.$index, scope.row)">Xóa</ElButton>
                </template>
            </ElTableColumn>
        </ElTable>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onBeforeMount } from 'vue';
import { getAllSizes, updateSize, deleteSize } from '@/api/sizes/Sizes';
import type { SizeData } from '@/types/Type';
import { useAdminStore } from '@/stores/admin/Admin';
import { ElMessage, ElMessageBox, ElNotification } from 'element-plus';

const { accessToken } = useAdminStore();
const loading = ref<boolean>(false);
const sizeData = ref<SizeData[]>([]);
const tableData = computed(() => sizeData.value);
const selectedSize = ref<SizeData>();

const enableEdit = (index: number, row: SizeData) => {
    selectedSize.value = { ...row };
    sizeData.value.at(index)!.isEdit = true;
}

const cancelEdit = (index: number) => {
    sizeData.value.at(index)!.isEdit = false;
    sizeData.value.at(index)!.name = selectedSize.value!.name;
}

const handleEdit = async (index: number, row: SizeData) => {
    try {
        const res = await updateSize(row.id, accessToken!, {
            name: row.name,
        });
        sizeData.value.splice(index, 1, { ...res.data, isEdit: false });
        ElNotification.success({
            title: 'Success',
            message: res.message,
            showClose: false,
            duration: 2000,
        });
    } catch (err: any) {
        ElNotification.error({
            title: 'Error',
            message: err.errors.size[0],
            showClose: false,
            duration: 2000,
        });
    }
}

const handleDelete = async (index: number, row: SizeData) => {
    ElMessageBox.confirm(
        'Bạn có chắc muốn xóa không ?',
        'Cảnh báo',
        {
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Huỷ',
            type: 'warning',
            title: 'Cảnh báo'
        }
    ).then(async () => {
        try {
            const res = await deleteSize(row.id, accessToken!);
            sizeData.value.splice(index, 1);
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
    const res = await getAllSizes();
    sizeData.value = res.data.map(value => (
        { ...value, isEdit: false }
    ));
    loading.value = false;
});
</script>

<style scoped>

</style>