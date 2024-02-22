<template>
    <div>
        <h1 class="text-[2.6rem] font-medium uppercase">Sửa thông tin sản phẩm</h1>
        <ElForm ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="260px" class="mt-[3rem]"
            :size="formSize" status-icon>
            <ElFormItem label="Tên sản phẩm" prop="name" :error="errorMessages?.name?.[0]">
                <ElInput v-model="ruleForm.name" placeholder="Nhập tên sản phẩm" @input="generateSlug" />
            </ElFormItem>
            <ElFormItem label="Nhà sản xuất" prop="manufacture_id" :error="errorMessages?.manufacture_id?.[0]">
                <ElSelectV2 v-model="ruleForm.manufacture_id" placeholder="Chọn nhà sản xuất"
                    :options="manufactureOptions" filterable remote :remote-method="searchManufacture"
                    :loading="loading" />
            </ElFormItem>
            <ElFormItem label="Danh mục sản phẩm" prop="categories" :error="errorMessages?.categories?.[0]">
                <ElSelectV2 v-model="ruleForm.categories" :options="categoryOptions"
                    placeholder="Chọn danh mục sản phẩm" multiple clearable class="w-[25rem]" />
            </ElFormItem>
            <ElFormItem label="Màu sản phẩm" prop="colors" :error="errorMessages?.colors?.[0]">
                <ElSelectV2 v-model="ruleForm.colors" :options="colorOptions" placeholder="Chọn màu sản phẩm" multiple
                    clearable class="w-[25rem]" />
            </ElFormItem>
            <ElFormItem label="Size sản phẩm" prop="sizes" :error="errorMessages?.sizes?.[0]">
                <ElSelectV2 v-model="ruleForm.sizes" :options="sizeOptions" placeholder="Chọn size sản phẩm" multiple
                    clearable class="w-[25rem]" />
            </ElFormItem>
            <ElFormItem label="Loại sản phẩm" prop="types" :error="errorMessages?.types?.[0]">
                <ElSelectV2 v-model="ruleForm.types" :options="typeOptions" placeholder="Chọn loại sản phẩm" multiple
                    collapse-tags collapse-tags-tooltip class="w-[25rem]" />
            </ElFormItem>
            <ElFormItem label="Số lượng sản phẩm trong kho" prop="stock" :error="errorMessages?.stock?.[0]">
                <ElInput v-model="ruleForm.stock" type="number" />
            </ElFormItem>
            <ElFormItem label="Giá bán sản phẩm" prop="price" :error="errorMessages?.price?.[0]">
                <ElInput v-model="ruleForm.price" :formatter="currencyFormat" :parser="currencyParser" />
            </ElFormItem>
            <ElFormItem label="Upload ảnh" prop="images">
                <ElUpload v-model:file-list="ruleForm.images" list-type="picture-card" :limit="3"
                    :on-preview="handleImagePreview" :on-remove="handleImageRemove"
                    :before-remove="handleImageBeforeRemove" :before-upload="handleImageBeforeUpload"
                    :http-request="handleImageUpload" :on-exceed="handleImageExceed"
                    accept="image/jpg, image/jpeg, image/png" :on-success="handleImageUploadSuccess"
                    v-loading="imageUploading">
                    <ElIcon>
                        <Plus />
                    </ElIcon>
                </ElUpload>
                <ElDialog v-model="dialogVisible">
                    <img w-full :src="dialogImageUrl" alt="Preview Image">
                </ElDialog>
            </ElFormItem>
            <ElFormItem label="Mô tả sản phẩm" prop="description" :error="errorMessages?.description?.[0]">
                <ElInput v-model="ruleForm.description" maxlength="150" placeholder="Nhập mô tả sản phẩm"
                    show-word-limit type="textarea" />
            </ElFormItem>
            <ElFormItem label="Slug" prop="slug" :error="errorMessages?.slug?.[0]">
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
import { reactive, ref, onBeforeMount, computed } from 'vue';
import { Plus } from '@element-plus/icons-vue';
import { ElDialog, ElLoading, ElNotification, ElSelectV2, type FormInstance, type FormRules, type UploadProps, ElUpload, ElMessage } from 'element-plus';
import { getAllManufactures } from '@/api/manufactures/Manufactures';
import { getAllCategories } from '@/api/categories/Categories';
import { getAllColors } from '@/api/colors/Colors';
import { getAllSizes } from '@/api/sizes/Sizes';
import { getAllTypes } from '@/api/types/Types';
import { getProductDetails, deleteProductImage, addProductImage, updateProduct } from '@/api/products/Products';
import type { CategoryData, ColorData, ManufactureData, SizeData, TypeData, ListItem, UpdateProductData, ProductData, ProductValidationError } from '@/types/Type';
import { useAdminStore } from '@/stores/admin/Admin';
import { SlugGenerator } from '@/helpers/SlugGenerator';
import { useDebounceFn } from '@vueuse/shared';
import { useRouter } from 'vue-router';
import { currencyFormat, currencyParser } from '@/helpers/NumberFormat';

