<template>
    <div class="min-h-[100vh] mt-[2rem]">
        <h1 class="text-[2.6rem] font-medium uppercase">Thêm sản phẩm mới</h1>
        <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="260px" class="mt-[3rem]"
            :size="formSize" status-icon>
            <ElFormItem label="Tên sản phẩm" prop="name">
                <ElInput v-model="ruleForm.name" placeholder="Nhập tên sản phẩm" @input="generateSlug" />
            </ElFormItem>
            <ElFormItem label="Nhà sản xuất" prop="manufacture_id">
                <ElSelectV2 v-model="ruleForm.manufacture_id" placeholder="Chọn nhà sản xuất"
                    :options="manufactureOptions" filterable remote :remote-method="searchManufacture"
                    :loading="loading" />
            </ElFormItem>
            <ElFormItem label="Danh mục sản phẩm" prop="categories">
                <ElSelectV2 v-model="ruleForm.categories" :options="categoryOptions"
                    placeholder="Chọn danh mục sản phẩm" multiple clearable class="w-[25rem]" />
            </ElFormItem>
            <ElFormItem label="Màu sản phẩm" prop="colors">
                <ElSelectV2 v-model="ruleForm.colors" :options="colorOptions" placeholder="Chọn màu sản phẩm" multiple
                    clearable class="w-[25rem]" />
            </ElFormItem>
            <ElFormItem label="Size sản phẩm" prop="sizes">
                <ElSelectV2 v-model="ruleForm.sizes" :options="sizeOptions" placeholder="Chọn size sản phẩm" multiple
                    clearable class="w-[25rem]" />
            </ElFormItem>
            <ElFormItem label="Loại sản phẩm" prop="types">
                <ElSelectV2 v-model="ruleForm.types" :options="typeOptions" placeholder="Chọn loại sản phẩm" multiple
                    collapse-tags collapse-tags-tooltip class="w-[25rem]" />
            </ElFormItem>
            <ElFormItem label="Số lượng sản phẩm trong kho" prop="stock">
                <ElInput v-model="ruleForm.stock" />
            </ElFormItem>
            <ElFormItem label="Giá bán sản phẩm" prop="price">
                <ElInput v-model="ruleForm.price" :formatter="currencyFormat" :parser="currencyParser" />
            </ElFormItem>
            <ElFormItem label="Upload ảnh" prop="images">
                <ElUpload v-model:file-list="ruleForm.images" list-type="picture-card" :limit="3" ref="upload"
                    :on-preview="handleImagePreview" :on-remove="handleImageRemove" :auto-upload="false"
                    :on-change="handleImageChange" :on-exceed="handleImageExceed"
                    accept="image/jpg, image/jpeg, image/png">
                    <ElIcon>
                        <Plus />
                    </ElIcon>
                </ElUpload>
                <ElDialog v-model="dialogVisible">
                    <img w-full :src="dialogImageUrl" alt="Preview Image">
                </ElDialog>
            </ElFormItem>
            <ElFormItem label="Mô tả sản phẩm" prop="description">
                <ElInput v-model="ruleForm.description" maxlength="150" placeholder="Nhập mô tả sản phẩm"
                    show-word-limit type="textarea" />
            </ElFormItem>
            <ElFormItem label="Slug" prop="slug">
                <ElInput v-model="ruleForm.slug" />
            </ElFormItem>
            <ElFormItem>
                <button v-ripple class="btn-primary m-4" @click.prevent="submitForm(ruleFormRef)">Submit</button>
                <button v-ripple class="btn-cancel m-4" @click.prevent="resetForm(ruleFormRef)">Reset</button>
            </ElFormItem>
        </ElForm>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue';
import { Plus } from '@element-plus/icons-vue';
import { ElDialog, ElLoading, ElMessage, ElNotification, ElSelectV2, genFileId, type FormInstance, type FormRules, type UploadFile, type UploadInstance, type UploadProps, type UploadRawFile, type UploadUserFile } from 'element-plus';
import { getAllManufactures } from '@/api/manufactures/Manufactures';
import { getAllCategories } from '@/api/categories/Categories';
import { getAllColors } from '@/api/colors/Colors';
import { getAllSizes } from '@/api/sizes/Sizes';
import { getAllTypes } from '@/api/types/Types';
import { createProduct } from '@/api/products/Products';
import type { CategoryData, ColorData, ManufactureData, SizeData, TypeData, ListItem } from '@/types/Type';
import { useAdminStore } from '@/stores/admin/Admin';
import { SlugGenerator } from '@/helpers/SlugGenerator';
import { useDebounceFn } from '@vueuse/shared';
import router from '@/router';
import { dialogImageUrl, dialogVisible, handleImagePreview, handleImageRemove } from '@/helpers/ImageUpload';
import { currencyFormat, currencyParser } from '@/helpers/NumberFormat';

const { accessToken } = useAdminStore();

// Create Form Insatance
const formSize = ref<string>('default');
const ruleFormRef = ref<FormInstance>();

