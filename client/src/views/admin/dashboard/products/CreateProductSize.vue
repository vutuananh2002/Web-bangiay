<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] font-medium uppercase">Thêm size sản phẩm mới</h1>
        <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="150px" class="mt-[3rem]"
            :size="formSize" status-icon>
            <ElFormItem label="Size sản phẩm" prop="name">
                <ElInput v-model="ruleForm.name" placeholder="Nhập size sản phẩm"/>
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
import { createSize } from '@/api/sizes/Sizes';
import { useAdminStore } from '@/stores/admin/Admin';
import router from '@/router';

const { accessToken } = useAdminStore();
const formSize = ref<string>('default');
const ruleFormRef = ref<FormInstance>();

const ruleForm = reactive({
    name: '',
});
const rules = reactive<FormRules>({
    name: [
        { type: 'string', required: true, message: 'Vui lòng nhập size sản phẩm.', trigger: 'blur' },
    ]
});

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.validate(async valid => {
        if (!valid) return false;
        try {
            const res = await createSize(accessToken!, ruleForm);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            resetForm(formEl);
            router.push({ path: '/admin/dashboard/product-sizes' });
        } catch (err: any) {
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
</script>

<style scoped>

</style>