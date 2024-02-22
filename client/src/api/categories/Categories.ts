import type { AccessToken, CategoriesDataResponse, CategoriesDataWithPaginateResponse, CategoryDetailResponse, ResponseData } from '@/types/Type';
import Api from '../Api';

const url = '/categories';

const getAllCategories = async (query: string = '') => {
    const res: CategoriesDataResponse = await Api.get(`${url}?search=${query}`);

    return res;
}

const getAllCategoriesWithPaginate = async (query: string = '', page: number = 1) => {
    const res: CategoriesDataWithPaginateResponse = await Api.get(`${url}/with-paginate?search=${query}&page=${page}`);

    return res;
}

const createCategory = async (token: AccessToken, data: any) => {
    const res: CategoryDetailResponse = await Api.post(`${url}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const updateCategory = async (categoryId: number|string, token: AccessToken, data: any) => {
    const res: CategoryDetailResponse = await Api.put(`${url}/${categoryId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });
    
    return res;
}

const deleteCategory = async (categoryId: number|string, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${categoryId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

export { getAllCategories, getAllCategoriesWithPaginate, updateCategory, deleteCategory, createCategory };