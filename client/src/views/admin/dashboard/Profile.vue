<template>
    <div class="mt-[2rem!important] min-h-[100vh] container">
        <h1 class="text-[2.6rem] uppercase">Thông tin tài khoản</h1>
        <div class="profile mt-[3rem] flex space-x-[6rem] bg-light p-[4rem] relative overflow-hidden">
            <div class="">
                <ElUpload :show-file-list="false" :auto-upload="false" accept="image/png, image/jpg, image/jpeg"
                    :on-change="handleAvatarChange" :before-upload="beforeAvatarUpload" :on-remove="handleRemove">
                    <ElTooltip class="box-item" effect="dark" content="Đổi avatar" placement="bottom">
                        <div
                            class="text-[0.625em] w-[25em] aspect-square border-[0.5rem] border-purple shadow-xl shadow-purple rounded-full overflow-hidden border-opacity-80 hover:border-opacity-100 transition-all">
                            <img :src="user.avatar" :alt="user.username" class="w-full h-full object-cover">
                        </div>
                    </ElTooltip>
                </ElUpload>
                <Transition name="fade">
                    <div v-if="isEditAvatar" class="w-full absolute left-[50%] translate-x-[-50%] mt-[4rem] flex justify-center items-center space-x-[1rem]">
                        <button class="btn-primary"
                            @click="handleAvatarUpload">
                            <span>Lưu avatar</span>
                        </button>
                        <button @click="cancelEditAvatar" class="btn-cancel">
                            <span>Hủy</span>
                        </button>
                    </div>
                </Transition>
            </div>
            <div class="flex flex-col space-y-[2rem] max-w-[75rem] p-2 relative z-10">
                <div class="tracking-wide leading-[2.4rem]">
                    <div class="relative">
                        <label for="name"
                            class="max-w-full text-[2.4rem] font-medium absolute z-10 top-[50%] h-[3rem] translate-y-[-50%] transition-all bg-purple-gradient bg-clip-text text-transparent truncate"
                            :class="{ 'opacity-0 invisible': isEdit }" v-text="user.name" />
                        <elInput placeholder="Nhập tên của bạn" name="name" class="h-[4rem] transition-all"
                            :class="{ 'opacity-100 visible': isEdit, 'opacity-0 invisible': !isEdit }"
                            v-model="user.name" />
                    </div>
                    <h4 class="font-medium text-gray bg-purple-gradient bg-clip-text text-transparent max-w-full truncate"
                        v-text="user.role" />
                    <div class="relative">
                        <label for="username"
                            class="text-[1.4rem] font-light absolute z-10 top-[50%] translate-y-[-50%] transition-all bg-purple-gradient bg-clip-text text-transparent max-w-full truncate"
                            v-text="user.username" :class="{ 'opacity-0 invisible': isEdit }" />
                        <elInput placeholder="Nhập username" name="username" :parser="usernameParser"
                            v-model="user.username" :formatter="usernameFormatter"
                            :class="{ 'opacity-100 visible': isEdit, 'opacity-0 invisible': !isEdit }" />
                    </div>
                </div>
                <div class="flex space-x-[3rem]">
                    <div class="relative">
                        <label for="email"
                            class="absolute top-[50%] translate-y-[-50%] z-10 transition-all max-w-full truncate"
                            :class="{ 'opacity-0 invisible': isEdit }">
                            <span class="bg-purple-gradient bg-clip-text text-transparent">Email: </span>
                            <span v-text="user.email"></span>
                        </label>
                        <elInput v-model="user.email" name="email" placeholder="Please input"
                            class="h-[4rem] transition-all"
                            :class="{ 'opacity-100 visible': isEdit, 'opacity-0 invisible': !isEdit }">
                            <template #prepend>Email:</template>
                        </elInput>
                    </div>
                    <div class="relative">
                        <label for="phone_number"
                            class="absolute top-[50%] translate-y-[-50%] z-10 transition-all max-w-full truncate"
                            :class="{ 'opacity-0 invisible': isEdit }">
                            <span class="bg-purple-gradient bg-clip-text text-transparent">Số điện thoại: </span>
                            <span v-text="user.phone_number"></span>
                        </label>
                        <elInput v-model="user.phone_number" name="phone_number" placeholder="Please input"
                            class="h-[4rem] transition-all"
                            :class="{ 'opacity-100 visible': isEdit, 'opacity-0 invisible': !isEdit }">
                            <template #prepend>Số điện thoại:</template>
                        </elInput>
                    </div>
                </div>
                <div class="relative">
                    <p v-text="user.description" class="absolute top-[50%] translate-y-[-50%] z-10 transition-all"
                        :class="{ 'opacity-0 invisible': isEdit }"></p>
                    <elInput v-model="user.description" :autosize="{ minRows: 4, maxRows: 8 }" type="textarea"
                        placeholder="Please input" name="description" class="transition-all"
                        :class="{ 'opacity-100 visible': isEdit, 'opacity-0 invisible': !isEdit }" />
                </div>
                <div class="flex space-x-[4rem] h-[4rem]">
                    <button v-ripple class="btn-primary flex justify-center items-center" @click="enableEdit"
                        :disabled="isEdit" :class="{ 'opacity-60': isEdit }">
                        <span>Sửa thông tin</span>
                    </button>
                    <Transition name="bounce">
                        <button v-if="isEdit" v-ripple
                            class="bg-green-400 p-4 max-w-[15rem] rounded-lg text-white transition-all flex justify-center items-center"
                            @click="handleProfileChange">Save</button>
                    </Transition>
                    <Transition name="bounce">
                        <button v-if="isEdit" class="btn-cancel flex justify-center items-center" @click="cancelEdit">
                            <span>Huỷ</span>
                        </button>
                    </Transition>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { UserData } from '../../../types/Type';