// Form Validation Rule
const ruleForm = reactive({
    name: '',
    manufacture_id: null,
    categories: [],
    colors: [],
    types: [],
    sizes: [],
    images: [],
    stock: 0,
    price: 0,
    description: '',
    slug: '',
});
const rules = reactive<FormRules>({
    name: [
        { required: true, message: 'Vui lòng điền tên sản phẩm.', trigger: 'blur' },
        { min: 5, message: 'Tên sản phẩm phải dài tối thiểu 5 ký tự', trigger: 'blur' },
    ],
    manufacture_id: [
        { required: true, message: 'Vui lòng chọn nhà sản xuất.', trigger: 'change' },
    ],
    categories: [
        { type: 'array', required: true, message: 'Vui lòng chọn danh mục sản phẩm.', trigger: 'change' },
    ],
    colors: [
        { type: 'array', required: true, message: 'Vui lòng chọn màu sắc sản phẩm.', trigger: 'change' },
    ],
    types: [
        { type: 'array', required: true, message: 'Vui lòng chọn loại sản phẩm.', trigger: 'change' },
    ],
    sizes: [
        { type: 'array', required: true, message: 'Vui lòng chọn size sản phẩm.', trigger: 'change' },
    ],
    images: [
        { type: 'array', required: true, message: 'Vui lòng chọn ảnh sản phẩm.', trigger: 'change' },
    ],
    stock: [
        { required: true, message: 'Vui lòng điền số lượng sản phẩm trong kho.', trigger: 'blur' },
    ],
    price: [
        { required: true, message: 'Vui lòng điền giá bán sản phẩm.', trigger: 'blur' }
    ],
    description: [
        { type: 'string', required: true, message: 'Vui lòng điền mô tả sản phẩm.', trigger: 'blur' }
    ],
    slug: [
        { type: 'string', required: true, message: 'Vui lòng điền slug sản phẩm.', trigger: 'change' },
    ],
});

// Manufactures Data
const manufacturesData = ref<ManufactureData[]>([]);
const manufactureList = computed(() => (
    manufacturesData.value.map(value => ({
        value: value.id,
        label: value.name,
    }))
));
const manufactureOptions = ref<ListItem[]>([]);
const loading = ref<boolean>(true);

// Categories Data
const categoriesData = ref<CategoryData[]>([]);
const categoryOptions = computed(() => (
    categoriesData.value.map(value => ({
        value: value.id,
        label: value.name,
    }))
));

// Colors Data
const colorsData = ref<ColorData[]>([]);
const colorOptions = computed(() => (
    colorsData.value.map(value => ({
        value: value.id,
        label: value.color_code,
    }))
));

// Sizes Data
const sizesData = ref<SizeData[]>([]);
const sizeOptions = computed(() => (
    sizesData.value.map(value => ({
        value: value.id,
        label: value.name,
    }))
));

// Types Data
const typesData = ref<TypeData[]>([]);
const typeOptions = computed(() => (
    typesData.value.map(value => ({
        value: value.id,
        label: value.type,
    }))
));

const upload = ref<UploadInstance>();

const handleImageChange: UploadProps['onChange'] = (uploadFile, uploadFiles) => {
    const allowedType = ['image/jpg', 'image/jpeg', 'image/png'];

    if (!uploadFile) {
        ElMessage.error('Vui lòng chọn ảnh!');
        return;
    }

    const fileSize = uploadFile.raw!.size / 1024 / 1024;

    if (!allowedType.includes(uploadFile.raw!.type)) {
        upload.value!.clearFiles();
        ElMessage.error('Ảnh phải thuộc định dạng JPG, JPEG, hoặc PNG!');
        return;
    }

    if (fileSize > 5) {
        upload.value!.clearFiles();
        ElMessage.error('Kích thước ảnh phải nhỏ hơn 5MB!');
        return;
    }
}

const handleImageExceed: UploadProps['onExceed'] = (files) => {
    ElMessage.info('Số ảnh tối đa là 3, vui lòng xóa bớt ảnh trước khi thêm.')
}

// Auto generate slug from product name
const generateSlug = useDebounceFn(() => {
    const slug = new SlugGenerator(ruleForm.name);
    ruleForm.slug = slug.getSlug();
}, 500, { maxWait: 2000 });

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.resetFields()
}

const createFormData = (): FormData => {
    const formData = new FormData();
    formData.append('name', ruleForm.name);
    formData.append('manufacture_id', ruleForm.manufacture_id!);
    ruleForm.categories.forEach(value => {
        formData.append('categories[]', value);
    });
    ruleForm.colors.forEach(value => {
        formData.append('colors[]', value);
    });
    ruleForm.types.forEach(value => {
        formData.append('types[]', value);
    });
    ruleForm.sizes.forEach(value => {
        formData.append('sizes[]', value);
    });
    ruleForm.images.forEach((value: UploadFile) => {
        formData.append('images[]', value.raw!);
    })
    formData.append('stock', ruleForm.stock.toString());
    formData.append('price', currencyParser(ruleForm.price));
    formData.append('description', ruleForm.description);
    formData.append('slug', ruleForm.slug);

    return formData;
}

const submitForm = async (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    await formEl.validate(async (valid, fields) => {
        if (!valid) return false;
        try {
            const res = await createProduct(accessToken!, createFormData());
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            router.push({
                path: `/admin/dashboard/products/${ruleForm.slug}`
            });
            resetForm(formEl);
        } catch (err: any) {
            console.log(err);
        }
    });
}

const searchManufacture = (query: string) => {
    if (query !== '') {
        loading.value = true;
        setTimeout(() => {
            loading.value = false;
            manufactureOptions.value = manufactureList.value.filter(item => {
                return item.label.toLocaleLowerCase().includes(query.toLocaleLowerCase());
            });
        }, 200);
    } else {
        manufactureOptions.value = [];
    }
}

onMounted(async () => {
    const loading = ElLoading.service({
        lock: true,
        text: 'Đang lấy dữ liệu từ server',
    });
    const manufacturesRes = await getAllManufactures();
    manufacturesData.value.push(...manufacturesRes.data);
    const categoriesRes = await getAllCategories();
    categoriesData.value.push(...categoriesRes.data);
    const colorsRes = await getAllColors();
    colorsData.value.push(...colorsRes.data);
    const sizesRes = await getAllSizes();
    sizesData.value.push(...sizesRes.data);
    const typeRes = await getAllTypes();
    typesData.value.push(...typeRes.data);
    loading.close();
});
</script>

<style scoped>

</style>