<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Cập nhật thông tin tài khoản</h1>
    <ElForm ref="ruleFormRef" v-loading="loading" class="flex space-x-[5rem] mt-[3rem]" :model="ruleForm"
        :rules="rules">
        <ElFormItem label="Avatar" class="w-[calc(30%-2.5rem)]">
            <div class="flex flex-col justify-center items-center space-y-[2rem]">
                <ElUpload :show-file-list="false" :auto-upload="false" class="flex flex-col-reverse justify-center"
                    accept="image/jpeg, image/jpg, image/png" :on-change="handleAvatarChange">
                    <div class="w-[10rem] aspect-square rounded-full border-[0.4rem] border-purple overflow-hidden">
                        <ElImage :src="avatar" lazy class="w-full h-full object-cover" />
                    </div>
                    <template v-if="!isEditAvatar" #trigger>
                        <button class="btn-primary mt-[2rem]" type="button">Chọn ảnh</button>
                    </template>
                </ElUpload>
                <Transition name="bounce">
                    <div v-if="isEditAvatar" class="flex space-x-[2rem]">
                        <button class="btn-primary" @click.prevent="handleAvatarUpload">Lưu ảnh</button>
                        <button class="btn-cancel" @click.prevent="cancelEditAvatar">Hủy</button>
                    </div>
                </Transition>
            </div>
        </ElFormItem>
        <div class="w-[calc(70%-2.5rem)]">
            <ElFormItem label="Username" prop="username">
                <ElInput v-model="ruleForm.username" placeholder="Nhập Username" />
            </ElFormItem>
            <ElFormItem>
                <button class="btn-primary" @click.prevent="handleUpdateProfile(ruleFormRef)">
                    <span>Submit</span>
                </button>
            </ElFormItem>
        </div>
    </ElForm>
</template>

<script setup lang="ts">
import { ref, reactive, onBeforeMount } from 'vue';
import { useAdminStore } from '@/stores/admin/Admin';
import { getUserDetail } from '@/api/user/User';
import type { UserData } from '@/types/Type';
import { ElLoading, ElMessage, ElNotification, type FormInstance, type FormRules, type UploadProps } from 'element-plus';
import { updateUserAvatar } from '@/api/user/UpdateUserAvatar';
import { updateUser } from '@/api/user/UpdateUser';

interface Props {
    id: number,
}

const props = defineProps<Props>();
const { accessToken } = useAdminStore();
const loading = ref<boolean>(false);

const userData = reactive<UserData>({
    id: 0,
    username: '',
    email: '',
    is_verified: '',
    status: '',
    avatar: {
        url: '',
        expires_at: '',
        id: 0,
    },
    roles: []
});
const setUserData = (data: UserData) => {
    userData.admin = data.admin;
    userData.avatar = data.avatar;
    userData.customer = data.customer;
    userData.email = data.email;
    userData.id = data.id;
    userData.is_verified = data.is_verified;
    userData.roles = data.roles;
    userData.status = data.status;
    userData.username = data.username;
    avatar.value = data.avatar.url;
    ruleForm.username = data.username;
}

const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive({
    username: '',
});
const rules = reactive<FormRules>({
    username: [
        { required: true, message: 'Vui lòng nhận tên người dùng.' },
        { min: 6, max: 20, message: 'Tên người dùng phải từ 6 - 20 ký tự.' },
    ],
})

// Hanlde avatar upload
const avatar = ref<string>(userData.avatar.url);
const avatarFile = ref();
const isEditAvatar = ref<boolean>(false);

const handleAvatarChange: UploadProps['onChange'] = (uploadFile) => {
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

const cancelEditAvatar = () => {
    avatar.value = userData.avatar.url;
    isEditAvatar.value = false;
}

const handleAvatarUpload = async () => {
    const { avatar } = userData;
    const formData = new FormData();
    formData.append('avatar', avatarFile.value);

    try {
        const res = await updateUserAvatar(props.id, accessToken!, formData);
        avatar.id = res.data.id;
        avatar.expires_at = res.data.expires_at;
        avatar.url = res.data.url;

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

const handleUpdateProfile = (formEl: FormInstance | undefined) => {
    if (!formEl) return;

    formEl.validate(async (valid) => {
        if (!valid) return false;

        try {
            const loading = ElLoading.service({
                lock: true,
                text: 'Đang xử lý...',
            });
            const res = await updateUser(props.id, accessToken!, ruleForm);
            setUserData(res);

            ElNotification.success({
                title: 'Success',
                message: 'Cập nhật thông tin thành công.',
                showClose: false,
                duration: 2000,
            });
            loading.close();
        } catch (err: any) {
            console.log(err);
        }
    });
}

onBeforeMount(async () => {
    loading.value = true;
    const res = await getUserDetail(props.id, accessToken!);
    setUserData(res.data);
    loading.value = false;
})
</script>

<style scoped>

</style>