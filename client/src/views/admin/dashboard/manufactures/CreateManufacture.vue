<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] font-medium uppercase">Thêm nhà sản xuất</h1>
        <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="260px" class="mt-[3rem]" status-icon>
            <ElFormItem label="Tên nhà sản xuất" prop="name" :error="errorMessages?.name?.[0]">
                <ElInput v-model="ruleForm.name" placeholder="Nhập tên nhà sản xuất"
                    @input="generateSlug(ruleForm.name)" />
            </ElFormItem>
            <ElFormItem label="Địa chỉ nhà sản xuất" prop="address" :error="errorMessages?.address?.[0]">
                <ElInput v-model="ruleForm.address" placeholder="Nhập địa chỉ nhà sản xuất" />
            </ElFormItem>
            <ElFormItem label="Số điện thoại nhà sản xuất" prop="phone_number"
                :error="errorMessages?.phone_number?.[0]">
                <ElInput v-model="ruleForm.phone_number" placeholder="Nhập số điện thoại nhà sản xuất" />
            </ElFormItem>
            <ElFormItem label="Email nhà sản xuất" prop="email" :error="errorMessages?.email?.[0]">
                <ElInput v-model="ruleForm.email" placeholder="Nhập email nhà sản xuất" />
            </ElFormItem>
            <ElFormItem label="Thông tin nhà sản xuất" prop="information" :error="errorMessages?.information?.[0]">
                <ElInput v-model="ruleForm.information" placeholder="Nhập thông tin nhà sản xuất" type="textarea"
                    maxlength="300" show-word-limit :autosize="textareaSize" />
            </ElFormItem>
            <ElFormItem label="Logo" prop="logo">
                <ElUpload ref="upload" v-model:file-list="ruleForm.logo" list-type="picture-card" :auto-upload="false"
                    :limit="1" :on-exceed="handleImageExceed" accept="image/jpg, image/jpeg, image/png"
                    :on-remove="handleImageRemove" :on-preview="handleImagePreview" :on-change="handleImageChange">
                    <ElIcon>
                        <Plus />
                    </ElIcon>
                </ElUpload>
                <ElDialog v-model="dialogVisible">
                    <img w-full :src="dialogImageUrl" alt="Preview Image">
                </ElDialog>
            </ElFormItem>
            <ElFormItem label="Slug" prop="slug">
                <ElInput v-model="ruleForm.slug" placeholder="Nhập slug nhà sản xuất" />
            </ElFormItem>
            <ElFormItem>
                <button class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">Submit</button>
                <button class="btn-cancel m-4" @click.prevent="resetForm(ruleFormRef)">Reset</button>
            </ElFormItem>
        </ElForm>
    </div>
</template>

<script setup lang="ts">
import { createManufacture } from '@/api/manufactures/Manufactures';
import { SlugGenerator } from '@/helpers/SlugGenerator';
import type { UpdateManufactureData, ManufactureValidationError } from '@/types/Type';
import { useDebounceFn } from '@vueuse/shared';
import { ElMessage, ElNotification, genFileId, type FormInstance, type FormRules, type UploadInstance, type UploadProps, type UploadRawFile } from 'element-plus';
import { ref, reactive } from 'vue';
import { Plus } from '@element-plus/icons-vue';
import { useAdminStore } from '@/stores/admin/Admin';
import router from '@/router';
import { dialogImageUrl, dialogVisible, handleImagePreview, handleImageRemove } from '@/helpers/ImageUpload';

const { accessToken } = useAdminStore();

const errorMessages = ref<ManufactureValidationError>();

const textareaSize = ref({
    minRows: 4,
    maxRows: 10
});
const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive<UpdateManufactureData>({
    name: '',
    address: '',
    phone_number: '',
    email: '',
    information: '',
    slug: '',
    logo: [],
});
const rules = reactive<FormRules>({
    name: [
        { type: 'string', required: true, message: 'Vui lòng nhập tên nhà sản xuất.', trigger: 'blur' },
    ],
    address: [
        { type: 'string', required: true, message: 'Vui lòng nhập địa chỉ nhà sản xuất.', trigger: 'blur' },
    ],
    phone_number: [
        { type: 'string', required: true, message: 'Vui lòng số điện thoại nhà sản xuất.', trigger: 'blur' },
    ],
    email: [
        { type: 'email', required: true, message: 'Vui lòng nhập email nhà sản xuất.', trigger: 'blur' },
    ],
    information: [
        { type: 'string', required: true, message: 'Vui lòng nhập thông tin nhà sản xuất.', trigger: 'blur' },
    ],
    slug: [
        { type: 'string', required: true, message: 'Vui lòng nhập slug.', trigger: 'blur' },
    ],
    logo: [
        { type: 'array', required: true, message: 'Vui lòng chọn logo.', trigger: 'change' },
    ],
});

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

const generateSlug = useDebounceFn((name: string) => {
    const slug = new SlugGenerator(name);
    ruleForm.slug = slug.getSlug();
});

const createFormData = (): FormData => {
    const formData = new FormData();

    formData.append('name', ruleForm.name);
    formData.append('address', ruleForm.address);
    formData.append('phone_number', ruleForm.phone_number);
    formData.append('email', ruleForm.email);
    formData.append('information', ruleForm.information);
    formData.append('slug', ruleForm.slug);
    formData.append('logo', ruleForm.logo?.[0].raw);

    return formData;
}

const submitForm = (formEl: FormInstance | undefined): void => {
    if (!formEl) return;

    formEl.validate(async (valid) => {
        if (!valid) return false;

        try {
            const res = await createManufacture(accessToken!, createFormData());
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            resetForm(formEl);
            router.push({
                path: '/admin/dashboard/manufactures'
            });
        } catch (err: any) {
            console.log(err);
            errorMessages.value = err.errors;
        }
    });
}

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.resetFields();
}
</script>

<style lang="scss" scoped>

</style>