import { ref, reactive, onBeforeMount } from 'vue';
import { useAdminStore } from '../../../stores/admin/Admin';
import { ElLoading, ElMessage, ElNotification, type UploadFile, type UploadFiles, type UploadProps, type UploadRawFile } from 'element-plus';
import { updateUser } from '../../../api/user/UpdateUser';
import { updateProfile } from '../../../api/admin/UpdateProfile';
import { updateUserAvatar } from '../../../api/user/UpdateUserAvatar';

interface AdminProfile {
    name: string | undefined,
    username: string | undefined,
    email: string | undefined,
    phone_number: string | undefined,
    role: string | undefined,
    description: string | undefined,
    avatar: string | undefined,
}

const { setAdmin, accessToken, getAdmin, profile } = useAdminStore();
const user = reactive<AdminProfile>({
    name: undefined,
    username: undefined,
    email: undefined,
    phone_number: undefined,
    role: undefined,
    description: undefined,
    avatar: undefined,
});
const isEdit = ref<boolean>(false);
const isEditAvatar = ref<boolean>(false);
const avatarFile = ref<any>(null);

const enableEdit = () => {
    isEdit.value = true;
}

const cancelEdit = () => {
    isEdit.value = false;
    setProfile(profile);
};

const cancelEditAvatar = () => {
    const { avatar } = profile;
    isEditAvatar.value = false;
    user.avatar = avatar.url;
}

const handleProfileChange = async () => {
    try {
        const { id, admin } = profile;
        const adminData = {
            'name': user.name,
            'phone_number': user.phone_number,
            'description': user.description,
        };
        const userData = {
            'username': user.username?.replace(/^@\s?/, ''),
            'email': user.email,
        };
        const updateAdminRes = await updateProfile(admin?.id!, accessToken!, adminData);
        const updateUserRes = await updateUser(id, accessToken!, userData);
        setAdmin(updateUserRes);
        setProfile(updateUserRes);
        ElNotification.success({
            title: 'Success',
            message: 'Cập nhật thông tin thành công.',
        });
        isEdit.value = false;
    } catch (err: any) {
        console.log(err);
    }
}

const setProfile = (data: UserData) => {
    user.name = data.admin?.name;
    user.username = `@${data.username}`;
    user.email = data.email;
    user.phone_number = data.admin?.phone_number;
    user.role = data.roles.at(0)?.name;
    user.description = data.admin?.description;
    user.avatar = data.avatar.url;
}

const usernameParser = (value: any) => (value.replace(/^@\s?/, ''));

const usernameFormatter = (value: any) => (`@ ${value.replace(/(\<|\>|\/)/, '')}`);

const handleAvatarChange: UploadProps['onChange'] = (uploadFile: UploadFile) => {
    const allowedAvatarType = ['image/jpg', 'image/jpeg', 'image/png'];

    if (!allowedAvatarType.includes(uploadFile.raw?.type!)) {
        ElMessage.error('Avatar phải thuộc định dạng JPG hoặc PNG!');
        return false;
    } else if (uploadFile.raw?.size! / 1024 / 1024 > 2) {
        ElMessage.error('Dung lượng avatar phải nhỏ hơn < 2MB');
        return false;
    }

    avatarFile.value = uploadFile.raw!;
    user.avatar = URL.createObjectURL(uploadFile.raw!);
    isEditAvatar.value = true;

    return true;
}

const beforeAvatarUpload: UploadProps['beforeUpload'] = (rawFile: UploadRawFile) => {
    const allowedAvatarType = ['image/jpg', 'image/png'];

    if (!allowedAvatarType.includes(rawFile.type)) {
        ElMessage.error('Avatar phải thuộc định dạng JPG hoặc PNG!');
        return false;
    } else if (rawFile.size / 1024 / 1024 > 2) {
        ElMessage.error('Dung lượng avatar phải nhỏ hơn < 2MB');
        return false;
    }

    return true;
}

const handleAvatarUpload = async () => {
    const { id, avatar } = profile;
    const formData = new FormData();

    if (avatarFile.value) {
        formData.append('avatar', avatarFile.value);
    }

    try {
        const res = await updateUserAvatar(id, accessToken!, formData);
        avatar.url = res.data.url;
        avatar.expires_at = res.data.expires_at;
        avatar.id = res.data.id;
        user.avatar = res.data.url;
        
        ElNotification.success({
            title: res.message,
            showClose: false,
        });
        isEditAvatar.value = false;
    } catch (err: any) {
        ElNotification.error({
            title: err.message,
            showClose: false,
        });
    }
}

const handleRemove: UploadProps['onRemove'] = (uploadFile, uploadFiles) => {
    console.log(uploadFile, uploadFiles)
}

onBeforeMount(async () => {
    const loading = ElLoading.service({
        lock: true,
    });
    // Fetch admin data
    await getAdmin();
    setProfile(profile);
    loading.close();
});
</script>

<style lang="scss" scoped>
.profile {
    &::before {
        content: '';
        position: absolute;
        top: 30%;
        left: -20%;
        width: 50rem;
        height: 50rem;
        border-radius: 50%;
        @apply bg-purple;
        opacity: 0.4;
        filter: blur(10rem);
    }

    &::after {
        content: '';
        position: absolute;
        top: -70%;
        right: -20%;
        width: 50rem;
        height: 50rem;
        border-radius: 50%;
        @apply bg-blue;
        opacity: 0.4;
        filter: blur(10rem);
    }
}
</style>