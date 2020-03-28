import axios from '../config/axios';

const apiClient = {
    save: (data) => {
        if (null !== data.id) {
            return axios.put(`/api/clients/${data.id}`, data);
        }


        return axios.post('/api/clients', data);
    },

    get: (params = null) => {
        return axios.get('/api/clients', params)
    },

    getList: (params = null) => {
        return axios.get('/api/clients/list', params)
    },

    show: (id) => {
        return axios.get(`/api/clients/${id}`);
    },

    delete: id => axios.delete(`/api/clients/${id}`)
}

export default apiClient;