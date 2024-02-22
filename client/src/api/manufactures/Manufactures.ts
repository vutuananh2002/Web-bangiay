import type { AccessToken, ImageDetailResponse, ManufactureDetailResponse, ManufacturesDataResponse, ManufacturesDataWithPaginateResponse, ResponseData } from '@/types/Type';
import Api from '../Api';

const url = '/manufactures';

const getAllManufactures = async (query: string = '') => {
    const res: ManufacturesDataResponse = await Api.get(`${url}?search=${query}`);

    return res;
}

const getAllManufacturesWithPaginate = async (query: string = '', page: number = 1) => {
    const res: ManufacturesDataWithPaginateResponse = await Api.get(`${url}/with-paginate?search=${query}&page=${page}`);

    return res;
}

const getManufactureDetail = async (manufactureId: number | string) => {
    const res: ManufactureDetailResponse = await Api.get(`${url}/${manufactureId}`);

    return res;
}

const createManufacture = async (token: AccessToken, data: any) => {
    const res: ManufactureDetailResponse = await Api.post(`${url}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

const updateManufacture = async (manufactureId: number | string, token: AccessToken, data: any) => {
    const res: ManufactureDetailResponse = await Api.put(`${url}/${manufactureId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const addManufactureLogo = async (manufactureId: number | string, token: AccessToken, data: any) => {
    const res: ImageDetailResponse = await Api.post(`${url}/add-logo/${manufactureId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

const deleteManufactureLogo = async (manufactureId: number | string, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/delete-logo/${manufactureId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const deleteManufacture = async (manufactureId: number | string, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${manufactureId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

export { getAllManufactures, getAllManufacturesWithPaginate, deleteManufacture, getManufactureDetail, deleteManufactureLogo, addManufactureLogo, updateManufacture, createManufacture };