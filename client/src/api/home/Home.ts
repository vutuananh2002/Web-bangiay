import type { HomeDataResponse } from '@/types/Type';
import Api from '../Api';

const url =  '/home';

const getHome = async () => {
    const res: HomeDataResponse = await Api.get(url);

    return res;
}

export { getHome };