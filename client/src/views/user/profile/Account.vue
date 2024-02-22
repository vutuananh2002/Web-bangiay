<template>
    <div class="">
        <RouterView v-slot="{ Component }">
            <KeepAlive>
                <component :is="Component" :key="$route.path" />
            </KeepAlive>
        </RouterView>
    </div>
</template>

<script setup lang="ts">

</script>

<style lang="scss" scoped>

</style>