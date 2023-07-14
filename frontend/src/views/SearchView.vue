<template>
    <div class="wrapper">
        <h1 class="title" v-if="!loading && store.searchList.length">Результаты поиска</h1>
        <loading-component v-if="loading"></loading-component>
        <div class="search__list" v-else-if="!loading && store.searchList.length">
            <film-component v-for="film in store.searchList" :key="film.kinopoisk_id" :film="film"></film-component>
        </div>
        <h1 class="title" v-else>Ничего не найдено</h1>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { useMainStore } from "../store";
import FilmComponent from "@/components/FilmComponent.vue";
import LoadingComponent from "@/components/LoadingComponent.vue";
import API from "../api/api";

export default defineComponent({
    components: {
        FilmComponent,
        LoadingComponent
    },
    data() {
        return {
            store: useMainStore(),
            loading: true
        }
    },
    mounted() {
        if (!this.$route.params.title) {
            this.$router.push({'path': '/'})
        }
        this.updateFilmsList()
    },
    methods: {
        updateFilmsList() {
            API.getByTitle(this.$route.params.title).then(res => {
                if (res.results) {
                    this.store.searchList = this.store.removeDublicates(res.results)
                }
                this.loading = false
            })
        }
    },
    watch: {
        $route(newVal) {
            if (newVal.name == 'searchedFilms') {
                this.loading = true
                this.updateFilmsList()
            }
        }
    }
})
</script>

<style scoped lang="scss">
.wrapper {
    margin-top: 70px;
}

.search__list {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
}
</style>