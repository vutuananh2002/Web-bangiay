import type { PricesDataResponse } from '@/types/Type';
import Api from '../Api';

const url = '/prices';

const getPrices = async (params: object = {}) => {
    const res: PricesDataResponse = await Api.get(url, {
        params: {
            ...params,
        }
    });

    return res;
}

export { getPrices };