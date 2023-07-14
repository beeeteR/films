<template>
    <div class="wrapper">
        <h1 class="title">Вы решили отложить просмотр</h1>
        <div class="page__films" v-if="filmsFromStore.length">
            <film-component v-for="film in filmsFromStore" :key="film.kp" :film="film" :isHome="false"></film-component>
        </div>
        <loading-component v-else></loading-component>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { useMainStore } from "@/store";
import FilmComponent from "../components/FilmComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";

export default defineComponent({
    components: {
        FilmComponent,
        LoadingComponent
    },
    data() {
        return {
            store: useMainStore(),
        }
    },
    mounted() {
    },
    methods: {

    },
    computed: {
        filmsFromStore() {
            return this.store.watchLater
        }
    }
})
</script>

<style lang="scss" scoped>
.wrapper {
    margin-top: 70px;
    .page__films {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
    }
}
</style>