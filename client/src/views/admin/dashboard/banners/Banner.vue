<template>
    <h1 class="text-[2.6rem] font-medium uppercase">Danh sách banner</h1>
    <div class="">
        <ElTable :data="tableData" max-height="600" v-loading="loading" class="w-full">
            <ElTableColumn prop="updated_at" label="Cập nhật" width="150" sortable />
            <ElTableColumn prop="title" label="Tiêu đề" width="200" sortable />
            <ElTableColumn prop="description" label="Mô tả" width="200" />
            <ElTableColumn label="Ảnh" width="200">
                <template #default="scope">
                    <div>
                        <ElImage preview-teleported class="w-[15rem] aspect-video flex justify-center items-center"
                            :src="scope.row.url" />
                    </div>
                </template>
            </ElTableColumn>
            <ElTableColumn fixed="right" width="200">
                <template #default="scope">
                    <ElButton size="small">
                        <RouterLink :to="`/admin/dashboard/banner/edit/${scope.row.id}`">
                            Sửa
                        </RouterLink>
                    </ElButton>
                    <ElButton size="small" type="danger">Xóa
                    </ElButton>
                </template>
            </ElTableColumn>
        </ElTable>
        <button class="btn-primary w-full">
            <RouterLink to="/admin/dashboard/banner/create" class="w-full inline-block">Tạo Banner mới</RouterLink>
        </button>
    </div>
</template>

<script setup lang="ts">
import { getAllBanners } from '@/api/banner/Banners';
import { ref, computed, reactive, onBeforeMount } from 'vue';
import type { BannerData } from '@/types/Type';

const loading = ref<boolean>(false);
const bannerData = reactive<BannerData[]>([]);
const tableData = computed(() => bannerData);

onBeforeMount(async () => {
    loading.value = true;
    const res = await getAllBanners();
    bannerData.push(...res.data);
    loading.value = false;
});
</script>

<style scoped>

</style>