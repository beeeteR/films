<template>
    <div class="compilation__wrapper">
        <div class="arrow__wrapper" @click="listToPrevFilms()">
            <font-awesome-icon :icon="['fas', 'chevron-left']" size="2xl" class="arrow" />
        </div>
        <div class="compilation__content">
            <film-component v-for="film in currentFilms" :key="film.date" :film="film"></film-component>
        </div>
        <div class="arrow__wrapper" @click="listToNextFilms()">
            <font-awesome-icon :icon="['fas', 'chevron-right']" size="2xl" class="arrow" />
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import FilmComponent from "./FilmComponent.vue";

export default defineComponent({
    components: {
        FilmComponent
    },
    props: {
        films: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            currentFilm: 0,
            step: 5,
            countFilms: 0
        }
    },
    mounted(){
        this.countFilms = this.films.length
    },
    methods: {
        listToNextFilms() {
            if (this.currentFilm + this.step > this.countFilms) {
                this.currentFilm = this.countFilms
            } else {
                this.currentFilm += this.step
            }
        },
        listToPrevFilms() {
            if (this.currentFilm - this.step < 0) {
                this.currentFilm = 0
            } else {
                this.currentFilm -= this.step
            }
        }
    },
    computed: {
        currentFilms() {
            return this.films.slice(this.currentFilm, this.currentFilm + this.step)
        }
    }
})
</script>

<style lang="scss">
.compilation__wrapper {
    display: flex;
    align-items: center;
    height: 320px;

    .compilation__content {
        display: grid;
        grid-template-columns: repeat(v-bind('step'), 1fr);
        grid-template-rows: repeat(1, 1fr);
        column-gap: 1rem;
        align-items: center;
        width: 100%;
        overflow: hidden;
    }

    .arrow__wrapper {
        cursor: pointer;
        height: 100%;
        display: flex;
        align-items: center;

        &:nth-child(1) {
            padding-right: 1rem;
        }

        &:nth-child(3) {
            padding-left: 1rem;
        }

        &:hover {
            .arrow {
                transform: scale(125%);
            }
        }

        .arrow {
            transition: all 300ms;
        }
    }
}
</style>