<template>
    <ElCard class="w-full">
        <template #header>
            <h2 class="text-[2rem] font-medium uppercase">Thanh toán</h2>
        </template>
        <div class="flex flex-col space-y-[4rem]">
            <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules">
                <ElFormItem label="Tên người nhận" prop="receiver_name">
                    <ElInput v-model="ruleForm.receiver_name" placeholder="Nhập tên người nhận" />
                </ElFormItem>
                <ElFormItem label="Địa chỉ người nhận" prop="receiver_address">
                    <div class="flex space-x-[2rem]">
                        <ElFormItem prop="province">
                            <ElSelectV2 v-model="ruleForm.province" :options="listProvinces" value-key="value.code"
                                filterable remote :remote-method="searchProvinces" placeholder="Chọn tỉnh,thành phố"
                                @change="loadDistricts" />
                        </ElFormItem>
                        <ElFormItem prop="district">
                            <ElSelectV2 v-model="ruleForm.district" :options="listDistricts" value-key="value.code"
                                filterable remote :remote-method="searchDistricts" placeholder="Chọn quận, huyện"
                                @change="loadWards" />
                        </ElFormItem>
                        <ElFormItem prop="ward">
                            <ElSelectV2 v-model="ruleForm.ward" :options="listWards" value-key="value.code" filterable
                                remote :remote-method="searchWards" placeholder="Chọn xã, phường, thị trấn"
                                @change="setAddress" />
                        </ElFormItem>
                    </div>
                </ElFormItem>
                <ElFormItem label="Số điện thoại người nhận" prop="receiver_phone_number">
                    <ElInput v-model="ruleForm.receiver_phone_number" placeholder="Nhập số điện thoại người nhận." />
                </ElFormItem>
                <ElFormItem label="Hình thức thanh toán" prop="payment">
                    <ElRadioGroup v-model="ruleForm.payment" @change="handlePaymentChange">
                        <ElRadioButton :label="PaymentMethod.Cash">
                            <span>Tiền mặt</span>
                        </ElRadioButton>
                        <ElRadioButton :label="PaymentMethod.Banking">
                            <span>Thanh toán bằng thẻ</span>
                        </ElRadioButton>
                    </ElRadioGroup>
                </ElFormItem>
            </ElForm>
            <div>
                <h2 class="text-[2rem]">Chi tiết đơn hàng</h2>
                <ElTable :data="cartDetail()" class="mt-[2rem]">
                    <ElTableColumn label="Tên sản phẩm" prop="name" />
                    <ElTableColumn label="Màu sắc">
                        <template #default="scope">
                            <span :style="{ 'background-color': scope.row.color.color_code }"
                                class="block w-[3rem] aspect-square rounded-full" />
                        </template>
                    </ElTableColumn>
                    <ElTableColumn label="Loại" prop="type.type" />
                    <ElTableColumn label="Size" prop="size.name" />
                    <ElTableColumn label="Số lượng" prop="quantity" />
                    <ElTableColumn label="Giá tiền">
                        <template #default="scope">
                            <span v-text="`${scope.row.price} VND x${scope.row.quantity}`" />
                        </template>
                    </ElTableColumn>
                </ElTable>
                <h3 class="block p-4 bg-slate-200 w-full text-purple">Tổng tiền: <span
                        v-text="`${currencyFormat(totalPrice)} VND`" /></h3>
            </div>
            <button class="btn-primary" @click.prevent="submitForm(ruleFormRef)">
                <span>Xác nhận đặt hàng</span>
            </button>
        </div>
    </ElCard>
</template>