interface Props {
    slug: string,
}

const props = defineProps<Props>();

const { accessToken } = useAdminStore();
const router = useRouter();
const productId = ref<number | string>('');

// Create Form Insatance
const formSize = ref<string>('default');
const ruleFormRef = ref<FormInstance>();

// Form Validation Rule
const ruleForm = reactive<UpdateProductData>({
    name: '',
    manufacture_id: '',
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
const errorMessages = ref<ProductValidationError>();

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

// For preview image
const dialogImageUrl = ref<string>('');
const dialogVisible = ref<boolean>(false);

const imageUploading = ref<boolean>(false);

const setProductData = (data: ProductData) => {
    ruleForm.name = data.name;
    ruleForm.manufacture_id = data.manufacture.name;
    ruleForm.categories = data.categories.map(value => value.id);
    ruleForm.colors = data.colors.map(value => value.id);
    ruleForm.types = data.types.map(value => value.id);
    ruleForm.sizes = data.sizes.map(value => value.id);
    ruleForm.images = data.images.map(value => ({
        name: value.id,
        url: value.url,
    }));
    ruleForm.stock = data.stock;
    ruleForm.price = data.price;
    ruleForm.description = data.description;
    ruleForm.slug = data.slug;
}

// Auto generate slug from product name
const generateSlug = useDebounceFn(() => {
    const slug = new SlugGenerator(ruleForm.name);
    ruleForm.slug = slug.getSlug();
}, 500, { maxWait: 2000 });

const handleImageBeforeRemove: UploadProps['beforeRemove'] = async (uploadFile, uploadFiles): Promise<boolean> => {
    const formData = new FormData();
    formData.append('images[]', uploadFile.name);

    try {
        const res = await deleteProductImage(productId.value, accessToken!, formData);
        ElNotification.success({
            title: 'Success',
            message: res.message,
            showClose: false,
            duration: 2000,
        });
        return true;
    } catch (err: any) {
        return false;
    }

}

const handleImageBeforeUpload: UploadProps['beforeUpload'] = (rawFile) => {
    const allowedType = ['image/jpg', 'image/jpeg', 'image/png'];

    if (!allowedType.includes(rawFile.type)) {
        ElMessage.error('Ảnh phải thuộc định dạng JPG,JPEG, hoặc PNG.');
        return false;
    } else if (rawFile.size / 1024 / 1024 > 5) {
        ElMessage.error('Kích thước của ảnh phải nhỏ hơn 5MB.');
        return false;
    }

    return true;
}

const handleImageRemove: UploadProps['onRemove'] = async (uploadFile, uploadFiles) => {
    console.log('Image Removed');
}

const handleImagePreview: UploadProps['onPreview'] = (uploadFile) => {
    dialogImageUrl.value = uploadFile.url!;
    dialogVisible.value = true;
}

const handleImageUpload: UploadProps['httpRequest'] = async (options) => {
    const formData = new FormData();
    formData.append('images[]', options.file);

    try {
        imageUploading.value = true;
        const res = await addProductImage(productId.value, accessToken!, formData);
        console.log(res);
        ElNotification.success({
            title: 'Success',
            message: res.message,
            showClose: false,
            duration: 2000,
        });
        imageUploading.value = false;
    } catch (err: any) {
        ElNotification.error({
            title: 'Error',
            message: err.message,
            showClose: false,
            duration: 2000,
        })
    }
}

const handleImageExceed: UploadProps['onExceed'] = async (files) => {
    ElNotification.error({
        title: 'Error',
        message: 'Số ảnh tối đa là 3.',
        showClose: false,
        duration: 2000,
    });
}

const handleImageUploadSuccess: UploadProps['onSuccess'] = (response, uploadFile, uploadFiles) => {

}

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.resetFields();
}

const createFormData = (): FormData => {
    const formData = new FormData();
    const productPrice = currencyParser(ruleForm.price.toString());

    formData.append('_method', 'PUT');
    formData.append('name', ruleForm.name);
    formData.append('manufacture_id', ruleForm.manufacture_id.toString());
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
    formData.append('stock', ruleForm.stock.toString());
    formData.append('price', productPrice);
    formData.append('description', ruleForm.description);
    formData.append('slug', ruleForm.slug);

    return formData;
}

const submitForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return;
    formEl.validate(async (valid) => {
        if (!valid) return false;
        try {
            const res = await updateProduct(productId.value, accessToken!, createFormData());
            setProductData(res.data);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            router.push({
                path: `/admin/dashboard/product-detail/${ruleForm.slug}`
            });
        } catch (err: any) {
            console.log(err);
            errorMessages.value = err.errors;
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

onBeforeMount(async () => {
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
    const res = await getProductDetails(props.slug);
    productId.value = res.id;
    setProductData(res);
    loading.close();
});
</script>

<style scoped>

</style>