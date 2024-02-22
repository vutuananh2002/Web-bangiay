<template>
    <div class="min-h-[100vh] w-full common-layout flex">
        <ElContainer>
            <ElHeader>
                <div ref="header"
                    class="header__inner px-12 fixed z-[999] w-full h-[7rem] top-0 left-0 bg-base text-black shadow-md flex justify-between items-center space-x-[2rem]">
                  <h1 class="header__item text-4xl" style="font-family: 'Dancing Script', cursive;">LucciBoo Sneaker</h1>
                    <nav class="header__item">
                      <ul class="flex space-x-[1.5rem]">
                        <li class="px-4 py-2 hover:text-primary">
                          <RouterLink to="/">Trang chủ</RouterLink>
                        </li>
                        <li class="px-4 py-2 hover:text-primary">
                          <RouterLink to="/shop">Sản phẩm</RouterLink>
                        </li>
                        <li class="px-4 py-2 hover:text-primary">
                          <RouterLink to="/about">Về chúng tôi</RouterLink>
                        </li>
                      </ul>
                    </nav>
                    <div ref="headerItem" class="header__item flex justify-center items-center space-x-[2rem]">
                        <ElSkeleton v-if="isAuthenticated()" :loading="loading" animated :count="1"
                            class="flex justify-center items-center space-x-[1rem]">
                            <template #template>
                                <ElSkeletonItem variant="image"
                                    style="width: 4rem; height: 4rem; border-radius: 50%;" />
                                <ElSkeletonItem variant="text" style="width: 10rem; height: 3rem;" />
                            </template>
                            <template #default>
                                <ElDropdown>
                                    <div class="flex justify-center items-center space-x-[1rem] cursor-pointer">
                                        <div
                                            class="w-[4rem] aspect-square rounded-full overflow-hidden border-[0.2rem] border-purple drop-shadow-lg">
                                            <img :src="profile.avatar.url" :alt="profile.username"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <h3 class="text-transparent bg-text-gradient bg-clip-text drop-shadow-lg"
                                            v-text="`${profile.customer?.full_name}`" />
                                    </div>
                                    <template #dropdown>
                                        <ElDropdownItem>
                                            <RouterLink to="/user/account/profile"
                                                class="flex justify-center items-center space-x-[2rem] p-4">
                                                <ElIcon :size="20">
                                                    <User />
                                                </ElIcon>
                                                <span>Tài khoản của tôi</span>
                                            </RouterLink>
                                        </ElDropdownItem>
                                        <ElDropdownItem>
                                            <RouterLink to="/user/order/information" class="flex justify-center items-center space-x-[2rem] p-4">
                                                <ElIcon :size="20">
                                                    <ShoppingTrolley />
                                                </ElIcon>
                                                <span>Đơn hàng của tôi</span>
                                            </RouterLink>
                                        </ElDropdownItem>
                                        <ElDropdownItem divided>
                                            <div class="flex justify-center items-center space-x-[2rem] p-4">
                                                <ElIcon :size="20">
                                                    <Setting />
                                                </ElIcon>
                                                <span>Cài đặt</span>
                                            </div>
                                        </ElDropdownItem>
                                        <ElDropdownItem>
                                            <div class="flex justify-center items-center space-x-[2rem] p-4" @click="handleLogout">
                                                <span class="material-symbols-outlined">
                                                    logout
                                                </span>
                                                <span>Đăng xuất</span>
                                            </div>
                                        </ElDropdownItem>
                                    </template>
                                </ElDropdown>
                            </template>
                        </ElSkeleton>
                        <ElTooltip placement="bottom-end" effect="dark" content="Giỏ hàng">
                            <RouterLink to="/cart-detail" class="flex justify-center items-center drop-shadow-lg">
                                <button
                                    class="btn-primary rounded-full flex justify-center items-center w-[5rem] h-[5rem] text-white">
                                    <span class="material-symbols-outlined">
                                        shopping_cart
                                    </span>
                                </button>
                                <span
                                    class="w-[2rem] h-[2rem] text-xl bg-secondary shadow-md rounded-full text-white flex justify-center items-center absolute top-[-0.5rem] right-[-0.2rem]"
                                    v-text="`${totalItemInCart > 9 ? '9+' : totalItemInCart}`" />
                            </RouterLink>
                        </ElTooltip>
                        <RouterLink to="/auth/login" v-if="!isAuthenticated()">
                            <button class="btn-primary">Đăng nhập</button>
                        </RouterLink>
                    </div>
                </div>
            </ElHeader>
            <ElContainer>
                <ElContainer>
                    <ElMain class="min-h-[100vh] w-full flex">
                        <RouterView v-slot="{ Component }">
                            <Transition name="router">
                                <KeepAlive>
                                    <component :is="Component" :key="$route.path" />
                                </KeepAlive>
                            </Transition>
                        </RouterView>
                    </ElMain>
                    <ElFooter class="footer flex bg-light shadow-md">
                        <TheFooter />
                    </ElFooter>
                </ElContainer>
            </ElContainer>
        </ElContainer>
    </div>
