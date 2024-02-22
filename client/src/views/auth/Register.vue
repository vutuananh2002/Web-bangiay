<template>
    <div class="w-full h-full flex justify-center items-center overflow-hidden before:absolute before:w-[60rem] before:h-[60rem] before:bg-purple before:top-[-40%] before:left-[-20%] before:rounded-full before:opacity-40 before:blur-lg after:absolute after:w-[60rem] after:h-[60rem] after:bottom-[-40%] after:right-[-15%] after:bg-blue after:opacity-40 after:blur-lg after:rounded-full after:z-[-1]">
        <ElCard class="w-[90%]">
            <template #header>
                <h2 class="text-[2rem] font-medium uppercase text-purple">Đăng ký tài khoản</h2>
            </template>
            <ElForm ref="ruleFormRef"  :model="ruleForm" :rules="rules"  class="flex space-x-[5rem] justify-start items-start">
                <ElFormItem label="Avatar" prop="avatar">
                    <ElUpload list-type="picture-card" :show-file-list="false" :auto-upload="false"
                        :on-change="handleAvatarChange"
                    >
                        <ElImage v-if="avatarUrl" :src="avatarUrl" />
                        <ElIcon v-else>
                            <Plus/>
                        </ElIcon>
                    </ElUpload>
                </ElFormItem>
                <div class="w-[70%]">
                    <ElFormItem label="Username" prop="username" :error="errorMessages?.username?.[0]">
                        <ElInput v-model="ruleForm.username" placeholder="Nhập tên người dùng" />
                    </ElFormItem>
                    <ElFormItem label="Email" prop="email" :error="errorMessages?.email?.[0]">
                        <ElInput  v-model="ruleForm.email" type="email" placeholder="Nhập Email" />
                    </ElFormItem>
                    <ElFormItem label="Mật khẩu" prop="password" :error="errorMessages?.password?.[0]">
                        <ElInput v-model="ruleForm.password" type="password" show-password placeholder="Nhập mật khẩu" />
                    </ElFormItem>
                    <ElFormItem label="Xác nhận mật khẩu" prop="password_confirmation" :error="errorMessages?.password_confirmation?.[0]">
                        <ElInput v-model="ruleForm.password_confirmation" type="password" show-password placeholder="Xác nhận mật khẩu" />
                    </ElFormItem>
                    <ElFormItem label="Họ" prop="first_name" :error="errorMessages?.first_name?.[0]">
                        <ElInput v-model="ruleForm.first_name"  placeholder="Nhập họ" />
                    </ElFormItem>
                    <ElFormItem label="Tên" prop="last_name" :error="errorMessages?.last_name?.[0]">
                        <ElInput v-model="ruleForm.last_name" placeholder="Nhập tên" />
                    </ElFormItem>
                    <ElFormItem label="Giới tính" prop="gender" :error="errorMessages?.gender?.[0]">
                        <ElRadioGroup v-model="ruleForm.gender">
                            <ElRadio :label="CustomerGender.male">
                                <span>Nam</span>
                            </ElRadio>
                            <ElRadio :label="CustomerGender.female">
                                <span>Nữ</span>
                            </ElRadio>
                        </ElRadioGroup>
                    </ElFormItem>
                    <ElFormItem label="Địa chỉ" prop="address" :error="errorMessages?.address?.[0]">
                        <div class="flex space-x-[1rem]">
                            <ElFormItem prop="province">
                                <ElSelectV2 v-model="ruleForm.province" :options="listProvinces" value-key="value.code"
                                    filterable remote :remote-method="searchProvinces"
                                    placeholder="Chọn tỉnh,thành phố" @change="loadDistricts" />
                            </ElFormItem>
                            <ElFormItem prop="district">
                                <ElSelectV2 v-model="ruleForm.district" :options="listDistricts" value-key="value.code"
                                    filterable remote :remote-method="searchDistricts"
                                    placeholder="Chọn quận, huyện" @change="loadWards" />
                            </ElFormItem>
                            <ElFormItem prop="ward">
                                <ElSelectV2 v-model="ruleForm.ward" :options="listWards" value-key="value.code"
                                    filterable remote :remote-method="searchWards"
                                    placeholder="Chọn xã, phường, thị trấn" @change="setAddress" />
                            </ElFormItem>
                        </div>
                    </ElFormItem>
                    <ElFormItem label="Số điện thoại" prop="phone_number" :error="errorMessages?.phone_number?.[0]">
                        <ElInput v-model="ruleForm.phone_number" placeholder="Nhập số điện thoại" />
                    </ElFormItem>
                    <ElFormItem>
                        <button class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">
                            <span>Đăng ký</span>
                        </button>
                        <button class="btn-cancel m-4" @click.prevent="resetForm(ruleFormRef)">
                            <span>Reset</span>
                        </button>
                    </ElFormItem>
                </div>
            </ElForm>
        </ElCard>
    </div>
