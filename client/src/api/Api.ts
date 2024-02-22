import axios from 'axios';

const API_PATH = import.meta.env.VITE_API_PATH;

const requests = axios.create({
    baseURL: `${API_PATH}`,
    headers: {
        'Content-Type': 'application/json',
    },
    withCredentials: true,
});

requests.interceptors.response.use((res) => res.data, (err) => Promise.reject(err.response));

export default requests;