</template>

<script setup lang="ts">
import TheFooter from '@/components/TheFooter.vue';
import { ElementPlus } from '@element-plus/icons-vue';
import { ElSkeleton, ElSkeletonItem, ElTooltip } from 'element-plus';
import { ref, onBeforeMount, onMounted } from 'vue';
import { useCartStore } from '@/stores/user/Cart';
import { storeToRefs } from 'pinia';
import { useUserStore } from '@/stores/user/User';
import { gsap } from "gsap";
import { Power1 } from 'gsap';
import { CustomEase } from 'gsap/all';
import { onClickOutside } from '@vueuse/core';
import { User, ShoppingTrolley, Setting, Bell } from '@element-plus/icons-vue';

interface Menu {
    title: string,
    link: string,
}

const { totalItemInCart } = storeToRefs(useCartStore());
const { isAuthenticated, getUser, profile, isRemember, setAccessToken, logout } = useUserStore();

const header = ref<HTMLElement>();
const loading = ref<boolean>(false);
const menu = ref<Menu[]>([
    {
        title: 'Home',
        link: '/',
    },
    {
        title: 'Tất cả sản phẩm',
        link: '/shop',
    },
]);

// Header animate 
const headerAnimate = () => {
    const headerTimeline = gsap.timeline({ defaults: { duration: 1 } });
    headerTimeline
        .from(header.value!, {
            y: '-100%',
            ease: CustomEase.create("custom", "M0,0 C0.025,0.11 0.079,0.253 0.102,0.34 0.146,0.51 0.176,1 0.176,1 0.176,1 0.266,0.59 0.38,0.59 0.585,0.59 0.582,0.99 0.582,0.99 0.582,0.99 0.561,0.808 0.72,0.736 0.834,0.684 0.893,0.713 1,0.99 "),
        })
        .from('.header__item', {
            opacity: 0,
            stagger: .5,
        });
    gsap.from('.footer', {
        y: '-100%',
        duration: 1,
        ease: CustomEase.create("custom", "M0,0 C0.025,0.11 0.079,0.253 0.102,0.34 0.146,0.51 0.176,1 0.176,1 0.176,1 0.266,0.59 0.38,0.59 0.585,0.59 0.582,0.99 0.582,0.99 0.582,0.99 0.561,0.808 0.72,0.736 0.834,0.684 0.893,0.713 1,0.99 "),
    });
}

// Hamburger menu animate
const isToggle = ref<boolean>(false);
const burgerTop = ref<HTMLElement>();
const burgerMiddle = ref<HTMLElement>();
const burgerBottom = ref<HTMLElement>();
const timeline = gsap.timeline({ paused: true, reversed: true });

const menuAnimate = () => {
    timeline.to(burgerTop.value!, {
        duration: 0.2,
        y: 6,
        rotate: 135,
        yoyo: true,
        ease: Power1.easeInOut,
    }).to(burgerMiddle.value!, {
        duration: 0.1,
        opacity: 0,
        x: 10,
        ease: Power1.easeIn,
    }).to(burgerBottom.value!, {
        duration: 0.2,
        y: -6,
        rotate: -135,
        yoyo: true,
        ease: Power1.easeInOut,
    });
    timeline.from('.links', {
        duration: 0.3,
        opacity: 0,
        x: -200,
        stagger: 0.2,
        ease: Power1.easeInOut
    });
}

const toggleShowMenu = () => {
    timeline.reversed() ? timeline.play() : timeline.reverse();
    isToggle.value = !isToggle.value;
}

const sideMenu = ref();
const sideMenuBtn = ref<HTMLElement>();

onClickOutside(sideMenu, (e) => {
    if (sideMenuBtn.value === e.target || sideMenuBtn.value?.contains(e.target as Node)) return;
    timeline.reverse();
    isToggle.value = false;
});

const handleLogout = async () => {
    await logout();
}

onBeforeMount(async () => {
    loading.value = true;
    await getUser();
    loading.value = false;

    window.addEventListener('beforeunload', () => {
        if (isRemember) return;
        setAccessToken(null);
    });
});

onMounted(() => {
    gsap.registerPlugin(CustomEase);
    headerAnimate();
    menuAnimate();
});
</script>

<style lang="scss" scoped>
.search {
    &-input {
        &::-webkit-search-cancel-button {
            display: none;
        }
    }
}

.links {
    box-shadow: 0.2rem 0.2rem 1.5rem #725bd6,
        -0.2rem -0.2rem 1.5rem #9e7dff;
}
</style>