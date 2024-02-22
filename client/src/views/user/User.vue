<template>
    <div class="common-layout mt-[2rem] w-full">
        <ElContainer>
            <ElAside width="300px">
                <div class="flex flex-col space-y-[4rem] bg-light min-h-[100vh] p-6">
                    <div class="flex space-x-[1.5rem]">
                        <div class="w-[5rem] aspect-square rounded-full overflow-hidden border-[0.2rem] border-purple">
                            <ElImage :src="profile.avatar.url" lazy class="w-full h-full object-cover" />
                        </div>
                        <div class="">
                            <h3 class="font-medium" v-text="profile.username" />
                            <span class="text-gray font-light text-xl" v-text="profile.email" />
                        </div>
                    </div>
                    <ul class="flex flex-col space-y-[2rem]">
                        <li class="flex flex-col space-y-[2rem]">
                            <div
                                class="h-[5rem] flex justify-center items-center space-x-[1.5rem] cursor-pointer p-4 bg-purple bg-opacity-60 text-white rounded-lg drop-shadow-md">
                                <ElIcon :size="20">
                                    <User />
                                </ElIcon>
                                <span>Thông tin tài khoản</span>
                            </div>
                            <ul class="pl-[4rem] flex flex-col space-y-[2rem]">
                                <li class="profile__item"
                                    :class="{ 'active': $route.path === '/user/account/profile' }">
                                    <RouterLink to="/user/account/profile"
                                        class="flex items-center space-x-[1.5rem] w-full h-full">
                                        <span class="material-symbols-outlined">
                                            badge
                                        </span>
                                        <span>Profile</span>
                                    </RouterLink>
                                </li>
                                <li class="profile__item"
                                    :class="{ 'active': $route.path === '/user/account/password' }">
                                    <RouterLink to="/user/account/password"
                                        class="flex items-center space-x-[1.5rem] w-full h-full">
                                        <ElIcon :size="20">
                                            <Lock />
                                        </ElIcon>
                                        <span>Đổi mật khẩu</span>
                                    </RouterLink>
                                </li>
                                <li class="profile__item" :class="{ 'active': $route.path === '/user/account/delete' }">
                                    <RouterLink to="/user/account/delete"
                                        class="flex items-center space-x-[1.5rem] w-full h-full">
                                        <span class="material-symbols-outlined">
                                            person_remove
                                        </span>
                                        <span>Xóa tài khoản</span>
                                    </RouterLink>
                                </li>
                            </ul>
                        </li>
                        <li class="flex flex-col space-y-[2rem]">
                            <div
                                class="h-[5rem] flex justify-center items-center space-x-[1.5rem] cursor-pointer p-4 bg-purple bg-opacity-60 text-white rounded-lg drop-shadow-md">
                                <ElIcon :size="20">
                                    <ShoppingCartFull />
                                </ElIcon>
                                <span>Đơn hàng của tôi</span>
                            </div>
                            <ul class="pl-[4rem] flex flex-col space-y-[2rem]">
                                <li class="order__item" :class="{ 'active': $route.path === '/user/order/information' }">
                                    <RouterLink to="/user/order/information" class="flex items-center space-x-[1.5rem] w-full h-full">
                                        <ElIcon :size="20">
                                            <ShoppingCartFull />
                                        </ElIcon>
                                        <span>Thông tin đơn hàng</span>
                                    </RouterLink>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </ElAside>
            <ElMain class="w-full">
                <RouterView v-slot="{ Component }">
                    <KeepAlive>
                        <component :is="Component" :key="$route.path" />
                    </KeepAlive>
                </RouterView>
            </ElMain>
        </ElContainer>
    </div>
</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/user/User';
import { User, Lock, ShoppingCartFull } from '@element-plus/icons-vue';

const { profile } = useUserStore();
</script>

<style lang="scss" scoped>
.profile,
.order {
    &__item {
        transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
        @apply cursor-pointer p-4 overflow-hidden rounded-lg;

        &::before {
            transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            transform-origin: right;
            content: '';
            @apply absolute w-full h-full top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] scale-x-0 bg-purple bg-opacity-60;
        }

        &:hover,
        &.active {
            @apply text-white;

            &::before {
                transform-origin: left;
                @apply scale-x-100;
            }
        }
    }
}
</style>