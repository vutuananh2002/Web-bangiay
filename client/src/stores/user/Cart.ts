import { currencyParser } from "@/helpers/NumberFormat";
import type { CartData, CartItem } from "@/types/Type";
import { ElMessage } from "element-plus";
import { defineStore } from "pinia";
import { ref, reactive, computed } from 'vue';
import { toNumber } from 'lodash';
import { useUserStore } from "./User";
import { createCart as ApiCreateCart, getCart as ApiGetCart, addProductToCart, removeProductFromCart, deleteCart as ApiDeleteCart } from "@/api/cart/Cart";
import router from "@/router";

export const useCartStore = defineStore('cart', () => {
    const user = useUserStore();

    const cart = reactive<CartData>({
        id: 0,
        cart_key: "",
        products: [],
        created_at: "",
        updated_at: ""
    });
    const cartId = ref<number | null>(cart.id);
    const cartKey = ref<string | null>(cart.cart_key);

    const totalItemInCart = computed(() => cart.products.length);
    const totalPrice = computed(() => {
        return cart.products.reduce((acc, curr) => acc + (curr.quantity * toNumber(currencyParser(curr.price.toString()))), 0);
    });

    // Whether check current user has cart
    const hasCart = (): boolean => {
        return cartKey.value ? true : false;
    }

    const getCart = async () => {
        if (hasCart()) {
            if (!user.isAuthenticated()) return;
            const res = await ApiGetCart(cartId.value!, cartKey.value!, user.accessToken!);
            setCart(res.data);
        } else {
            await createCart();
        }
    }

    // Set data to the cart
    const setCart = (data: CartData) => {
        cart.id = data.id;
        cart.cart_key = data.cart_key;
        cart.products = data.products;
        cart.created_at = data.created_at;
        cart.updated_at = data.updated_at;
        cartId.value = data.id;
        cartKey.value = data.cart_key;
    }

    const setCartIdAndKey = (id: number | null, key: string | null) => {
        cartId.value = id;
        cartKey.value = key;
    }

    const createCart = async () => {
        if (!user.isAuthenticated()) return;

        const res = await ApiCreateCart(user.accessToken!);
        setCart(res.data);
    }

    const addToCart = async (item: CartItem) => {
        if (!user.isAuthenticated()) {
            ElMessage.error('Vui lòng đăng nhập.');
            router.replace({
                path: '/auth/login',
            });
            return;
        }
        
        const product = {
            key: cartKey.value,
            product_id: item.id,
            quantity: item.quantity,
            color_id: item.color.id,
            type_id: item.type.id,
            size_id: item.size.id,
        }

        try {
            const res = await addProductToCart(cartId.value!, user.accessToken!, product);
            cart.products = [...res.data.products];
            ElMessage.success(res.message);
        } catch (err: any) {
            console.log(err);
        }
    }

    const cartDetail = () => {
        return cart.products;
    }

    const removeItemFromCart = async (item: CartItem) => {
        try {
            const res = await removeProductFromCart(cartId.value!, user.accessToken!, {
                key: cartKey.value,
                products: [item.id],
            });
            cart.products = cart.products.filter(value => value.id !== item.id);
        } catch (err: any) {
            console.log(err);
        }
    }

    const removeAllItemFromCart = async () => {
        const products = cart.products.map(value => value.id);
        try {
            const res = await removeProductFromCart(cartId.value!, user.accessToken!, {
                key: cartKey.value,
                products: products,
            });
            cart.products = [];
        } catch (err: any) {
            console.log(err);
        }
    }

    const deleteCart = async () => {
        try {
            const res = await ApiDeleteCart(cartId.value!, cartKey.value!, user.accessToken!);
            setCartIdAndKey(null, null);
            cart.products = [];
        } catch (err: any) {
            console.log(err);
        }
    }

    const isEmpty = (): boolean => {
        return cart.products.length === 0;
    }

    return {
        cart,
        cartId,
        cartKey,
        totalItemInCart,
        totalPrice,
        addToCart,
        removeItemFromCart,
        removeAllItemFromCart,
        deleteCart,
        cartDetail,
        getCart,
        setCartIdAndKey,
        hasCart,
        isEmpty,
    }
}, {
    persist: {
        storage: localStorage,
        paths: ['cartKey', 'cartId'],
    }
});