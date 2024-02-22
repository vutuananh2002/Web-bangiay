import type { AccessToken, ReviewDetailResponse } from '@/types/Type';
import Api from '../Api';

const url = '/reviews';

const createComment = async (token: AccessToken, data: any) => {
    const res: ReviewDetailResponse = await Api.post(url, data, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`,
        }
    });
    
    return res;
}

export { createComment };