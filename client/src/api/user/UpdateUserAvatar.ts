import type { AccessToken, UserAvatarResponse } from '../../types/Type';
import Api from '../Api';

const url = '/users/update-avatar/';

const updateUserAvatar = async (userId: number|string, token: AccessToken, data: any): Promise<UserAvatarResponse> => {
    const res: Promise<UserAvatarResponse> = Api.post(`${url}${userId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
            'Content-Type': 'multipart/form-data',
        }
    });

    return res;
}

export { updateUserAvatar };