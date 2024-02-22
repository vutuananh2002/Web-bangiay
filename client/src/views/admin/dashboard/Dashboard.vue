<template>
    <section class="dashboard w-full" :class="{ 'nav-open': isToggle, 'nav-collapse': isCollapse }">
        <ElContainer>
            <ElAside
                class="fixed h-full top-0 right-0 left-auto z-[9999] bg-light text-left text-primary transition-all duration-500 translate-x-[23rem]">
                <div
                    class="logo py-[1.8rem] h-[7.5rem] flex justify-center items-center text-[1.8rem] uppercase font-bold">
                    <RouterLink to="/admin/dashboard"
                        class="logo__text logo-mini p-[1rem] w-[5rem] transition-all duration-500">
                        <span>DB</span>
                    </RouterLink>
                    <RouterLink to="/admin/dashboard" class="logo__text logo-normal transition-all duration-500">
                        <span>Dashboard</span>
                    </RouterLink>
                </div>
                <ElScrollbar max-height="60rem">
                    <ElMenu :collapse="isCollapse">
                        <ElSubMenu index="0">
                            <template #title>
                                <div class="user flex justify-center items-center space-x-[2rem]">
                                    <div class="user__avatar w-[3rem] h-[3rem] rounded-full bg-purple overflow-hidden">
                                        <img :src="profile.avatar.url" :alt="profile.username"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <h3 class="menu-title user__name block max-w-[10rem] truncate transition-all duration-500"
                                        v-text="profile.admin?.name" />
                                </div>
                            </template>
                            <ElMenuItem index="0-1">
                                <RouterLink to="/admin/dashboard/profile" href=""
                                    class="user-profile user-profile--view flex justify-center items-center space-x-[1rem]">
                                    <ElIcon :size="30">
                                        <User />
                                    </ElIcon>
                                    <span class="el-menu-item__text">Tài khoản của tôi</span>
                                </RouterLink>
                            </ElMenuItem>
                            <ElMenuItem index="0-2">
                                <a href="#"
                                    class="user-profile user-profile--settings flex justify-center items-center space-x-[1rem]">
                                    <ElIcon :size="30">
                                        <Setting />
                                    </ElIcon>
                                    <span class="el-menu-item__text">Cài đặt</span>
                                </a>
                            </ElMenuItem>
                            <ElMenuItem index="0-3">
                                <button class="flex justify-center items-center space-x-[1.4rem]" @click="handleLogout">
                                    <span class="material-symbols-outlined">
                                        logout
                                    </span>
                                    <span class="el-menu-item__text">Đăng xuất</span>
                                </button>
                            </ElMenuItem>
                        </ElSubMenu>
                        <div
                            class="mt-[2rem] before:absolute before:top-[-1rem] before:w-full before:h-[0.1rem] before:bg-purple">
                            <ElSubMenu index="1">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <Box />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý sản phẩm</h3>
                                    </div>
                                </template>
                                <ElSubMenu index="1-1">
                                    <template #title>
                                        <div class="flex justify-center items-center space-x-[2rem]">
                                            <ElIcon :size="20">
                                                <Shop />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Sản phẩm</span>
                                        </div>
                                    </template>
                                    <ElMenuItem index="1-1-1">
                                        <RouterLink to="/admin/dashboard/products"
                                            class="flex justify-center items-center space-x-[1rem]">
                                            <ElIcon :size="15">
                                                <Memo />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Danh sách sản phẩm</span>
                                        </RouterLink>
                                    </ElMenuItem>
                                    <ElMenuItem index="1-1-2">
                                        <RouterLink to="/admin/dashboard/products/create-product"
                                            class="flex justify-center items-center space-x-[1rem]">
                                            <ElIcon :size="15">
                                                <Plus />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Thêm sản phẩm</span>
                                        </RouterLink>
                                    </ElMenuItem>
                                </ElSubMenu>
                                <ElSubMenu index="1-2">
                                    <template #title>
                                        <div class="flex justify-center items-center space-x-[2rem]">
                                            <ElIcon :size="20">
                                                <Shop />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Loại sản phẩm</span>
                                        </div>
                                    </template>
                                    <ElMenuItem index="1-2-1">
                                        <RouterLink to="/admin/dashboard/product-types"
                                            class="flex justify-center items-center space-x-[1rem]">
                                            <ElIcon :size="15">
                                                <Memo />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Danh sách loại sản phẩm</span>
                                        </RouterLink>
                                    </ElMenuItem>
                                    <ElMenuItem index="1-2-2">
                                        <RouterLink to="/admin/dashboard/create-product-type"
                                            class="flex justify-center items-center space-x-[1rem]">
                                            <ElIcon :size="15">
                                                <Plus />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Thêm loại sản phẩm</span>
                                        </RouterLink>
                                    </ElMenuItem>
                                </ElSubMenu>
                                <ElSubMenu index="1-3">
                                    <template #title>
                                        <div class="flex justify-center items-center space-x-[2rem]">
                                            <ElIcon :size="20">
                                                <Shop />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Màu sản phẩm</span>
                                        </div>
                                    </template>
                                    <ElMenuItem index="1-3-1">
                                        <RouterLink to="/admin/dashboard/product-colors"
                                            class="flex justify-center items-center space-x-[1rem]">
                                            <ElIcon :size="15">
                                                <Memo />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Danh sách màu sản phẩm</span>
                                        </RouterLink>
                                    </ElMenuItem>
                                    <ElMenuItem index="1-3-2">
                                        <RouterLink to="/admin/dashboard/create-product-color"
                                            class="flex justify-center items-center space-x-[1rem]">
                                            <ElIcon :size="15">
                                                <Plus />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Thêm màu sản phẩm</span>
                                        </RouterLink>
                                    </ElMenuItem>
                                </ElSubMenu>
                                <ElSubMenu index="1-4">
                                    <template #title>
                                        <div class="flex justify-center items-center space-x-[2rem]">
                                            <ElIcon :size="20">
                                                <Shop />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Size sản phẩm</span>
                                        </div>
                                    </template>
                                    <ElMenuItem index="1-4-1">
                                        <RouterLink to="/admin/dashboard/product-sizes"
                                            class="flex justify-center items-center space-x-[1rem]">
                                            <ElIcon :size="15">
                                                <Memo />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Danh sách size sản phẩm</span>
                                        </RouterLink>
                                    </ElMenuItem>
                                    <ElMenuItem index="1-4-2">
                                        <RouterLink to="/admin/dashboard/create-product-size"
                                            class="flex justify-center items-center space-x-[1rem]">
                                            <ElIcon :size="15">
                                                <Plus />
                                            </ElIcon>
                                            <span class="el-menu-item__text">Thêm size sản phẩm</span>
                                        </RouterLink>
                                    </ElMenuItem>
                                </ElSubMenu>
                            </ElSubMenu>
                            <ElSubMenu index="2">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <CollectionTag />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý danh mục sản phẩm</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="2-1">
                                    <RouterLink to="/admin/dashboard/categories"
                                        class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Memo />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Danh sách danh mục</span>
                                    </RouterLink>
                                </ElMenuItem>
                                <ElMenuItem index="2-2">
                                    <RouterLink to="/admin/dashboard/create-category"
                                        class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Plus />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Thêm danh mục</span>
                                    </RouterLink>
                                </ElMenuItem>
                            </ElSubMenu>
                            <ElSubMenu index="3">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <OfficeBuilding />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý nhà sản xuất</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="3-1">
                                    <RouterLink to="/admin/dashboard/manufactures"
                                        class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Memo />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Danh sách nhà sản xuất</span>
                                    </RouterLink>
                                </ElMenuItem>
                                <ElMenuItem index="3-2">
                                    <RouterLink to="/admin/dashboard/create-manufacture"
                                        class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Plus />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Thêm nhà sản xuất</span>
                                    </RouterLink>
                                </ElMenuItem>
                            </ElSubMenu>
                            <ElSubMenu index="4">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <User />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý tài khoản</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="4-1">
                                    <RouterLink to="/admin/dashboard/account" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Memo />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Danh sách tài khoản</span>
                                    </RouterLink>
                                </ElMenuItem>
                                <ElMenuItem index="4-2">
                                    <RouterLink to="/admin/dashboard/account/register" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Plus />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Đăng ký tài khoản Admin</span>
                                    </RouterLink>
                                </ElMenuItem>
                            </ElSubMenu>
                            <ElSubMenu index="5">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <Service />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý khách hàng</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="5-1">
                                    <RouterLink  to="/admin/dashboard/customer" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Memo />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Danh sách khách hàng</span>
                                    </RouterLink>
                                </ElMenuItem>
                            </ElSubMenu>
                            <ElSubMenu index="6">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <ShoppingCartFull />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý đơn hàng</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="6-1">
                                    <RouterLink to="/admin/dashboard/order" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Memo />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Danh sách đơn hàng</span>
                                    </RouterLink>
                                </ElMenuItem>
                                <ElMenuItem index="6-2">
                                    <a href="#" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Plus />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Tạo đơn hàng mới</span>
                                    </a>
                                </ElMenuItem>
                            </ElSubMenu>
                            <ElSubMenu index="7">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <Star />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý đánh giá</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="7-1">
                                    <a href="#" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Memo />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Danh sách đánh giá</span>
                                    </a>
                                </ElMenuItem>
                                <ElMenuItem index="7-2">
                                    <a href="#" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Plus />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Tạo đánh giá mới</span>
                                    </a>
                                </ElMenuItem>
                            </ElSubMenu>
                            <ElSubMenu index="8">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <Picture />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý danh mục ảnh</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="8-1">
                                    <a href="#" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Memo />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Danh sách danh mục ảnh</span>
                                    </a>
                                </ElMenuItem>
                                <ElMenuItem index="8-2">
                                    <a href="#" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Plus />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Thêm ảnh mới</span>
                                    </a>
                                </ElMenuItem>
                            </ElSubMenu>
                            <ElSubMenu index="9">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <Lock />
                                        </ElIcon>
                                        <h3 class="menu-title">Quản lý phân quyền</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="9-1">
                                    <a href="#" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Memo />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Danh sách phân quyền</span>
                                    </a>
                                </ElMenuItem>
                            </ElSubMenu>
                            <ElSubMenu index="10">
                                <template #title>
                                    <div class="flex justify-center items-center space-x-[2rem]">
                                        <ElIcon :size="30">
                                            <SetUp />
                                        </ElIcon>
                                        <h3 class="menu-title">Cài đặt</h3>
                                    </div>
                                </template>
                                <ElMenuItem index="10-1">
                                    <RouterLink to="/admin/dashboard/banner" class="flex justify-center items-center space-x-[1rem]">
                                        <ElIcon :size="15">
                                            <Refresh />
                                        </ElIcon>
                                        <span class="el-menu-item__text">Đổi banner/liên kết</span>
                                    </RouterLink>
                                </ElMenuItem>
                            </ElSubMenu>
                        </div>
                    </ElMenu>
                </ElScrollbar>
            </ElAside>
            <ElContainer class="main-panel content transition-all duration-500">
                <ElHeader class="flex justify-center items-center bg-light shadow-md">
                    <button class="nav-toggle w-[4rem] h-[4rem] my-[1.7rem] mr-[1.5rem] float-right ml-auto order-5"
                        :class="{ 'toggled': isToggle }" @click="toggleSidebar">
                        <span class="icon-bar bar--1 w-[2.4rem] h-[0.2rem] block bg-primary"></span>
                        <span class="icon-bar bar--2 w-[2.4rem] h-[0.2rem] block bg-primary mt-[0.4rem]"></span>
                        <span class="icon-bar bar--3 w-[2.4rem] h-[0.2rem] block bg-primary mt-[0.4rem]"></span>
                    </button>
                    <button
                        class="nav-minimize w-[3rem] h-[3rem] bg-purple rounded-full hidden justify-center items-center text-white my-[1.8rem] mr-[2rem] shadow-purple shadow-md"
                        @click="collapseSidebar">
                        <ElIcon :size="20">
                            <More v-if="!isCollapse" />
                            <Menu v-else />
                        </ElIcon>
                    </button>
                    <RouterLink to="/admin/dashboard" class="text-[2rem] font-bold order-1 my-[1.8rem]">
                        <span>Overview</span>
                    </RouterLink>
                    <ElForm class="my-[1.8rem] order-2 ml-[4rem] hidden" @submit.prevent="handleSubmit">
                        <ElFormItem>
                            <ElInput placeholder="Search" class="h-[4rem]" v-model="search">
                                <template #prefix>
                                    <ElIcon :size="20">
                                        <Search />
                                    </ElIcon>
                                </template>
                            </ElInput>
                        </ElFormItem>
                    </ElForm>
                    <ElDropdown class="order-3 ml-[4rem]">
                        <span class="el-dropdown-link">
                            <ElIcon :size="20">
                                <Bell />
                            </ElIcon>
                        </span>
                        <template #dropdown>
                            <ElDropdownMenu class="w-[20rem]">
                                <ElDropdownItem>
                                    <span class="truncate">Lorem ipsum dolor sit amet.</span>
                                </ElDropdownItem>
                            </ElDropdownMenu>
                        </template>
                    </ElDropdown>
                    <div class="order-4 flex justify-center items-center space-x-[1rem] ml-auto">
                        <button @click="$router.back()"
                            class="w-[3rem] h-[3rem] bg-purple rounded-full flex justify-center items-center text-white my-[1.8rem] mr-[2rem] shadow-purple shadow-md">
                            <ElIcon :size="20">
                                <Back />
                            </ElIcon>
                        </button>
                        <button @clik="$router.forward()"
                            class="w-[3rem] h-[3rem] bg-purple rounded-full flex justify-center items-center text-white my-[1.8rem] mr-[2rem] shadow-purple shadow-md">
                            <ElIcon :size="20">
                                <Right />
                            </ElIcon>
                        </button>
                    </div>
                </ElHeader>
                <ElMain class="min-h-[100vh] w-full">
                    <RouterView v-slot="{ Component }">
                      <component :is="Component" :key="$route.path" />
                    </RouterView>
                </ElMain>
                <ElFooter class="w-full bg-light shadow-md border-t border-purple flex items-center">
                    <DashboardFooter />
                </ElFooter>
            </ElContainer>
        </ElContainer>
    </section>
