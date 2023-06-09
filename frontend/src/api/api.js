import axios from "axios"

const token = '0c01d7b051c229c0a2452b14db6370e0'

const API = {
    async getByTitle(title) {
        return await axios.get(`https://bazon.cc/api/search?token=${token}&title=${title}`)
            .then(response => response.data)
            .catch(error => console.log(error))
    },
    async getByKp(kp) {
        return await axios.get(`https://bazon.cc/api/search?token=${token}&kp=${kp}`)
            .then(response => response.data)
            .catch(error => console.log(error))
    },
    async getAllPages(type='all', cat='', resolution='', year='') {
        return await axios.get(`https://bazon.cc/api/pages?token=${token}&type=${type}&cat=${cat}&resolution=${resolution}&year=${year}`)
            .then(response => response.data)
            .catch(error => console.log(error))
    },
    async getAll(type='all', page=1, cat='', resolution='', year='') {
        return await axios.get(`https://bazon.cc/api/json?token=${token}&type=${type}&page=${page}&cat=${cat}&resolution=${resolution}&year=${year}`)
            .then(response => response.data)
            .catch(error => console.log(error))
    },
    async getAllTranslations() {
        return await axios.get(`https://bazon.cc/api/translations?token=${token}`)
            .then(response => response.data)
            .catch(error => console.log(error))
    }
}

export default API 