import type { AccessToken, AccessTokenResponse, AuthResponse, LoginCredentials, ResponseData, UserDataResponse } from '@/types/Type';
import Api from '../../Api';

const url = '/auth';

const login = async (credentials: LoginCredentials) => {
    const res: AuthResponse = await Api.post(`${url}/login`, credentials);

    return res;
}

const logout = async (token: AccessToken) => {
    const res: ResponseData = await Api.post(`${url}/logout`, {}, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const register = async (credentials: any) => {
    const res: AccessTokenResponse = await Api.post(`${url}/register`, credentials, {
        headers: {
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

export { login, logout, register };