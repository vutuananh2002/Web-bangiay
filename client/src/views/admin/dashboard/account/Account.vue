<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Danh sách tài khoản</h1>
    <ElTable class="w-full" :data="tableData" max-height="600" v-loading="loading">
        <ElTableColumn type="expand">
            <template #default="scope">
                <ElTable :data="scope.row.roles">
                    <ElTableColumn prop="name" label="Vai trò" />
                </ElTable>
            </template>
        </ElTableColumn>
        <ElTableColumn prop="updated_at" label="Cập nhật" width="120" />
        <ElTableColumn prop="username" label="Username" width="150" />
        <ElTableColumn prop="email" label="Email" width="200" />
        <ElTableColumn prop="is_verified" label="Xác nhận" width="120" />
        <ElTableColumn prop="status" label="Trạng thái" width="120" />
        <ElTableColumn prop="email_verified_at" label="Ngày kích hoạt" width="150" />
        <ElTableColumn label="Avatar" width="200">
            <template #default="scope">
                <div class="w-[10rem] h-[10rem]">
                    <ElImage :src="scope.row.avatar?.url" />
                </div>
            </template>
        </ElTableColumn>
        <ElTableColumn fixed="right" width="150">
            <template #default="scope">
                <ElButton size="small">
                    <RouterLink :to="`/admin/dashboard/account/edit/${scope.row.id}`">Sửa</RouterLink>
                </ElButton>
                <ElButton type="danger" size="small" @click="handleDeleteProfile(scope.$index, scope.row)">Xóa
                </ElButton>
            </template>
        </ElTableColumn>
    </ElTable>
    <ElPagination layout="prev, pager, next" :total="pagination?.total ?? 10" hide-on-single-page
        @current-change="setPage" :current-page="currentPage" />
</template>

<script setup lang="ts">
import { ref, computed, reactive, onBeforeMount } from 'vue';
import { deleteUser, getAllUsers } from '@/api/user/User';
import type { UserData, UsersDataWithPaginateResponse } from '@/types/Type';
import { useAdminStore } from '@/stores/admin/Admin';
import { ElLoading, ElMessage, ElMessageBox } from 'element-plus';

const { accessToken } = useAdminStore();
const loading = ref<boolean>(false);

const userData = reactive<UsersDataWithPaginateResponse>({
    users: {
        data: [],
    },
    links: {
        next: '',
        prev: '',
        first: '',
        last: '',
    },
    success: false,
    message: ''
});
const tableData = computed(() => userData.users.data);
const pagination = computed(() => userData.users.pagination);

const setUserData = (data: UsersDataWithPaginateResponse) => {
    userData.links = data.links;
    userData.users = data.users;
    userData.message = data.message;
    userData.success = data.success;
}

const currentPage = ref<number>(1);
const setPage = async (page: number) => {
    loading.value = true;
    const res = await getAllUsers(page, accessToken!);
    setUserData(res);
    currentPage.value = page;
    loading.value = false;
}

const handleDeleteProfile = async (index: number, row: UserData) => {
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
        const loading = ElLoading.service({
            lock: true,
            text: 'Đang xử lý...',
        });
        try {
            const res = await deleteUser(row.id, accessToken!);
            userData.users.data.splice(index, 1);
            ElMessage.success(res.message);
            loading.close();
        } catch (err: any) {
            ElMessage.error(err.data.message);
            loading.close();
        }
    }).catch(() => {
        ElMessage.info('Đã hủy');
    })
}

onBeforeMount(async () => {
    loading.value = true;
    const res = await getAllUsers(1, accessToken!);
    setUserData(res);
    loading.value = false;
});
</script>

<style scoped>

</style>