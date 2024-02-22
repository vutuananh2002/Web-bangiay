import type { AccessToken, UserDataResponse } from '../../types/Type';
import Api from '../Api';

const url = '/users';

const updateUser = async (userId: number|string, token: AccessToken, data: any) => {
    const res: UserDataResponse = await Api.put(`${url}/${userId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res.data;
}

export { updateUser };