<template>
    <RouterView></RouterView>
</template>

<script setup lang="ts">
import { onBeforeMount } from 'vue';
import { useAdminStore } from '../../stores/admin/Admin';

const { isRemember, setAccessToken } = useAdminStore();

onBeforeMount(() => {
    window.addEventListener('beforeunload', () => {
        if (isRemember) return;
        setAccessToken(null);
    });
});
</script>

<style scoped>

</style>