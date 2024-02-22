<template>
    <ElCard class="w-full">
        <template #header>
            <div class="flex flex-col space-y-[0.5rem]">
                <h3 class="text-4xl font-bold">Hồ sơ của tôi</h3>
                <span class="text-2xl font-light text-gray">Quản lý thông tin để bảo mật tài khoản</span>
            </div>
        </template>
        <div class="flex justify-between space-x-[5rem]">
            <ElForm class="w-[calc(60%-5rem)] flex flex-col space-y-[3rem]" ref="ruleFormRef" :model="ruleForm" :rules="rules" status-icon>
                <ElFormItem label="Username" prop="username">
                    <h3 v-if="!isEdit" class="" v-text="profile.username" />
                    <ElInput v-else v-model="ruleForm.username" />
                </ElFormItem>
                <ElFormItem label="Họ và tên" prop="fullname">
                    <h3 v-if="!isEdit" class="" v-text="profile.customer?.full_name" />
                    <div v-else class="flex space-x-[2rem]">
                        <ElFormItem prop="first_name">
                            <ElInput v-model="ruleForm.first_name" placeholder="Nhập họ" @change="setFullname" />
                        </ElFormItem>
                        <ElFormItem prop="last_name">
                            <ElInput v-model="ruleForm.last_name" placeholder="Nhập tên" @change="setFullname" />
                        </ElFormItem>
                    </div>
                </ElFormItem>
                <ElFormItem label="Giới tính" prop="gender">
                    <h3 v-if="!isEdit" class="" v-text="profile.customer?.gender" />
                    <ElRadioGroup v-else v-model="ruleForm.gender">
                        <ElRadio :label="CustomerGender.male">
                            <span>Nam</span>
                        </ElRadio>
                        <ElRadio :label="CustomerGender.female">
                            <span>Nữ</span>
                        </ElRadio>
                    </ElRadioGroup>
                </ElFormItem>
                <ElFormItem label="Email">
                    <div class="flex space-x-[1rem] justify-center items-center">
                        <h3 class="" v-text="profile.email" />
                        <RouterLink to="/user/account/change-email"
                            class="text-[1.2rem] text-gray underline hover:text-purple transition-all">Thay đổi email
                        </RouterLink>
                    </div>
                </ElFormItem>
                <ElFormItem label="Địa chỉ" prop="address">
                    <h3 v-if="!isEdit" class="" v-text="profile.customer?.address" />
                    <div v-else class="flex space-x-[2rem]">
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
                <ElFormItem label="Số điện thoại" prop="phone_number">
                    <h3 v-if="!isEdit" class="" v-text="profile.customer?.phone_number" />
                    <ElInput v-else v-model="ruleForm.phone_number" />
                </ElFormItem>
                <ElFormItem>
                    <TransitionGroup name="list">
                        <button v-if="!isEdit" class="btn-primary m-4" @click.prevent="enableEdit">Sửa thông
                            tin</button>
                        <button v-if="isEdit" class="btn-primary m-4"
                            @click.prevent="handleUpdateProfile(ruleFormRef)">Lưu</button>
                        <button v-if="isEdit" class="btn-cancel m-4" @click.prevent="cancelEdit">Hủy</button>
                    </TransitionGroup>
                </ElFormItem>
            </ElForm>
            <div class="w-[calc(40%-5rem)] flex flex-col items-center space-y-[1.5rem]">
                <ElUpload :show-file-list="false" :auto-upload="false" class="flex flex-col-reverse justify-center"
                    accept="image/jpeg, image/jpg, image/png" :on-change="handleAvatarChange">
                    <div class="w-[10rem] aspect-square rounded-full border-[0.4rem] border-purple overflow-hidden">
                        <ElImage :src="avatar" lazy class="w-full h-full object-cover" />
                    </div>
                    <template #trigger v-if="!isEditAvatar">
                        <button class="btn-primary mt-[2rem]">Chọn ảnh</button>
                    </template>
                </ElUpload>
                <Transition name="bounce">
                    <div v-if="isEditAvatar" class="flex space-x-[2rem]">
                        <button class="btn-primary" @click.prevent="handleAvatarUpload">Lưu ảnh</button>
                        <button class="btn-cancel" @click.prevent="cancelEditAvatar">Hủy</button>
                    </div>
                </Transition>
            </div>
        </div>
    </ElCard>
