import type { AccessToken, ImageDetailResponse, ProductData, ProductDetailResponse, ProductsDataWithPaginateResponse, ResponseData } from '../../types/Type';
import Api from '../Api';

const url = '/products';

const getAllProducts = async (query: string = '', page: number = 1, params: object = {}) => {
    const res: ProductsDataWithPaginateResponse = await Api.get(`${url}?search=${query}&page=${page}`, {
        params: {
            ...params,
        },
    });

    return res;
}

const getProductDetails = async (productId: number|string): Promise<ProductData> => {
    const res = await Api.get(`${url}/${productId}`);

    return res.data;
}

const createProduct = async (token: AccessToken, data: any) => {
    const res: ProductDetailResponse = await Api.post(`${url}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

const updateProduct = async (productId: number|string, token: AccessToken, data: any) => {
    const res: ProductDetailResponse = await Api.post(`${url}/${productId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

const addProductImage = async (productId: number|string, token: AccessToken, data: any) => {
    const res: ImageDetailResponse = await Api.post(`${url}/add-image/${productId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

const deleteProductImage = async (productId: number|string, token: AccessToken, data: any) => {
    const res: ResponseData = await Api.post(`${url}/delete-image/${productId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const deleteProduct = async (productId: number|string, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${productId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
} 

export { getAllProducts, getProductDetails, deleteProduct, createProduct, updateProduct, deleteProductImage, addProductImage };