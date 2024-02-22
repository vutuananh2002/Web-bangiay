import type { AccessToken, ResponseData } from '@/types/Type';
import Api from '../Api';

const url = '/email';

const verifyEmail = async (id: any, hash: any, params: object) => {
    const res: ResponseData = await Api.get(`${url}/verify/${id}/${hash}`, {
        params: {
            ...params,
        }
    });

    return res;
}

const resetEmail = async (token: AccessToken, data: any) => {
    const res: ResponseData = await Api.post(`${url}/change-email`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const verifyChange = async (id: any, hash: any, params: object) => {
    const res: ResponseData = await Api.get(`${url}/verify-change/${id}/${hash}`, {
        params: {
            ...params,
        }
    });

    return res;
}

export { verifyEmail, resetEmail, verifyChange };