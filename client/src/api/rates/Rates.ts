import type { RateDataResponse } from '@/types/Type';
import Api from '../Api';

const url = '/rates';

const getRates = async (params: object = {}) => {
    const res: RateDataResponse = await Api.get(url, {
        params: {
            ...params,
        }
    });

    return res;
}

export { getRates };