import axios from "axios";

const url = 'https://provinces.open-api.vn/api';

const requests = axios.create({
    baseURL: url,
    headers: {
        'Content-Type': 'application/json',
    }
});

requests.interceptors.response.use((res) => res.data, (err) => Promise.reject(err.response.data));

export default requests;