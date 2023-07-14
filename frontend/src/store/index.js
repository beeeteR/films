import { defineStore } from "pinia";
import API from '../api/api'

export const useMainStore = defineStore('main', {
  state: () => ({
    user: false,
    watchLater: [],
    playlists: null,
    searchList: []
  }),

  getters: {
    removeDublicates() {
      return (arr) => {
        let acc = []
        let result = []
        arr.forEach(el => {
          if (!acc?.find(id => id === el?.kinopoisk_id)) {
            result.push(el)
            acc.push(el.kinopoisk_id)
          }
        })
        return [...result]
      }
    },
    getFilmsByYear() {
      return (year) => API.getAll('film', 1, '', '', year).then(res => this.removeDublicates(res?.results ? res.results : []))
    },
    getSerialsByYear() {
      return (year) => API.getAll('serial', 1, '', '', year).then(res => this.removeDublicates(res?.results ? res.results : []))
    },
    getById() {
      return (id) => API.getByKp(id)
    },
    getByTitle() {
      return (title) => API.getByTitle(title).then(res => this.removeDublicates(res?.results ? res.results : []))
    }
  },

  actions: {
    changeWatchLater(kp, posterUrl, name, rating, type, year) {
      let index = false
      this.watchLater.forEach((el, i) => {
        if (el.kp == kp) {
          index = i
        }
      })
      if (index != false) {
        this.watchLater.splice(index, 1)
        API.delWatchLater(this.user.id, kp)
      } else {
        this.watchLater.push({ 'kp': kp, 'posterUrl': posterUrl, 'name': name, 'rating': rating, 'type': type, 'year': year })
        API.setWatchLater(this.user.id, kp, posterUrl, name, rating, type, year)
      }
    }
  }
})