<template>
    <Email>
        <template #message>
            <span v-text="message" />
        </template>
    </Email>
</template>

<script setup lang="ts">
import { verifyChange } from '@/api/auth/Email';
import Email from '@/components/auth/Email.vue';
import router from '@/router';
import { ElNotification } from 'element-plus';
import { ref, onBeforeMount } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const message = ref<string>('');

onBeforeMount(async () => {
    try {
        const res = await verifyChange(route.params.id, route.params.hash, route.query);
        message.value = res.message;
        ElNotification.success({
            title: 'Success',
            message: res.message,
            showClose: false,
            duration: 5000,
        });
        router.push({
            path: '/user/account/profile',
        });
    } catch (err: any) {
        console.log(err);
    }
});
</script>

<style scoped>

</style>