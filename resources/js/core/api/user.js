import axios from "../config/axios";

export default {
    register: (data) => {

    },

    login: (data) => {
        // csrf();
        return axios.post('/login', data)
    },

    me: (user) => {
        axios.defaults.headers.common['Authorization'] = `Bearer ${user.token}`;
        return axios.get('/api/user')
    },

}

async function csrf() {
    await axios.get('/airlock/csrf-cookie')
}
