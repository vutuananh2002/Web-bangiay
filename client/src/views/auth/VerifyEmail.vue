<template>
    <Email>
        <template #message>
            <span v-text="message" />
        </template>
    </Email>
</template>

<script setup lang="ts">
import Email from '@/components/auth/Email.vue';
import router from '@/router';
import { ElNotification } from 'element-plus';
import { onBeforeMount, ref } from 'vue';
import { useRoute } from 'vue-router';
import { verifyEmail } from '@/api/auth/Email';
import { StatusCode } from '@/types/Enum';

const route = useRoute();
const message = ref<string>('')

onBeforeMount(async () => {
    try {
        await verifyEmail(route.params.id, route.params.hash, route.query);
        message.value = 'Email đã được xác nhận, bạn sẽ được chuyển hướng về trang đăng nhập.';

        ElNotification.success({
            title: 'Success',
            message: message.value,
            showClose: false,
            duration: 2000,
        });
        router.push({
            path: '/auth/login',
        });
    } catch (err: any) {
        if (err.status === StatusCode.Forbidden) {
            message.value = 'Link không hợp lệ hoặc đã hết hạn!.';
        }
    }
});
</script>

<style scoped>

</style>