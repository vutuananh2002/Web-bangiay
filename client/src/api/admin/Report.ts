import type { AccessToken, ReportDataResponse } from '@/types/Type';
import Api from '../Api';

const url = '/reports';

const getReports = async (token: AccessToken) => {
    const res: ReportDataResponse = await Api.get(url, {
        headers: {
            'Authorization': `${token.token_type} ${token.token}`
        }
    });

    return res;
}

export { getReports };