</template>

<script setup lang="ts">
import {
    User, Setting, Search, More,
    Menu, Box, Plus, Memo, Shop, CollectionTag,
    OfficeBuilding, Service, ShoppingCartFull, Star,
    Picture, Lock, SetUp, Refresh, Bell, Back, Right
} from '@element-plus/icons-vue';
import { ElSubMenu } from 'element-plus';
import { ref, onBeforeMount } from "vue";
import DashboardFooter from '../../../components/admin/DashboardFooter.vue';
import { useAdminStore } from '../../../stores/admin/Admin';
import { useRouter } from 'vue-router';

const isToggle = ref<boolean>(false);
const isCollapse = ref<boolean>(false);
const search = ref<string>("");
const { logout, getAdmin, profile } = useAdminStore();
const router = useRouter();

const handleSubmit = (): void => {
    console.log(search.value);
}

const collapseSidebar = () => {
    isCollapse.value = !isCollapse.value;
    isToggle.value = false;
}

const toggleSidebar = () => {
    isToggle.value = !isToggle.value;
    isCollapse.value = false;
}

window.addEventListener('resize', e => {
    if (window.innerWidth <= 1024) {
        isCollapse.value = false;
    } else {
        isToggle.value = false;
    }
});

const handleLogout = async () => {
    await logout();
    router.push({ path: '/admin/login' });
}

