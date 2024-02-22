import type { AccessToken, BannersDataResponse, ResponseData, BannerDetailResponse } from '@/types/Type';
import Api from '../Api';

const url = '/banners';

const getAllBanners = async () => {
    const res: BannersDataResponse = await Api.get(url);

    return res;
}

const getBannerDetail = async (bannerId: number, token: AccessToken) => {
    const res: BannerDetailResponse = await Api.get(`${url}/${bannerId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const createBanner = async (token: AccessToken, data: any) => {
    const res: BannerDetailResponse = await Api.post(url, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

const updateBanner = async (bannerId: number, token: AccessToken, data: any) => {
    const res: BannerDetailResponse = await Api.post(`${url}/${bannerId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

const deleteBanner = async (bannerId: number, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${bannerId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

export { getAllBanners, getBannerDetail, createBanner, updateBanner, deleteBanner };