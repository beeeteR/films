import axios from "axios"

const token = '0c01d7b051c229c0a2452b14db6370e0'
const apikey = '822bdf15-9f70-436d-ba85-e86d0cce0038'
const delayCallFunc = 200
let countCallFunc = 0

async function repeatFunc(func, args) {
    if (countCallFunc >= 3) {
        alert('Что-то пошло не так перезагрузите страницу')
        return false
    }else {
        countCallFunc += 1
        return await func(...args).then(res => {
            if (res) {
                countCallFunc = 0
                return res
            }else {
                setTimeout(() => {
                    repeatFunc(func, args)
                }, delayCallFunc + delayCallFunc * countCallFunc);
            }
        })
    }
}

const API = {

    // BAZON

    async getByTitle(title) {
        return await axios.get(`https://bazon.cc/api/search?token=${token}&title=${title}`)
            .then(response => response.data)
            .catch(error => {
                if (countCallFunc < 3) {
                    let double = repeatFunc(this.getByTitle, [title])
                    if (double) {
                        return double.data
                    }
                } else {
                    console.log(error)
                }
            })
    },
    async getByKp(kp) {
        return await axios.get(`https://bazon.cc/api/search?token=${token}&kp=${kp}`)
            .then(response => response.data)
            .catch(error => {
                if (countCallFunc < 3) {
                    let double = repeatFunc(this.getByKp, [kp])
                    if (double) {
                        return double.data
                    }
                } else {
                    console.log(error)
                }
            })
    },
    async getAllPages(type = 'all', cat = '', resolution = '', year = '') {
        return await axios.get(`https://bazon.cc/api/pages?token=${token}&type=${type}&cat=${cat}&resolution=${resolution}&year=${year}`)
            .then(response => response.data)
            .catch(error => {
                if (countCallFunc < 3) {
                    let double = repeatFunc(this.getAllPages, [type, cat, resolution, year])
                    if (double) {
                        return double.data
                    }
                } else {
                    console.log(error)
                }
            })
    },
    async getAll(type = 'all', page = 1, cat = '', resolution = '', year = '') {
        return await axios.get(`https://bazon.cc/api/json?token=${token}&type=${type}&page=${page}&cat=${cat}&resolution=${resolution}&year=${year}`)
            .then(response => response.data)
            .catch(error => {
                if (countCallFunc < 3) {
                    let double = repeatFunc(this.getAll, [type, page, cat, resolution, year])
                    if (double) {
                        return double.data
                    }
                } else {
                    console.log(error)
                }
            })
    },
    async getAllTranslations() {
        return await axios.get(`https://bazon.cc/api/translations?token=${token}`)
            .then(response => response.data)
            .catch(error => {
                if (error.message == "Network Error") {
                    let double = repeatFunc(this.getAllTranslations, [])
                    if (double) {
                        return double.data
                    }
                } else {
                    console.log(error)
                }
            })
    },

    // KINOPOISKUNOFICIAL

    async getById(id) {
        return await axios.get(`https://kinopoiskapiunofficial.tech/api/v2.2/films/${id}`, {
            headers: {
                'X-API-KEY': apikey
            }
        })
            .then(response => response.data)
            .catch(error => console.log(error))
    },
    async getTopFilms(type, page) {
        if (type == 'popular') {
            type = 'TOP_100_POPULAR_FILMS'
        } else if (type == 'best') {
            type = 'TOP_250_BEST_FILMS'
        } else if (type == 'await') {
            type = 'TOP_AWAIT_FILMS'
        }

        return await axios.get(`https://kinopoiskapiunofficial.tech/api/v2.2/films/top?type=${type}&page=${page}`, {
            headers: {
                'X-API-KEY': apikey,
            }
        })
            .then(response => response.data)
            .catch(error => console.log(error))
    },

    // BACKEND

    async createUser(data) {
        return await axios.post('http://127.0.0.1:8000/api/createUser', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async getUser(id) {
        return await axios.get(`http://127.0.0.1:8000/api/getUser?id=${id}`)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async authUser(email, password) {
        return await axios.get(`http://127.0.0.1:8000/api/authUser?email=${email}&password=${password}`)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async getUserByToken(token) {
        return await axios.get(`http://127.0.0.1:8000/api/getUserByToken?token=${token}`)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async setWatchLater(userID, kpID, posterUrl, name, rating, type, year) {
        let data = new FormData()
        data.append('id_user', userID)
        data.append('id_kp', kpID)
        data.append('poster_url', posterUrl)
        data.append('name', name)
        data.append('rating', rating)
        data.append('type', type)
        data.append('year', year)

        return await axios.post('http://127.0.0.1:8000/api/setWatchLater', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async delWatchLater(userID, kpID) {
        let data = new FormData()
        data.append('id_user', userID)
        data.append('id_kp', kpID)

        return await axios.post('http://127.0.0.1:8000/api/delWatchLater', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async setComment(userID, kpID, date, text, isAnswer=0, answerToId=null) {
        let data = new FormData()
        data.append('id_user', userID)
        data.append('id_film', kpID)
        data.append('datetime', date)
        data.append('is_answer', isAnswer)
        data.append('answer_to_id', answerToId)
        data.append('text', text)

        return await axios.post('http://127.0.0.1:8000/api/setComment', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async delComment(commentId) {
        let data = new FormData()
        data.append('id', commentId)

        return await axios.post('http://127.0.0.1:8000/api/delComment', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async getComments(kpID) {
        return await axios.get(`http://127.0.0.1:8000/api/getComments?film_id=${kpID}`)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async editComment(idComment, text) {
        let data = new FormData()
        data.append('id', idComment)
        data.append('text', text)

        return await axios.post('http://127.0.0.1:8000/api/editComment', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async setPlaylist(userID, name) {
        let data = new FormData()
        data.append('user_id', userID)
        data.append('name', name)

        return await axios.post('http://127.0.0.1:8000/api/setPlaylist', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async delPlaylist(playlistID) {
        let data = new FormData()
        data.append('playlist_id', playlistID)

        return await axios.post('http://127.0.0.1:8000/api/delPlaylist', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async updateNamePlaylist(playlistID, name) {
        let data = new FormData()
        data.append('playlist_id', playlistID)
        data.append('name', name)

        return await axios.post('http://127.0.0.1:8000/api/updateNamePlaylist', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async setFilmInPlaylist(playlistID, kpID, posterUrl, name, rating, type, year, isSerial, season=null, episode=null, studio=null) {
        let data = new FormData()
        data.append('playlist_id', playlistID)
        data.append('id_kp', kpID)
        data.append('poster_url', posterUrl)
        data.append('name', name)
        data.append('rating', rating)
        data.append('type', type)
        data.append('year', year)
        data.append('is_serial', isSerial)
        data.append('season', season)
        data.append('episode', episode)
        data.append('studio', studio)

        return await axios.post('http://127.0.0.1:8000/api/setFilmInPlaylist', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async delFilmFromPlaylist(filmID) {
        let data = new FormData()
        data.append('film_id', filmID)

        return await axios.post('http://127.0.0.1:8000/api/delFilmFromPlaylist', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async updateFilmInPlaylist(filmID, season, episode) {
        let data = new FormData()
        data.append('film_id', filmID)
        data.append('season', season)
        data.append('episode', episode)

        return await axios.post('http://127.0.0.1:8000/api/updateFilmInPlaylist', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async moveFilmToAnotherPlaylist(filmID, playlistID) {
        let data = new FormData()
        data.append('film_id', filmID)
        data.append('playlist_id', playlistID)

        return await axios.post('http://127.0.0.1:8000/api/moveFilmToAnotherPlaylist', data)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
    async getUserPlaylists(userID) {
        return await axios.get(`http://127.0.0.1:8000/api/getUserPlaylists?user_id=${userID}`)
            .then(response => response.data)
            .catch(error => {
                alert('Ошибка бэк')
                console.log(error);
            })
    },
}


export default API 