<template>
    <div class="wrapper" @click="closeAllDrops($event)" ref="navigator">
        <h1 class="title">Навигатор</h1>
        <div class="filters">
            <filter-component :items="['Фильм', 'Сериал', 'Мультфильм', 'Аниме']" :title="'Тип'" :type="'type'"
                :opened="stateAllFilterDrop" @changeFilterValue="changeFilter($event)"></filter-component>
            <filter-component :items="years" :title="'Год'" :type="'year'" :opened="stateAllFilterDrop"
                @changeFilterValue="changeFilter($event)"></filter-component>
            <filter-component :items="genres" :title="'Жанр'" :type="'genre'" :opened="stateAllFilterDrop"
                @changeFilterValue="changeFilter($event)"></filter-component>
        </div>
        <div class="filter__results" v-if="films.length">
            <film-component v-for="film in filmsWithoutDublicates" :key="film.kinopoisk_id" :film="film"></film-component>
        </div>
        <loading-component :height="200"></loading-component>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import FilterComponent from "@/components/FilterComponent.vue";
import FilmComponent from "@/components/FilmComponent.vue";
import LoadingComponent from "@/components/LoadingComponent.vue";
import API from "../api/api";
import { useMainStore } from "../store";

export default defineComponent({
    components: {
        FilterComponent,
        FilmComponent,
        LoadingComponent
    },
    data() {
        return {
            store: useMainStore(),
            stateAllFilterDrop: null,
            filters: {
                'type': null,
                'year': null,
                'genre': null
            },
            years: [],
            genres: [
                'вестерн',
                'фантастика',
                'ужасы',
                'боевик',
                'комедия',
                'фэнтези',
                'драма',
                'детектив',
                'спорт',
                'триллер',
                'приключения'
            ],
            films: [],
            currentPage: 0,
            totalPages: 0,
            loading: true
        }
    },
    mounted() {
        for (let i = 2023; i > 1990; i--) {
            this.years.push(i)
        }
        this.getCorrectData()

        document.addEventListener('scroll', this.throttle(this.trackPos, 250))
    },
    methods: {
        closeAllDrops(event) {
            this.$refs.navigator.querySelectorAll('.filter').forEach(el => {
                if (event.target != el) {
                    this.stateAllFilterDrop = false
                    return
                } else {
                    this.stateAllFilterDrop = null
                }
            })
        },
        changeFilter(newFilter) {
            this.filters[newFilter.type] = newFilter.value
        },
        getCorrectData(fromTrack = false) {
            let type
            let cat
            let year

            switch (this.filters.type) {
                case 'Фильм':
                    type = 'film'
                    break;
                case 'Сериал':
                    type = 'serial'
                    break;
                case 'Мультфильм':
                    type = 'all'
                    cat = 'мультфильм'
                    break;
                case 'Аниме':
                    type = 'all'
                    cat = 'аниме'
                    break;
                default:
                    type = 'all'
                    break;
            }

            cat = cat ? this.filters.genre ? [cat, this.filters.genre].join(' ') : cat : ''
            year = this.filters.year ? String(this.filters.year) : ''
            if (fromTrack) {
                this.getFilmsByPage(type, cat, year)
            } else {
                this.getPages(type, cat, year)
            }
        },
        getPages(type, cat, year) {
            this.totalPages = 0
            this.currentPage = 0
            this.films = []

            API.getAllPages(type, cat, '', year).then(res => this.totalPages = res)
            setTimeout(() => {
                this.getFilmsByPage(type, cat, year)
            }, 200)
        },
        getFilmsByPage(type, cat, year) {
            if (this.currentPage == this.totalPages && this.totalPages != 0) {
                return
            }
            this.currentPage += 1
            API.getAll(type, this.currentPage, cat, '', year).then(res => this.films.push(...res.results))
            this.loading = false
        },
        trackPos() {
            let height = document.body.offsetHeight
            let screenHeight = window.innerHeight
            let scrolled = window.scrollY
            let threshold = height - screenHeight / 4
            let position = scrolled + screenHeight
            if (position >= threshold) {
                this.loading = true
                setTimeout(() => {
                    this.getCorrectData(true)
                }, 200);
            }
        },
        throttle(callee, timeout) {
            let timer = null
            return function perform(...args) {
                if (timer) return

                timer = setTimeout(() => {
                    callee(...args)

                    clearTimeout(timer)
                    timer = null
                }, timeout)
            }
        }
    },
    watch: {
        filters: {
            handler() {
                this.getCorrectData()
            },
            deep: true
        }
    },
    computed: {
        filmsWithoutDublicates() {
            return this.store.removeDublicates(this.films)
        }
    }
})
</script>

<style lang="scss" scoped>
.wrapper {
    margin-top: 70px;

    .title {
        text-align: center;
        padding: 2rem;
        margin: 2rem 0;
        border-radius: 0.5rem;
        border: 1px solid #242424;
    }

    .filters {
        position: relative;
        z-index: 10;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .filter__results {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-top: 2rem;
    }

    .target-for-get-films {
        width: 100%;
        padding: 2rem 0;
    }
}
</style>