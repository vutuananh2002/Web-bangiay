import type { AccessToken, ResponseData, TypesDataResponse, TypeDetailResponse } from '@/types/Type';
import Api from '../Api';

const url = '/types';

const getAllTypes = async (query: string = '') => {
    const res: TypesDataResponse = await Api.get(`${url}?search=${query}`);

    return res;
}

const createType = async (token: AccessToken, data: any) => {
    const res: TypeDetailResponse = await Api.post(`${url}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const updateType = async (typeId: number, token: AccessToken, data: any) => {
    const res: TypeDetailResponse = await Api.put(`${url}/${typeId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const deleteType = async (typeId: number, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${typeId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

export { getAllTypes, createType, updateType, deleteType };