import { OrderStatus } from '@/types/Enum';
import type { AccessToken, OrderDetailResponse, OrdersDataWithPaginateResponse } from '@/types/Type';
import Api from '../Api';

const url = '/orders';

const getAllOrders = async (page: number = 1, token: AccessToken) => {
    const res: OrdersDataWithPaginateResponse = await Api.get(`${url}?page=${page}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const getOrderDetail = async (orderId: number, token: AccessToken) => {
    const res: OrderDetailResponse = await Api.get(`${url}/${orderId}`, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const approveOrder = async (orderId: number, token: AccessToken) => {
    const res: OrderDetailResponse = await Api.put(`${url}/${orderId}`, {
        status: OrderStatus.Delivering,
    }, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const cancelOrder = async (orderId: number, token: AccessToken) => {
    const res: OrderDetailResponse = await Api.post(`${url}/cancel/${orderId}`, {}, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const confirmReceivedOrder = async (orderId: number, token: AccessToken) => {
    const res: OrderDetailResponse = await Api.post(`${url}/confirm/${orderId}`, {}, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

const changeOrderStatus = async (orderId: number, token: AccessToken, status: OrderStatus) => {
    const res: OrderDetailResponse = await Api.put(`${url}/${orderId}`, {
        status: status,
    }, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }   
    });

    return res;
}

export { getAllOrders, getOrderDetail, cancelOrder, approveOrder, changeOrderStatus, confirmReceivedOrder };