onBeforeMount(async () => {
    await getAdmin();
});
</script>

<style lang="scss" scoped>
.dashboard {
    --sidebar-width: 23rem;

    @media (min-width: 1024px) {
        --sidebar-width: 26rem;
    }

    &.nav-open,
    &.nav-collapse {
        @media (min-width: 1024px) {
            --sidebar-width: 8rem;
        }

        .el-aside {
            @apply translate-x-0;

            .logo {
                &-mini {
                    @media (min-width: 1024px) {
                        @apply opacity-100 ml-[6rem];
                    }
                }

                &-normal {
                    @media (min-width: 1024px) {
                        @apply opacity-0 invisible;
                    }
                }
            }

            .el-sub-menu__title {
                @media (min-width: 1024px) {
                    @apply pl-[2.5rem];
                }
            }

            .el-menu-item {
                @media (min-width: 1024px) {
                    @apply pl-[2.5rem];
                }

                &__text {
                    @media (min-width: 1024px) {
                        @apply opacity-0 invisible;
                    }
                }
            }
        }

        .main-panel {
            @apply translate-x-[-23rem];

            @media (min-width: 1024px) {
                @apply translate-x-0;
            }
        }

        .menu-title {
            @media (min-width: 1024px) {
                @apply opacity-0 invisible;
            }
        }
    }
}

