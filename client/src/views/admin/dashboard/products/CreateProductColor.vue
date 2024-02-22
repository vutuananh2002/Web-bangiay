<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] font-medium uppercase">Thêm màu sản phẩm mới</h1>
        <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="150px" class="mt-[3rem]"
            :size="formSize" status-icon>
            <ElFormItem label="Màu sản phẩm" prop="color_code">
                <ElColorPicker v-model="ruleForm.color_code" size="large"/>
            </ElFormItem>
            <ElFormItem>
                <button v-ripple class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">Submit</button>
                <button v-ripple class="btn-cancel m-4" @click.prevent="resetForm(ruleFormRef)">Reset</button>
            </ElFormItem>
        </ElForm>
    </div>
</template>

<script setup lang="ts">
import { ElColorPicker, ElNotification, type FormInstance, type FormRules } from 'element-plus';
import { ref, reactive } from 'vue';
import { createColor } from '@/api/colors/Colors';
import { useAdminStore } from '@/stores/admin/Admin';
import router from '@/router';

const { accessToken } = useAdminStore();
const formSize = ref<string>('default');
const ruleFormRef = ref<FormInstance>();

const ruleForm = reactive({
    color_code: '',
});
const rules = reactive<FormRules>({
    color_code: [
        { type: 'string', required: true, message: 'Vui lòng chọn màu sản phẩm cần thêm.', trigger: 'change' },
    ]
});

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.validate(async valid => {
        if (!valid) return false;
        try {
            const res = await createColor(accessToken!, ruleForm);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            resetForm(formEl);
            router.push({ path: '/admin/dashboard/product-colors' });
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