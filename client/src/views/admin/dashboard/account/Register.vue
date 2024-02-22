<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Đăng ký tài khoản Admin</h1>
    <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" status-icon class="mt-[3rem] flex space-x-[5rem]">
        <ElFormItem label="Avatar" prop="avatar" class="w-[calc(30%-2.5rem)] flex items-start">
            <ElUpload list-type="picture-card" :show-file-list="false" :auto-upload="false"
                :on-change="handleAvatarChange">
                <ElImage v-if="avatarUrl" :src="avatarUrl" />
                <ElIcon v-else>
                    <Plus />
                </ElIcon>
            </ElUpload>
        </ElFormItem>
        <div class="w-[calc(70%-2.5rem)]">
            <ElFormItem label="Username" prop="username">
                <ElInput v-model="ruleForm.username" placeholder="Nhập tên người dùng" />
            </ElFormItem>
            <ElFormItem label="Email" prop="email">
                <ElInput type="email" v-model="ruleForm.email"  placeholder="Nhập email" />
            </ElFormItem>
            <ElFormItem label="Mật khẩu" prop="password">
                <ElInput type="password" show-password v-model="ruleForm.password"  placeholder="Nhập mật khẩu" />
            </ElFormItem>
            <ElFormItem label="Xác nhận mật khẩu" prop="password_confirmation">
                <ElInput type="password" show-password v-model="ruleForm.password_confirmation"  placeholder="Xác nhận mật khẩu" />
            </ElFormItem>
            <ElFormItem label="Tên Admin" prop="name">
                <ElInput v-model="ruleForm.name"  placeholder="Nhập tên Admin" />
            </ElFormItem>
            <ElFormItem label="Số điện thoại" prop="phone_number">
                <ElInput v-model="ruleForm.phone_number"  placeholder="Nhập số điện thoại" />
            </ElFormItem>
            <ElFormItem>
                <button class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">
                    <span>Submit</span>
                </button>
                <button class="btn-cancel m-4">
                    <span>Reset</span>
                </button>
            </ElFormItem>
        </div>
    </ElForm>
</template>

<script setup lang="ts">
import { ref, reactive } from "vue";
import { useAdminStore } from "@/stores/admin/Admin";
import { ElMessage, ElNotification, type FormInstance, type FormRules, type UploadProps } from "element-plus";
import { Plus } from "@element-plus/icons-vue";
import { register } from '@/api/admin/auth/Auth';

const { accessToken } = useAdminStore();

const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive({
    avatar: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    name: '',
    phone_number: '',
});
const rules = reactive<FormRules>({
    avatar: [
        { required: true, message: 'Vui lòng chọn avatar', trigger: 'change' },
    ],
    username: [
        { required: true, message: 'Vui lòng nhập tên người dùng.', trigger: 'blur' },
        { min: 6, max: 20, message: 'Tên người dùng phải từ 6 - 20 ký tự' },
    ],
    email: [
        { type: 'email', required: true, message: 'Vui lòng nhập email.', trigger: 'blur' },
    ],
    password: [
        { required: true, message: 'Vui lòng nhập mật khẩu.', trigger: 'blur' },
    ],
    password_confirmation: [
        { required: true, message: 'Vui lòng xác nhận mật khẩu.', trigger: 'blur' },
    ],
    name: [
        { required: true, message: 'Vui lòng nhập tên Admin.', trigger: 'blur' },
    ],
    phone_number: [
        { required: true, message: 'Vui lòng nhập số điện thoại.', trigger: 'blur' },
    ],
});

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

const createFormData = () => {
    const formData = new FormData();
    
    formData.append('username', ruleForm.username);
    formData.append('email', ruleForm.email);
    formData.append('password', ruleForm.password);
    formData.append('password_confirmation', ruleForm.password_confirmation);
    formData.append('name', ruleForm.name);
    formData.append('phone_number', ruleForm.phone_number);
    formData.append('avatar', ruleForm.avatar);

    return formData;
}

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;

    formEl.validate(async (valid) => {
        if (!valid) return false;

        try {
            const res = await register(createFormData(), accessToken!);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
        } catch (err: any) {
            console.log(err);
        }
    });
}
</script>

<style scoped>

</style>