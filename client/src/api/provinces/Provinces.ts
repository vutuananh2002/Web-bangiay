import type { DistrictData, ProvinceData } from '@/types/Type';
import Api from './Api';

const getProvinces = async () => {
    const res: ProvinceData[] = await Api.get('/p');

    return res;
}

const getDistricts = async (provinceCode: number) => {
    const res: ProvinceData = await Api.get(`/p/${provinceCode}`, {
        params: {
            depth: 2,
        }
    });

    return res.districts;
}

const getWards = async (districtCode: number) => {
    const res: DistrictData = await Api.get(`/d/${districtCode}`, {
        params: {
            depth: 2,
        }
    });

    return res.wards;
}

export { getProvinces, getDistricts, getWards };