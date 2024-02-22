<template>
    <ElSkeleton :loading="loading" animated :count="1">
        <template #template>
            <ElSkeletonItem variant="rect" style="width: 100%; height: 15rem; margin: 0.5rem;" />
        </template>
        <template #default>
            <li
                class="p-6 h-[15rem] max-h-[15rem] flex space-x-[2rem] bg-light rounded-lg drop-shadow-lg overflow-hidden before:w-[15rem] before:h-[15rem] before:absolute before:bg-purple before:rounded-full before:top-[-40%] before:left-[-5%] before:opacity-40 before:blur-md after:absolute after:w-[15rem] after:h-[15rem] after:bg-blue after:rounded-full after:bottom-[-50%] after:right-[-5%] after:opacity-40 after:blur-md">
                <div class="flex flex-col justify-center items-center w-[calc(15%-2rem)]">
                    <div class="w-[7rem] aspect-square rounded-full overflow-hidden border-purple border-[0.2rem]">
                        <ElImage :src="comment.customer.avatar" lazy />
                    </div>
                    <div class="">
                        <span v-for="star in comment.star" class="material-symbols-outlined text-purple"
                            style="font-variation-settings: 'FILL' 1;">
                            star
                        </span>
                        <span v-for="star in (5 - comment.star)" class="material-symbols-outlined text-blue">
                            star
                        </span>
                    </div>
                </div>
                <p class="truncate-five-line block w-[calc(85%-2rem)]" v-text="comment.comment" />
            </li>
        </template>
    </ElSkeleton>
</template>

<script setup lang="ts">
import type { ReviewData } from '@/types/Type';

interface Props {
    loading: boolean,
    comment: ReviewData,
}

const props = defineProps<Props>();
</script>

<style scoped>

</style>