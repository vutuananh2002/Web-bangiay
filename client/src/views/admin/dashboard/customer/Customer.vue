<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Danh sách khách hàng</h1>
    <ElTable :data="tableData" max-height="600" v-loading="loading" class="w-full">
        <ElTableColumn prop="updated_at" label="Cập nhật" width="120" sortable />
        <ElTableColumn prop="full_name" label="Tên khách hàng" width="170" sortable />
        <ElTableColumn label="Giới tính" width="120" :filters="filters" :filter-method="filterGender"
            filter-placement="bottom">
            <template #default="scope">
                <ElTag :type="scope.row.gender === 'Nam' ? '' : 'success'" v-text="scope.row.gender" />
            </template>
        </ElTableColumn>
        <ElTableColumn prop="address" label="Địa chỉ" width="500" />
        <ElTableColumn prop="phone_number" label="Số điện thoại" width="140" />
        <ElTableColumn fixed="right" width="150">
            <template #default="scope">
                <ElButton size="small">Sửa</ElButton>
                <ElButton size="small" type="danger" @click.prevent="handleDeleteCustomer(scope.$index, scope.row)">Xóa</ElButton>
            </template>
        </ElTableColumn>
    </ElTable>
    <ElPagination layout="prev, pager, next" :total="pagination?.total ?? 10" @current-change="setPage"
        :current-page="currentPage" hide-on-single-page />
</template>

<script setup lang="ts">
import { deleteCustomer, getAllCustomers } from '@/api/customer/Customer';
import { ref, reactive, computed, onBeforeMount } from "vue";
import { useAdminStore } from '@/stores/admin/Admin';
import type { CustomersDataWithPaginateResponse, CustomerData } from '@/types/Type';
import { ElLoading, ElMessage, ElMessageBox } from 'element-plus';

const loading = ref<boolean>(false);
const { accessToken } = useAdminStore();

const customerData = reactive<CustomersDataWithPaginateResponse>({
    customers: {
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
const tableData = computed(() => customerData.customers.data);
const pagination = computed(() => customerData.customers.pagination);

const setCustomerData = (data: CustomersDataWithPaginateResponse) => {
    customerData.links = data.links;
    customerData.customers = data.customers;
    customerData.message = data.message;
    customerData.success = data.success;
}

const filters = [
    {
        text: 'Nam',
        value: 'Nam',
    },
    {
        text: 'Nữ',
        value: 'Nữ',
    }
];

const filterGender = (value: string, row: CustomerData) => {
    return value === row.gender;
}

const currentPage = ref<number>(1);
const setPage = async (page: number) => {
    loading.value = true;
    const res = await getAllCustomers(page, accessToken!);
    setCustomerData(res);
    currentPage.value = page;
    loading.value = false;
}

const handleDeleteCustomer = (index: number, row: CustomerData) => {
    ElMessageBox.confirm(
        'Bạn có chắc muốn xóa không? Thao tác này sẽ xóa toàn bộ thông tin (bao gồm cả tài khoản) của khách hàng này khỏi hệ thống.',
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
            const res = await deleteCustomer(row.id, accessToken!);
            customerData.customers.data.splice(index, 1);
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
    const res = await getAllCustomers(1, accessToken!);
    setCustomerData(res);
    loading.value = false;
});
</script>

<style scoped>

</style>