<script setup lang="ts">import { getDistricts, getProvinces, getWards } from '@/api/provinces/Provinces';
import type { DistrictData, ProvinceData, WardData } from '@/types/Type';
import { ElLoading, ElMessage, ElNotification, type FormInstance, type FormRules } from 'element-plus';
import { find, join } from 'lodash';
import { computed, reactive, ref, onBeforeMount } from 'vue';
import { useCartStore } from '@/stores/user/Cart';
import { currencyFormat } from '@/helpers/NumberFormat';
import { storeToRefs } from 'pinia';
import { PaymentMethod } from '@/types/Enum';
import { checkout } from '@/api/cart/Cart';
import { useUserStore } from '@/stores/user/User';
import router from '@/router';

const { accessToken } = useUserStore();
const { cartDetail, cartId, cartKey, setCartIdAndKey, cart } = useCartStore();
const { totalPrice } = storeToRefs(useCartStore());

const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive({
    receiver_name: '',
    province: null,
    district: null,
    ward: null,
    receiver_address: '',
    receiver_phone_number: '',
    payment: 1,
});
const rules = reactive<FormRules>({
    receiver_name: [
        { required: true, message: 'Vui lòng nhập tên người nhận.', trigger: 'blur' },
    ],
    receiver_address: [
        { required: true, message: 'Vui lòng nhập địa chỉ người nhận.' },
    ],
    receiver_phone_number: [
        { required: true, message: 'Vui lòng nhập số điện thoại người nhận.', trigger: 'blur' }
    ],
    payment: [
        { required: true, message: 'Vui lòng chọn hình thức thanh toán.', trigger: 'change' },
    ],
})

// Fetch provinces data
const provinces = ref<ProvinceData[]>([]);
const listProvinces = computed(() => (
    provinces.value.map(item => {
        return {
            value: {
                name: item.name,
                code: item.code,
            },
            label: item.name,
        }
    })
));
const districts = ref<DistrictData[]>([]);
const listDistricts = computed(() => (
    districts.value.map(item => {
        return {
            value: {
                name: item.name,
                code: item.code,
            },
            label: item.name,
        }
    })
));
const wards = ref<WardData[]>([]);
const listWards = computed(() => (
    wards.value.map(item => {
        return {
            value: {
                name: item.name,
                code: item.code,
            },
            label: item.name,
        }
    })
));

const setAddress = () => {
    const province = find(provinces.value, { code: ruleForm.province }) as ProvinceData;
    const district = find(districts.value, { code: ruleForm.district }) as DistrictData;
    const ward = find(wards.value, { code: ruleForm.ward }) as WardData;

    ruleForm.receiver_address = join([ward?.name, district?.name, province?.name], ', ');
}

const loadDistricts = async () => {
    const res = await getDistricts(ruleForm.province!);
    districts.value = res;
}

const loadWards = async () => {
    const res = await getWards(ruleForm.district!);
    wards.value = res;
}

const searchProvinces = (query: string) => {

}

const searchDistricts = (query: string) => {

}

const searchWards = (query: string) => {

}

const handlePaymentChange = () => {
    if (ruleForm.payment === PaymentMethod.Banking) {
        ElMessage.info('Chức năng này đang được phát triển!');
        ruleForm.payment = PaymentMethod.Cash;
    }
}

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;

    formEl.validate(async (valid) => {
        if (!valid) return false;

        const loading = ElLoading.service({
            lock: true,
            text: 'Đang xử lý...',
        });

        try {
            const res = await checkout(cartId!, accessToken!, {
                key: cartKey,
                receiver_name: ruleForm.receiver_name,
                receiver_phone_number: ruleForm.receiver_phone_number,
                receiver_address: ruleForm.receiver_address,
            });
            setCartIdAndKey(null, null);
            cart.products = [];

            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 5000,
            });
            router.push({
                path: '/user/order/information',
            });
        } catch (err: any) {
            ElNotification.success({
                title: 'Error',
                message: err.data.message,
                showClose: false,
                duration: 5000,
            });
        }

        loading.close();
    });
}

onBeforeMount(async () => {
    const res = await getProvinces();
    provinces.value = res;
});
</script>

<style scoped>

</style>