.el-aside {
    width: var(--sidebar-width);
    overflow: hidden;

    @media (min-width: 1024px) {
        @apply left-0 right-auto translate-x-0;
    }

    .logo {
        &-mini {
            @apply opacity-0 mr-[-5rem];
        }

        &::before {
            content: '';
            position: absolute;
            width: 85%;
            height: 0.1rem;
            @apply bg-gray-500;
            bottom: 0;
        }
    }
}

.el-menu {
    border: none;

    &--collapse {
        width: 100%;
    }
}

.menu-title {
    @apply max-w-[12rem] truncate;

    @media (min-width: 1024px) {
        @apply max-w-[15rem];
    }
}

.el-menu-item {
    @apply transition-all duration-500;

    &__text {
        @apply max-w-[12rem] truncate;

        @media (min-width: 1024px) {
            @apply max-w-[15rem];
        }
    }
}

.el-header {
    .el-form {
        @media (min-width: 1024px) {
            @apply block;
        }

        &-item {
            margin: 0;
        }
    }
}

.el-scrollbar {
    margin-top: 2rem;
}

.main-panel {
    @media (min-width: 1024px) {
        max-width: calc(100% - var(--sidebar-width));
        float: right;
        margin-left: auto;
    }
}

.nav-minimize {
    @media (min-width: 1024px) {
        @apply flex;
    }
}

