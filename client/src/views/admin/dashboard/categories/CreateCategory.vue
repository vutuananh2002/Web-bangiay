<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] font-medium uppercase">Thêm danh mục mới</h1>
        <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="150px" class="mt-[3rem]"
            :size="formSize" status-icon>
            <ElFormItem label="Tên danh mục" prop="name">
                <ElInput v-model="ruleForm.name" placeholder="Nhập tên danh mục"
                    class="w-[30rem]" @input="generateSlug(ruleForm.name)" :error="errorMessages?.name?.[0]" />
            </ElFormItem>
            <ElFormItem label="Slug" prop="slug">
                <ElInput v-model="ruleForm.slug" placeholder="Nhập slug danh mục" :error="errorMessages?.slug?.[0]" />
            </ElFormItem>
            <ElFormItem>
                <button v-ripple class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">Submit</button>
                <button v-ripple class="btn-cancel m-4" @click.prevent="resetForm(ruleFormRef)">Reset</button>
            </ElFormItem>
        </ElForm>
    </div>
</template>

<script setup lang="ts">
import { ElNotification, type FormInstance, type FormRules } from 'element-plus';
import { ref, reactive } from 'vue';
import { createCategory } from '@/api/categories/Categories';
import { useAdminStore } from '@/stores/admin/Admin';
import router from '@/router';
import { useDebounceFn } from '@vueuse/shared';
import { SlugGenerator } from '@/helpers/SlugGenerator';
import type { CategoryValidationError } from '@/types/Type';

const { accessToken } = useAdminStore();

const formSize = ref<string>('default');
const ruleFormRef = ref<FormInstance>();
const errorMessages = ref<CategoryValidationError>();

const ruleForm = reactive({
    name: '',
    slug: '',
});
const rules = reactive<FormRules>({
    name: [
        { type: 'string', required: true, message: 'Vui lòng nhập tên danh mục.', trigger: 'blur' },
    ],
    slug: [
        { type: 'string', required: true, message: 'Vui lòng nhập slug của danh mục.', trigger: 'blur' },
    ],
});

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.validate(async valid => {
        if (!valid) return false;
        try {
            const res = await createCategory(accessToken!, ruleForm);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            resetForm(formEl);
            router.push({ path: '/admin/dashboard/categories' });
        } catch (err: any) {
            errorMessages.value = err.errors;
            ElNotification.error({
                title: 'Error',
                message: err.message,
                showClose: false,
                duration: 2000,
            });
        }
    });
}

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.resetFields();
}

const generateSlug = useDebounceFn((name: string) => {
    const slug = new SlugGenerator(name);
    ruleForm.slug = slug.getSlug();
}, 500, { maxWait: 1000 });
</script>

<style scoped>

</style>