</template>

<script setup lang="ts">
import { getDistricts, getProvinces, getWards } from '@/api/provinces/Provinces';
import { register } from '@/api/user/auth/Auth';
import router from '@/router';
import { CustomerGender } from '@/types/Enum';
import type { DistrictData, ProvinceData, RegisterCredentials, WardData, RegisterCredentialsValidationError } from '@/types/Type';
import { Plus } from '@element-plus/icons-vue';
import { ElMessage, ElNotification, type FormInstance, type FormRules, type UploadProps } from 'element-plus';
import { find, join, omit } from 'lodash';
import { ref, reactive, computed, onBeforeMount } from 'vue';

const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive<RegisterCredentials>({
    avatar: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    first_name: '',
    last_name: '',
    gender: 1,
    province: null,
    district: null,
    ward: null,
    address: '',
    phone_number: '',
});
const rules = reactive<FormRules>({
    avatar: [
        { required: true, message: 'Vui lòng chọn avatar.', trigger: 'change' },
    ],
    username: [
        { required: true, message: 'Vui lòng nhập tên người dùng.', trigger: 'blur' },
        { min: 6, max: 20, message: 'Tên người dùng phải từ 6 - 20 ký tự.', trigger: 'blur' }
    ],
    email: [
        { type: 'email', required: true, message: 'Vui lòng nhập email', trigger: 'blur' },
    ],
    password: [
        { required: true, message: 'Vui lòng nhập mật khẩu.', trigger: 'blur'},
    ],
    password_confirmation: [
        { required: true, message: 'Vui lòng xác nhận mật khẩu.', trigger: 'blur' },
    ],
    first_name: [
        { required: true, message: 'Vui lòng nhập họ.', trigger: 'blur' }
    ],
    last_name: [
        { required: true, message: 'Vui lòng nhập tên.', trigger: 'blur' },
    ],
    gender: [
        { required: true, message: 'Vui lòng chọn giới tính.', trigger: 'blur' },
    ],
    province: [
        { required: true, message: 'Vui lòng chọn tỉnh, thành phố.', trigger: 'change' },
    ],
    district: [
        { required: true, message: 'Vui lòng chọn quận, huyện.', trigger: 'change' },
    ],
    ward: [
        { required: true, message: 'Vui lòng chọn xã, phường, thị trấn.', trigger: 'change' },
    ],
    phone_number: [
        { required: true, message: 'Vui lòng nhập số điện thoại.', trigger: 'blur' },
    ]
})

const avatarUrl = ref('');
const handleAvatarChange: UploadProps['onChange'] = (uploadFile, uploadFiles) => {
    const allowedAvatarType = ['image/jpeg', 'image/png', 'image/png'];
    const avatarSize = uploadFile.raw!.size / 1024 / 1024;

    if (!allowedAvatarType.includes(uploadFile.raw!.type)) {
        ElMessage.error('Avatar phải thuộc định dạng JPEG, JPG, PNG!');
        return;
    }

    if (avatarSize > 5) {
        ElMessage.error('Dung lượng avatar phải nhỏ hơn 5MB!');
        return;
    }

    avatarUrl.value = URL.createObjectURL(uploadFile.raw!);
    ruleForm.avatar = uploadFile.raw!;
}

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

    ruleForm.address = join([ward?.name, district?.name, province?.name], ', ');
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

const createFormData = () => {
    const formData = new FormData();

    formData.append('username', ruleForm.username);
    formData.append('email', ruleForm.email);
    formData.append('password', ruleForm.password);
    formData.append('password_confirmation', ruleForm.password_confirmation);
    formData.append('avatar', ruleForm.avatar);
    formData.append('first_name', ruleForm.first_name);
    formData.append('last_name', ruleForm.last_name);
    formData.append('gender', ruleForm.gender.toString());
    formData.append('address', ruleForm.address);
    formData.append('phone_number', ruleForm.phone_number);

    return formData;
}

const errorMessages = ref<RegisterCredentialsValidationError>();
const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;

    formEl.validate(async (valid) => {
        if (!valid) return false;

        try {
            const res = await register(createFormData());
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            router.push({
                path: `/auth/send-email-verification/${res.data.access_token.token}`,
            });
        } catch (err: any) {
            errorMessages.value = err.data.errors;
        }
    });
}

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    avatarUrl.value = '';
    formEl.resetFields();
}

onBeforeMount(async () => {
    const res = await getProvinces();
    provinces.value = res;
});
</script>

<style lang="scss" scoped>

</style>