// Toggle button animate
.nav-toggle {
    @media (min-width: 1024px) {
        @apply opacity-0 invisible pointer-events-none;
    }

    >span.bar--1 {
        top: 0;
        animation: topbar-back 500ms 0s ease;
        animation-fill-mode: forwards;
    }

    >span.bar--2 {
        opacity: 1;
    }

    >span.bar--3 {
        bottom: 0;
        animation: bottom-back 500ms 0s ease;
        animation-fill-mode: forwards;
    }

    &.toggled {
        >span.bar--1 {
            top: 0.6rem;
            animation: topbar-x 500ms 0s ease;
            animation-fill-mode: forwards;
        }

        >span.bar--2 {
            opacity: 0;
        }

        >span.bar--3 {
            bottom: 0.6rem;
            animation: bottombar-x 500ms 0s ease;
            animation-fill-mode: forwards;
        }
    }

    @keyframes topbar-x {
        0% {
            top: 0rem;
            transform: rotate(0deg);
        }

        45% {
            top: 0.6rem;
            transform: rotate(145deg);
        }

        75% {
            transform: rotate(130deg);
        }

        100% {
            transform: rotate(135deg);
        }
    }

    @keyframes topbar-back {
        0% {
            top: 0.6rem;
            transform: rotate(135deg);
        }

        45% {
            transform: rotate(-10deg);
        }

        75% {
            transform: rotate(5deg);
        }

        100% {
            top: 0rem;
            transform: rotate(0);
        }
    }

    @keyframes bottombar-x {
        0% {
            bottom: 0rem;
            transform: rotate(0deg);
        }

        45% {
            bottom: 0.6rem;
            transform: rotate(-145deg);
        }

        75% {
            transform: rotate(-130deg);
        }

        100% {
            transform: rotate(-135deg);
        }
    }

    @keyframes bottombar-back {
        0% {
            bottom: 0.6rem;
            transform: rotate(-135deg);
        }

        45% {
            transform: rotate(10deg);
        }

        75% {
            transform: rotate(-5deg);
        }

        100% {
            bottom: 0rem;
            transform: rotate(0);
        }
    }
}
</style>