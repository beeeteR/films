<template>
    <div class="wrapper" @click="closeAllDrops($event)" ref="navigator">
        <h1 class="title">Навигатор</h1>
        <div class="filters">
            <filter-component :items="['Фильм', 'Сериал', 'Мультфильм', 'Аниме']" :title="'Тип'" :type="'type'"
                :opened="stateFiltersDrop.type" @changeFilterValue="changeFilter($event)" @openModal="changeStateFilters($event)"></filter-component>
                
            <filter-component :items="years" :title="'Год'" :type="'year'" :opened="stateFiltersDrop.years"
                @changeFilterValue="changeFilter($event)" @openModal="changeStateFilters($event)"></filter-component>
            
            <filter-component :items="genres" :title="'Жанр'" :type="'genre'" :opened="stateFiltersDrop.genres"
                @changeFilterValue="changeFilter($event)" @openModal="changeStateFilters($event)"></filter-component>
        </div>
        <div class="filter__results" v-if="films.length && !notFound">
            <film-component v-for="film in filmsWithoutDublicates" :key="film.kinopoisk_id" :film="film"></film-component>
        </div>
        <loading-component :height="200" v-if="!notFound"></loading-component>
        <h1 class="title" v-if="notFound">Ничего не найдено</h1>
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
            stateFiltersDrop: {
                'type': false, 
                'years': false, 
                'genres': false
            },
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
            loading: true,
            notFound: false
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
        closeAllDrops() {
            // this.$refs.navigator.querySelectorAll('.filter').forEach(el => {
            //     console.log('Клик:', event.target, 'Фильтр:', el);
            //     if (event.target != el) {
            //         console.log('не равно');
            //         for (let key in this.stateFiltersDrop) {
            //             // console.log(key, this.stateFiltersDrop[key]);
            //             this.stateFiltersDrop[key] = false
            //         }
            //         return
            //     }
            // })
        },
        changeStateFilters(type) {
            for (let key in this.stateFiltersDrop) {
                if (type == key) {
                    this.stateFiltersDrop[key] = true
                }else {
                    this.stateFiltersDrop[key] = false
                }
            }
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

            API.getAllPages(type, cat, '', year).then(res => {
                this.totalPages = res
                this.getFilmsByPage(type, cat, year)
            })
        },
        getFilmsByPage(type, cat, year) {
            if (this.currentPage == this.totalPages && this.totalPages != 0) {
                return
            }
            this.currentPage += 1
            API.getAll(type, this.currentPage, cat, '', year).then(res => {
                if (res.error) {
                    this.notFound = true
                }else {
                    this.notFound = false
                    this.films.push(...res.results)
                }
            })
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