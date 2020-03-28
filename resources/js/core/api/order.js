import axios from '../config/axios';

const apiOrder = {
    save: data => {
        return axios.post('/api/orders', data);
    },

    get: (params = {}) => {
        return axios.get('/api/orders', {params});
    },

    show: (id) => {
        return axios.get(`/api/orders/${id}`);
    },

    setStatus: (id, status) => {
        return axios.put(`/api/orders/${id}`, {status: status})
    },

    delete: id => {
        return axios.delete(`/api/orders/${id}`);
    },

    getStatistic: (params, type) => {
        return axios.get('/api/stats', {params: {...params, type}})
    }
}

export default apiOrder;