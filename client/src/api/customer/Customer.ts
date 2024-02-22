import type { AccessToken, CustomerDataResponse, CustomersDataWithPaginateResponse, ResponseData } from '@/types/Type';
import Api from '../Api';

const url = '/customers';

const getAllCustomers = async (page: number = 1, token: AccessToken) => {
    const res: CustomersDataWithPaginateResponse = await Api.get(`${url}?page=${page}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const updateCustomer = async (customerId: number, token: AccessToken, data: any) => {
    const res: CustomerDataResponse = await Api.put(`${url}/${customerId}`, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
} 

const deleteCustomer = async (customerId: number, token: AccessToken) => {
    const res: ResponseData = await Api.delete(`${url}/${customerId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

export { updateCustomer, getAllCustomers, deleteCustomer };