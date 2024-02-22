import type { AccessToken, ResponseData, UserDataResponse, UsersDataWithPaginateResponse } from '../../types/Type';
import Api from '../Api';

const url = '/users';

const getAllUsers = async (page: number = 1, token: AccessToken) => {
    const res: UsersDataWithPaginateResponse = await Api.get(`${url}?page=${page}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const getUserDetail = async (userId: number, token: AccessToken) => {
    const res: UserDataResponse = await Api.get(`${url}/${userId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

const updateUser = async (userId: number|string, token: AccessToken, data: any) => {
    const res: UserDataResponse = await Api.put(`${url}/${userId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res.data;
}

const deleteUser = async (userId: number, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${userId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });

    return res;
}

export { getAllUsers, updateUser, getUserDetail, deleteUser };