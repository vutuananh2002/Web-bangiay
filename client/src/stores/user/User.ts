import { getCurrentUser } from "@/api/user/GetCurrentUser";
import type { AccessToken, LoginCredentials, UserData } from "@/types/Type";
import { defineStore } from "pinia";
import { ref, reactive } from "vue";
import { login as userLogin, logout as userLogout } from "@/api/user/auth/Auth";
import { ElNotification } from "element-plus";
import { StatusCode } from "@/types/Enum";
import { useCartStore } from "./Cart";

export const useUserStore = defineStore('user', () => {
    const cart = useCartStore();

    const profile = reactive<UserData>({
        id: 0,
        username: "",
        email: "",
        is_verified: "",
        status: "",
        avatar: {
            id: 0,
            url: '',
            expires_at: '',
        },
        roles: [],
        customer: {
            id: 0,
            full_name: '',
            address: '',
            phone_number: '',
            gender: '',
            created_at: '',
            updated_at: '',
        }
    });
    const userDefault = ref<UserData>();
    const accessToken = ref<AccessToken | null>();
    const isRemember = ref<boolean>(false);
    const message = ref<string>();
    const isLoginFailed = ref<boolean>(false);

    const getUser = async () => {
        try {
            const res = await getCurrentUser(accessToken.value!);
            setUserData(res);
        } catch (err: any) {
            if (err.status === StatusCode.Unauthorized) {
                ElNotification.error({
                    title: 'Error',
                    message: 'Phiên đăng nhập đã hết hạn.',
                    showClose: false,
                    duration: 2000,
                });
                setAccessToken(null);
            }
        }
    }

    const setUserData = (data: UserData) => {
        profile.id = data.id;
        profile.admin = data.admin;
        profile.username = data.username;
        profile.email = data.email;
        profile.is_verified = data.is_verified;
        profile.status = data.status;
        profile.avatar = data.avatar;
        profile.roles = data.roles;
        profile.customer = data.customer;
    }

    const setAccessToken = (token: AccessToken | null) => {
        accessToken.value = token;
    }

    const login = async (credentials: LoginCredentials) => {
        try {
            const res = await userLogin(credentials);
            setAccessToken(res.data.access_token);
            setUserData(res.data.user);
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
            isLoginFailed.value = false;
        } catch (err: any) {
            message.value = err.data.message;
            isLoginFailed.value = true;
            throw err;
        }
    }

    const logout = async () => {
        try {
            const res = await userLogout(accessToken.value!);
            accessToken.value = null;
            setUserData(userDefault.value!);
            cart.setCartIdAndKey(null, null);
            
            ElNotification.success({
                title: 'Success',
                message: res.message,
                showClose: false,
                duration: 2000,
            });
        } catch (err: any) {
            ElNotification.error({
                title: 'Error',
                message: err.message,
                showClose: false,
                duration: 2000,
            });
        }
    }

    const isAuthenticated = () => {
        return accessToken.value ? true : false;
    }

    const setRememberStatus = (status: boolean) => {
        isRemember.value = status;
    }

    return {
        isAuthenticated,
        setUserData,
        getUser,
        login,
        logout,
        setRememberStatus,
        setAccessToken,
        profile,
        accessToken,
        isRemember,
        message,
        isLoginFailed,
    }
}, {
    persist: {
        storage: localStorage,
        paths: ['accessToken', 'isRemember'],
    }
});