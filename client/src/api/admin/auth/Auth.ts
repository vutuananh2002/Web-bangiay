import type { LoginCredentials, AuthResponse, AccessToken, ResponseData, AccessTokenResponse } from '../../../types/Type';
import Api from '../../Api';

const url = '/admin/auth';

const login =  async (credentials: LoginCredentials) => {
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

const register = async (credentials: any, token: AccessToken) => {
    const res: AccessTokenResponse = await Api.post(`${url}/register`, credentials, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

export { login, logout, register };