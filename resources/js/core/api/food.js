import axios from '../config/axios';

const apiFood = {
    save: (data) => {
        const formData = new FormData();
        formData.append("name", data.name);
        formData.append("price", data.price);

        if (data.image !== null) {
            const extension = data.image.uri.split(".").pop();
            formData.append("img", {
                uri: data.image.uri,
                name: `image`,
                type: `image/${extension}`,
            });
        }

        if (null !== data.id) {
            formData.append('_method', 'PATCH')
            return axios.post(`/api/foods/${data.id}`, formData);
        }


        return axios.post('/api/foods', formData);
    },

    get: (params = null) => {
        return axios.get('/api/foods', params)
    },

    getList: (params = null) => {
        return axios.get('/api/foods/list', params);
    },

    show: (id) => {
        return axios.get(`/api/foods/${id}`);
    },

    delete: id => axios.delete(`/api/foods/${id}`)
}

export default apiFood;