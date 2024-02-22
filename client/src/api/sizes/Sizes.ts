import type { AccessToken, ResponseData, SizeDetailResponse, SizesDataResponse, SizesDataWithPaginateResponse } from '@/types/Type';
import Api from '../Api';

const url = '/sizes';

const getAllSizes = async (query: string = '') => {
    const res: SizesDataResponse = await Api.get(`${url}?search=${query}`);

    return res;
}

const getAllSizesWithPaginate = async (params: object = {}) => {
    const res: SizesDataWithPaginateResponse = await Api.get(`${url}/with-paginate`, {
        params: {
            ...params,
        }
    });

    return res;
}

const createSize = async (token: AccessToken, data: any) => {
    const res: SizeDetailResponse = await Api.post(`${url}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const updateSize = async (sizeId: number|string, token: AccessToken, data: any) => {
    const res: SizeDetailResponse = await Api.put(`${url}/${sizeId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const deleteSize = async (sizeId: number|string, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${sizeId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

export { getAllSizes, updateSize, deleteSize, createSize, getAllSizesWithPaginate };