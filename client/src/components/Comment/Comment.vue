<template>
    <div class="flex flex-col space-y-[2rem]">
        <h3 class="text-[2.2rem] font-medium text-transparent bg-text-gradient bg-clip-text drop-shadow-lg">Đánh giá
            sản phẩm</h3>
        <div class="flex flex-col space-y-[2rem]">
            <ul class="flex flex-col space-y-[2rem]">
                <CommentItem v-for="comment in pagedComment" :key="comment.id" :loading="loading" :comment="comment" />
            </ul>
            <ElPagination background layout="prev, pager, next" :total="comments.length ?? 10" @current-change="setPage"
                hide-on-single-page />
        </div>
        <div class="flex flex-col space-y-[1rem]">
            <h3 class="text-[2.2rem] font-medium text-transparent bg-text-gradient bg-clip-text drop-shadow-lg">Viết
                bình luận</h3>
            <ElForm :model="ruleForm" ref="ruleFormRef" :rules="rules" status-icon>
                <ElFormItem prop="rating">
                    <ElRate v-model="ruleForm.star" :texts="ratingTexts" show-text />
                </ElFormItem>
                <ElFormItem prop="comment">
                    <ElInput v-model="ruleForm.comment" type="textarea" :rows="5" maxlength="300" show-word-limit placeholder="Nhập đánh giá của bạn" />
                </ElFormItem>
                <ElFormItem>
                    <button class="btn-primary" @click.prevent="submitForm(ruleFormRef)">Gửi đánh giá</button>
                </ElFormItem>
            </ElForm>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { ReviewData } from '@/types/Type';
import { ElMessage, type FormInstance, type FormRules } from 'element-plus';
import { ref, computed, reactive } from 'vue';
import CommentItem from './CommentItem.vue';
import { useUserStore } from '@/stores/user/User';
import { createComment } from '@/api/reviews/Reviews';
import { useRoute } from 'vue-router';

interface Props {
    loading: boolean,
    comments: ReviewData[],
    product_id: number,
}
const props = defineProps<Props>();

const route = useRoute();
const { accessToken, profile } = useUserStore();

// Pagination
const PER_PAGE = 10;
const currentPage = ref<number>(1);
const pagedComment = computed(() => {
    return props.comments.slice(PER_PAGE * currentPage.value - PER_PAGE, PER_PAGE * currentPage.value);
});
const setPage = (page: number) => {
    currentPage.value = page;
}

const ratingTexts = ['Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Tuyệt vời'];
// Validation form
const ruleFormRef = ref<FormInstance>();
const ruleForm = reactive({
    star: 1,
    comment: '',
});
const rules = reactive<FormRules>({
    star: [
        { type: 'number', required: true, message: 'Vui lòng đánh giá sản phẩm.' }
    ],
    comment: [
        { required: true, message: 'Vui lòng để lại bình luận.' },
    ],
});

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;

    formEl.validate(async (valid) => {
        if (!valid) return false;

        try {
            const res = await createComment(accessToken!, {
                product_id: props.product_id,
                customer_id: profile.customer?.id,
                ...ruleForm,
            });
            props.comments.push(res.data);
            ElMessage.success(res.message);
        } catch (err: any) {
            console.log(err);
        }
    });
}
</script>

<style scoped>

</style>