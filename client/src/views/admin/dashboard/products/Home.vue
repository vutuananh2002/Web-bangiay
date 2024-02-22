<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <RouterView v-slot="{ Component }">
            <KeepAlive>
                <component :is="Component" :key="$route.path" />
            </KeepAlive>
        </RouterView>
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped>

</style>