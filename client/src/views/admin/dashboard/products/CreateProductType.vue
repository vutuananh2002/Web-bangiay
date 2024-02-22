<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] font-medium uppercase">Thêm loại sản phẩm mới</h1>
        <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="150px" class="mt-[3rem]" :size="formSize" status-icon>
            <ElFormItem label="Loại sản phẩm" prop="type">
                <ElSelectV2 v-model="ruleForm.type_code" placeholder="Chọn loại sản phẩm cần thêm" :options="typeOptions" class="w-[30rem]" />
            </ElFormItem>
            <ElFormItem label="Slug" prop="slug">
                <ElInput v-model="ruleForm.slug" placeholder="Nhập slug loại sản phẩm" />
            </ElFormItem>
            <ElFormItem>
                <button v-ripple class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">Submit</button>
                <button v-ripple class="btn-cancel m-4" @click.prevent="resetForm(ruleFormRef)">Reset</button>
            </ElFormItem>
        </ElForm>
    </div>
</template>

<script setup lang="ts">
import type { ListItem } from '@/types/Type';
import { ElNotification, ElSelectV2, type FormInstance, type FormRules } from 'element-plus';
import { ref, reactive } from 'vue';
import { createType } from '@/api/types/Types';
import { useAdminStore } from '@/stores/admin/Admin';
import router from '@/router';

const { accessToken } = useAdminStore();
const formSize = ref<string>('default');
const ruleFormRef = ref<FormInstance>();

const ruleForm = reactive({
    type_code: '',
    slug: '',
});
const rules = reactive<FormRules>({
    type_code: [
        { type: 'number', required: true, message: 'Vui lòng chọn loại sản phẩm cần thêm', trigger: 'change' },
    ],
    slug: [
        { type: 'string', required: true, message: 'Vui lòng nhập slug của loại sản phẩm.', trigger: 'blur' },
    ],
});
const typeOptions = reactive<ListItem[]>([
    {
        value: 0,
        label: 'Nam'
    },
    {
        value: 1,
        label: 'Nữ',
    },
    {
        value: 2,
        label: 'Trẻ em'
    }
]);

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.validate(async valid => {
        if (!valid) return false;
        try {
            const res = await createType(accessToken!, ruleForm);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            resetForm(formEl);
            router.push({ path: '/admin/dashboard/product-types' });
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