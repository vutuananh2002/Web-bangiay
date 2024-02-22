<template>
    <ElCard class="w-full">
        <template #header>
            <h3 class="text-4xl font-bold">Thay đổi địa chỉ Email</h3>
        </template>
        <div class="">
            <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules">
                <ElFormItem label="Địa chỉ Email mới" prop="email">
                    <ElInput v-model="ruleForm.email" type="email" placeholder="Nhập địa chỉ email mới" />
                </ElFormItem>
                <ElFormItem>
                    <button class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">
                        <span>Submit</span>
                    </button>
                    <button class="btn-cancel m-4" @click.prevent="resetForm(ruleFormRef)">
                        <span>Reset</span>
                    </button>
                </ElFormItem>
                <Transition name="bounce">
                    <ElAlert v-show="message" :title="message" show-icon :closable="false" />
                </Transition>
            </ElForm>
        </div>
    </ElCard>
</template>

<script setup lang="ts">
import { resetEmail } from "@/api/auth/Email";
import { ElLoading, type FormInstance, type FormRules } from "element-plus";
import { ref, reactive } from "vue";
import { useUserStore } from "@/stores/user/User";

const { accessToken } = useUserStore();

const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive({
    email: '',
});

const validateEmail = (rule: any, value: any, callback: any) => {
    const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const isValidEmail = emailRegex.test(value);

    if (value === '') {
        callback(new Error('Vui lòng điền email.'));
        return;
    } 

    if (!isValidEmail) {
        callback(new Error('Địa chi email không hợp lệ.'));
        return;
    }

    callback();
}

const rules = reactive<FormRules>({
    email: { required: true, validator: validateEmail, trigger: 'blur' },
});

const message = ref<string>('');
const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;

    formEl.validate(async (valid) => {
        if (!valid) return false;

        try {
            const loading = ElLoading.service({
                lock: true,
                fullscreen: true,
                text: 'Đang xử lý...',
            });
            const res = await resetEmail(accessToken!, ruleForm);
            message.value = res.message;
            loading.close();
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