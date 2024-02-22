import type { AccessToken, UserDataResponse } from '../../types/Type';
import Api from '../Api';

const url = '/auth/current-user';

const getCurrentUser = async (token: AccessToken) => {
    const res: UserDataResponse = await Api.get(url, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res.data;
}

export { getCurrentUser };