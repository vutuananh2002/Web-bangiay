import type { AccessToken, LoginCredentials, UserData } from "../../types/Type";
import { defineStore } from "pinia";
import { ref, reactive } from "vue";
import { getCurrentUser } from "@/api/user/GetCurrentUser";
import { logout as adminLogout, login as adminLogin } from "@/api/admin/auth/Auth";
import { ElNotification } from "element-plus";
import { StatusCode } from "@/types/Enum";
import router from "@/router";

export const useAdminStore = defineStore('admin', () => {
    const profile = reactive<UserData>({
        id: 0,
        username: '',
        email: '',
        is_verified: '',
        status: '',
        avatar: {
            id: 0,
            url: '',
            expires_at: null,
        },
        roles: [
            {
                role: 0,
                name: '',
            }
        ],
    });
    const adminDefault = ref<UserData>();
    const accessToken = ref<AccessToken | null>(null);
    const isRemember = ref<boolean>(false);
    const isLoginFailed = ref<boolean>(false);
    const message = ref<string>('');

    const getAdmin = async () => {
        try {
            const res = await getCurrentUser(accessToken.value!);
            setAdmin(res);
        } catch (err: any) {
            if (err.status === StatusCode.Unauthorized) {
                ElNotification.error({
                    title: 'Error',
                    message: 'Phiên đăng nhập đã hết hạn.',
                    showClose: false,
                    duration: 2000,
                });
                setAccessToken(null);
                router.replace({
                    path: '/admin/login',
                });
            }
        }
    };

    const setAdmin = (credentials: UserData) => {
        profile.id = credentials.id;
        profile.admin = credentials.admin;
        profile.username = credentials.username;
        profile.email = credentials.email;
        profile.is_verified = credentials.is_verified;
        profile.status = credentials.status;
        profile.avatar = credentials.avatar;
        profile.roles = credentials.roles;
    }

    const setAccessToken = (token: AccessToken | null) => {
        accessToken.value = token;
    }

    const login = async (credentials: LoginCredentials) => {
        try {
            const res = await adminLogin(credentials);
            setAccessToken(res.data.access_token);
            setAdmin(res.data.user);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
            });
            isLoginFailed.value = false;
        } catch (err: any) {
            message.value = err.data.message;
            isLoginFailed.value = true;
        }
    }

    const logout = async () => {
        try {
            const res = await adminLogout(accessToken.value!);
            setAccessToken(null);
            setAdmin(adminDefault.value!);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
            });
        } catch (err: any) {
            ElNotification.error({
                title: 'Error',
                message: err.message,
                showClose: false,
            });
        }
    }

    const isAuthenticated = (): boolean => {
        return accessToken.value ? true : false;
    }

    const setRememberStatus = (status: boolean): void => {
        isRemember.value = status;
    }

    return {
        isAuthenticated,
        setAdmin,
        getAdmin,
        profile,
        setAccessToken,
        accessToken,
        login,
        logout,
        setRememberStatus,
        isRemember,
        message,
        isLoginFailed,
    };
}, {
    persist: {
        storage: localStorage,
        paths: ['accessToken', 'isRemember'],
    }
});