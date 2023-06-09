import { defineStore } from "pinia";
import API from '../api/api'

export const useMainStore = defineStore('main', {
  state: () => ({
    watchLater: [],
    watching: [{ 'id': 143242, 'season': 1, 'episode': 1 }]
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
      return (year) => API.getAll('film', 1, '', '', year).then(res => this.removeDublicates(res.results))
    },
    getSerialsByYear() {
      return (year) => API.getAll('serial', 1, '', '', year).then(res => this.removeDublicates(res.results))
    },
    getById() {
      return (id) => API.getByKp(id)
    },
    getByTitle() {
      return (title) => API.getByTitle(title).then(res =>  this.removeDublicates(res.results))
    }
  },

  actions: {
    addToWatchLater(id) {
      if (!this.watchLater.includes(id))
        this.watchLater.push(id)
    }
  }
})