</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/user/User';
import { ElMessage, ElNotification, type FormInstance, type FormRules, type UploadFile, type UploadInstance, type UploadProps } from 'element-plus';
import { ref, reactive, onBeforeMount, computed, watch } from 'vue';
import { CustomerGender } from '@/types/Enum';
import { getDistricts, getProvinces, getWards } from '@/api/provinces/Provinces';
import type { ProvinceData, DistrictData, WardData } from '@/types/Type';
import { find, get, join, omit, split } from 'lodash';
import { updateUserAvatar } from '@/api/user/UpdateUserAvatar';
import { updateUser } from '@/api/user/User';
import { updateCustomer } from '@/api/customer/Customer';

const { profile, accessToken, setUserData } = useUserStore();
const avatar = ref<string>(profile.avatar.url);

// Validation form
const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive({
    username: profile.username,
    first_name: '',
    last_name: '',
    fullname: profile.customer!.full_name,
    gender: profile.customer!.gender === 'Nam' ? 1 : 0,
    province: null,
    district: null,
    ward: null,
    address: profile.customer!.address,
    phone_number: profile.customer!.phone_number,
});
const rules = reactive<FormRules>({
    username: [
        { required: true, message: 'Vui lòng nhập tên người dùng.', trigger: 'blur' },
        { min: 6, max: 20, message: 'Tên người dùng phải từ 6 - 20 ký tự.', trigger: 'blur' }
    ],
    first_name: [
        { required: true, message: 'Vui lòng nhập họ.', trigger: 'blur'},
    ],
    last_name: [
        { required: true, message: 'Vui lòng nhập tên.', trigger: 'blur' },
    ],
    fullname: [
        { required: true, message: 'Vui lòng nhập họ tên.'},
    ],
    gender: [
        { required: true, message: 'Vui lòng chọn giới tính.', trigger: 'change' },
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
    address: [
        { required: true, message: 'Vui lòng điền địa chỉ.' },
    ],
    phone_number: [
        { required: true, message: 'Vui lòng nhập số điện thoại.', trigger: 'blur' }
    ]
});

const setRuleFormData = () => {
    ruleForm.username = profile.username,
    ruleForm.fullname = profile.customer!.full_name,
    ruleForm.first_name = split(profile.customer!.full_name, ' ')?.[0],
    ruleForm.last_name = split(profile.customer!.full_name, ' ')?.[1],
    ruleForm.gender =  profile.customer!.gender === 'Nam' ? 1 : 0;
    ruleForm.address = profile.customer!.address;
    ruleForm.phone_number = profile.customer!.phone_number;
}

const setFullname = () => {
    ruleForm.fullname = join([ruleForm.first_name, ruleForm.last_name], ' ');
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

// Handle update profile
const isEdit = ref<boolean>(false);
const enableEdit = () => {
    isEdit.value = true;
}
const cancelEdit = () => {
    isEdit.value = false;
}

const handleUpdateProfile = async (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.validate(async (valid) => {
        if (!valid) return false;

        const splitFullname = split(ruleForm.fullname, ' ');

        try {
            await updateCustomer(profile.customer!.id, accessToken!, {
                ...omit(ruleForm, ['fullname', 'username', 'province', 'district', 'ward']),
            });
            const res = await updateUser(profile.id, accessToken!, {
                username: get(ruleForm, ['username']),
            });

            setUserData(res);
            ElNotification.success({
                title: 'Success',
                message: 'Cập nhật thông tin thành công.',
                showClose: false,
                duration: 2000,
            })
            isEdit.value = false;
        } catch (err: any) {
            console.log(err);
        }
    });
}

// Handle update avatar
const avatarFile = ref();
const isEditAvatar = ref<boolean>(false);
const cancelEditAvatar = () => {
    avatar.value = profile.avatar.url;
    isEditAvatar.value = false;
}

const handleAvatarChange: UploadProps['onChange'] = (uploadFile: UploadFile) => {
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

    avatarFile.value = uploadFile.raw!;
    avatar.value = URL.createObjectURL(avatarFile.value);
    isEditAvatar.value = true;
}

const handleAvatarUpload = async () => {
    const { id, avatar } = profile;
    const formData = new FormData();
    formData.append('avatar', avatarFile.value);

    try {
        const res = await updateUserAvatar(id, accessToken!, formData);
        avatar.url = res.data.url;
        avatar.id = res.data.id;
        avatar.expires_at = res.data.expires_at;

        ElNotification.success({
            title: 'Success',
            message: res.message,
            showClose: false,
            duration: 2000,
        });
        isEditAvatar.value = false;
    } catch (err: any) {
        ElNotification.error({
            title: 'Error',
            message: err.message,
            showClose: false,
            duration: 2000,
        });
    }
}

onBeforeMount(async () => {
    const res = await getProvinces();
    provinces.value = res;

    watch(profile, () => {
        avatar.value = profile.avatar.url;
        setRuleFormData();
    });
});
</script>

<style scoped>

</style>