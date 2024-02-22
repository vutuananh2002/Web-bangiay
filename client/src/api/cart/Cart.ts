import type { AccessToken, CartDetailResponse, OrderDetailResponse, ResponseData } from '@/types/Type';
import Api from '../Api';

const url = '/carts';

const createCart = async (token: AccessToken) => {
    const res: CartDetailResponse = await Api.post(url, {}, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const getCart = async (cartId: number, cartKey: string, token: AccessToken) => {
    const res: CartDetailResponse = await Api.get(`${url}/${cartId}`, {
        params: {
            key: cartKey,
        },
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const addProductToCart = async (cartId: number, token: AccessToken, data: any) => {
    const res: CartDetailResponse = await Api.post(`${url}/add-product/${cartId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const removeProductFromCart = async (cartId: number, token: AccessToken, data: any) => {
    const res: CartDetailResponse = await Api.post(`${url}/remove-product/${cartId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const deleteCart = async (cartId: number, cartKey: string, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${cartId}`, {
        params: {
            key: cartKey,
        },
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const checkout = async (cartId: number, token: AccessToken, data: any) => {
    const res: OrderDetailResponse = await Api.post(`${url}/checkout/${cartId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

export { createCart, getCart, addProductToCart, removeProductFromCart, deleteCart, checkout };
