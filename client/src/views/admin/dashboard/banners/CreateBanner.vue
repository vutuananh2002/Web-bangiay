<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Tạo banner mới</h1>
    <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="150px" status-icon class="mt-[3rem]">
        <ElFormItem label="Tiêu đề" prop="title">
            <ElInput v-model="ruleForm.title" placeholder="Nhập tiêu đề banner" />
        </ElFormItem>
        <ElFormItem label="Ảnh banner" prop="image">
            <ElUpload ref="upload" list-type="picture-card" v-model:file-list="ruleForm.image" :auto-upload="false"
                accept="image/jpeg, image/jpg, image/png" :limit="1" :on-exceed="handleImageExceed"
                :on-remove="handleImageRemove" :on-preview="handleImagePreview" :on-change="handleImageChange"
            >
                <ElIcon>
                    <Plus/>
                </ElIcon>
            </ElUpload>
            <ElDialog v-model="dialogVisible">
                <img :src="dialogImageUrl" alt="Preview Image" class="w-full">
            </ElDialog>
        </ElFormItem>
        <ElFormItem label="Mô tả" prop="description">
            <ElInput v-model="ruleForm.description" type="textarea" maxlength="300" show-word-limit placeholder="Nhập mô tả banner" :rows="5" />
        </ElFormItem>
        <ElFormItem>
            <button class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">Tạo banner</button>
            <button class="btn-cancel m-4" @click.prevent="resetForm(ruleFormRef)">Reset</button>
        </ElFormItem>
    </ElForm>
</template>

<script setup lang="ts">
import { ref, reactive } from "vue";
import { createBanner } from "@/api/banner/Banners";
import { useAdminStore } from "@/stores/admin/Admin";
import { ElMessage, ElNotification, genFileId, type FormInstance, type FormRules, type UploadInstance, type UploadProps, type UploadRawFile } from "element-plus";
import { Plus } from "@element-plus/icons-vue";
import { dialogVisible, dialogImageUrl, handleImagePreview, handleImageRemove } from "@/helpers/ImageUpload";
import type { UpdateBannerData } from "@/types/Type";
import router from "@/router";

const { accessToken } = useAdminStore();

// Validation form
const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive<UpdateBannerData>({
    title: '',
    image: [],
    description: '',
});
const rules = reactive<FormRules>({
    title: [
        { required: true, message: 'Vui lòng nhập tiêu đề banner.', trigger: 'blur' }
    ],
    image: [
        { type: 'array', required: true, message: 'Vui lòng chọn ảnh banner.', trigger: 'change' }
    ],
    description: [
        { required: true, message: 'Vui lòng nhập mô tả banner.', trigger: 'blur' }
    ],
});

// Handle image upload
const upload = ref<UploadInstance>();

const handleImageChange: UploadProps['onChange'] = (uploadFile, uploadFiles) => {
    const allowedType = ['image/jpg', 'image/jpeg', 'image/png'];

    if (!uploadFile) {
        ElMessage.error('Vui lòng chọn ảnh!');
        return;
    }

    const fileSize = uploadFile.raw!.size / 1024 / 1024;

    if (!allowedType.includes(uploadFile.raw!.type)) {
        upload.value!.clearFiles();
        ElMessage.error('Ảnh phải thuộc định dạng JPG, JPEG, hoặc PNG!');
        return;
    }

    if (fileSize > 5) {
        upload.value!.clearFiles();
        ElMessage.error('Kích thước ảnh phải nhỏ hơn 5MB!');
        return;
    }
}

const handleImageExceed: UploadProps['onExceed'] = (files) => {
    ElMessage.info('Số ảnh tối đa là 1, ảnh cũ sẽ được ghi đè lên ảnh mới!');
    upload.value!.clearFiles();
    const file = files[0] as UploadRawFile;
    file.uid = genFileId();
    upload.value!.handleStart(file);
}

const createFormData = () => {
    const formData = new FormData();

    formData.append('title', ruleForm.title);
    formData.append('image', ruleForm.image?.[0].raw);
    formData.append('description', ruleForm.description);

    return formData;
}

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;

    formEl.validate(async (valid) => {
        if (!valid) return false;

        try {
            const res = await createBanner(accessToken!, createFormData());
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            resetForm(formEl);
            router.push({
                path: '/admin/dashboard/banner'
            });
        } catch (err: any) {
            console.log(err);
        }
    });
}

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.resetFields();
}
</script>

<style scoped>

</style>