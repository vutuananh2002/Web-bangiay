import type { AccessToken, ColorDetailResponse, ColorsDataResponse, ColorsDataWithPaginationResponse, ResponseData } from '@/types/Type';
import Api from '../Api';

const url = '/colors';

const getAllColors = async (query: string = '') => {
    const res: ColorsDataResponse = await Api.get(`${url}?search=${query}`);

    return res;
}

const getAllColorsWithPaginate = async (params: object = {}) => {
    const res: ColorsDataWithPaginationResponse = await Api.get(`${url}/with-paginate`, {
        params: params,
    });

    return res;
}

const createColor = async (token: AccessToken, data: any) => {
    const res: ColorDetailResponse = await Api.post(`${url}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const updateColor = async (colorId: number|string, token: AccessToken, data: any) => {
    const res: ColorDetailResponse = await Api.put(`${url}/${colorId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const deleteColor = async (colorId: number|string, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${colorId}`,{
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

export { getAllColors, updateColor, deleteColor, createColor, getAllColorsWithPaginate };