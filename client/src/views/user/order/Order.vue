<template>
    <div class="">
        <RouterView v-slot="{ Component }">
            <component :is="Component" :key="$route.path" />
        </RouterView>
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped>

</style>