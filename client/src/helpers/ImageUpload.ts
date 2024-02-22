import type { UploadProps, UploadFile } from "element-plus";
import { ref } from "vue";

// Image Preview
const dialogImageUrl = ref<string>('');
const dialogVisible = ref<boolean>(false);

const handleImagePreview = (file: UploadFile) => {
    dialogImageUrl.value = file.url!;
    dialogVisible.value = true;
}

// Image Remove
const handleImageRemove: UploadProps['onRemove'] = async (file) => {
    console.log('Image Removed');
}

export { handleImagePreview, handleImageRemove, dialogImageUrl, dialogVisible }