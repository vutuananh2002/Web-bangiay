import type { AccessToken } from '../../types/Type';
import Api from '../Api';

const url = '/admin/admin-profile/';

const updateProfile = async (adminId: number, token: AccessToken, data: any) => {
    const res = await Api.put(`${url}${adminId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

export { updateProfile };