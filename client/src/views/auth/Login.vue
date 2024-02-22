<template>
    <section class="login w-full h-full flex justify-center items-center relative text-[#606266] text-[1.4rem] overflow-hidden">
        <Transition name="slide-to-bottom">
            <ElForm v-if="isShow" :model="ruleForm" ref="ruleFormRef" status-icon :rules="rules" label-width="10rem"
                class="login-form w-[45rem] bg-[rgba(136,108,255,.1)] p-[2rem] flex flex-col rounded-lg relative z-10 backdrop-blur-md shadow-lg">
                <div class="login-form__header">
                    <h2
                        class="text-[2.6rem] uppercase font-bold text-center h-[5rem] relative mb-2 truncate text-transparent bg-clip-text bg-purple-gradient leading-10 tracking-wider">
                        Welcome
                    </h2>
                    <h3
                        class="text-center text-[2rem] uppercase font-semibold mb-4 truncate text-transparent bg-clip-text bg-purple-gradient tracking-wider">
                        Đăng nhập
                    </h3>
                </div>
                <ElFormItem label="Email" prop="email">
                    <ElInput type="email" v-model="ruleForm.email" placeholder="Vui lòng điền email" />
                </ElFormItem>
                <ElFormItem label="Mật khẩu" prop="password">
                    <ElInput type="password" v-model="ruleForm.password" placeholder="Vui lòng điền mật khẩu"
                        show-password 
                    />
                </ElFormItem>
                <ElFormItem class="mt-[1rem]">
                    <ElCheckbox v-model="ruleForm.remember_me">
                        <span>Ghi nhớ đăng nhập</span>
                    </ElCheckbox>
                    <RouterLink to="/auth/forgot-password"
                        class="ml-auto text-purple opacity-80 hover:opacity-100 transition-all">
                        Quên mật khẩu?
                    </RouterLink>
                </ElFormItem>
                <p>Bạn chưa có tài khoản?<RouterLink to="/auth/register" class="text-purple opacity-80 hover:opacity-100 transition-all pl-2">Đăng ký</RouterLink></p>
                <Transition name="bounce" appear>
                    <ElAlert v-if="isLoginFailed" :title="message" type="error" show-icon :closable="false" class="mt-[1rem!important]"></ElAlert>
                </Transition>
                <button v-ripple class="btn-primary mt-8" @click.prevent="submitForm(ruleFormRef)">Đăng nhập</button>
            </ElForm>
        </Transition>
    </section>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue';
import type { LoginCredentials } from '../../types/Type';
import type { FormInstance } from 'element-plus';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useUserStore } from '@/stores/user/User';

const isShow = ref<boolean>(false);
const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive<LoginCredentials>({
    email: '',
    password: '',
    remember_me: true,
});

const { login, setRememberStatus } = useUserStore();
const { isLoginFailed, message } = storeToRefs(useUserStore());
const router = useRouter();

/**
 * Validate the user email.
 * 
 * @param rule
 * @param value 
 * @param callback 
 */
const validateEmail = (rule: any, value: any, callback: any) => {
    const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const isValidEmail = emailRegex.test(value);

    if (value === '') {
        callback(new Error('Vui lòng điền email.'));
    } else if (!isValidEmail) {
        callback(new Error('Địa chỉ email không hợp lệ.'));
    } else {
        callback();
    }
}

/**
 * Validate the user password.
 * 
 * @param rule 
 * @param value 
 * @param callback 
 */
const validatePassword = (rule: any, value: any, callback: any) => {
    const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*.,])[A-Za-z0-9!@#$%^&*.,]{8,}$/;
    const isValidPassword = passwordRegex.test(value);

    if (value === '') {
        callback(new Error('Vui lòng điền mật khẩu.'));
    } else if (value.length < 6) {
        callback(new Error('Mật khẩu quá yếu.'));
    } else if (!isValidPassword) {
        callback(new Error('Mật khẩu phải từ 6 - 10 ký tự bao gồm chữ hoa, chữ thường, số và các ký tự đặc biệt.'));
    } else {
        callback();
    }
}

const rules = reactive({
    email: [{ validator: validateEmail, trigger: 'blur' }],
    password: [{ validator: validatePassword, trigger: 'blur' }],
});

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.validate(async valid => {
        if (!valid) return false;

        try {
            await login(ruleForm);
            setRememberStatus(ruleForm.remember_me);
            router.push({ path: '/' });
        } catch (err) {
            console.log(err);
        }
    });
}

onMounted(() => {
    isShow.value = true;
});
</script>

<style lang="scss" scoped>
.login-form {
    &__header {
        >h2 {
            &::before {
                content: '';
                position: absolute;
                width: 100%;
                height: 0.1rem;
                @apply bg-gray-500;
                bottom: 25%;
                left: 0;
            }
        }
    }
}
.login {
    &::before {
        content: '';
        position: absolute;
        width: 60rem;
        height: 60rem;
        @apply bg-purple rounded-full;
        top: -30%;
        left: -25%;
        opacity: 0.4;
        filter: blur(5rem);
    }

    &::after {
        content: '';
        position: absolute;
        width: 60rem;
        height: 60rem;
        @apply bg-blue rounded-full;
        bottom: -35%;
        right: -15%;
        opacity: 0.4;
        filter: blur(5rem);
    }
}
</style>