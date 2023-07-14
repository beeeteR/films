<template>
    <div class="search" :class="{ active: searched }">
        <input type="text" class="inp-search" v-model="searchText" ref="inpSearch" @keydown.enter="goToSearchView()">
        <div class="searched__films">
            <router-link class="searched__film" v-for="film in searchedFilms" :key="film.date"
                :to="`/film/${film.kinopoisk_id}`" @click="searchToInit()">
                {{ film.info.rus }} {{ film.info.year }} {{ film.serial == '1' ? 'Сериал' : '' }}
            </router-link>
        </div>
    </div>
    <font-awesome-icon :icon="['fas', 'magnifying-glass']" size="lg" ref="search" @click="searchInpOpen"
        style="cursor: pointer;" />
</template>

<script>
import { defineComponent } from "vue";
import { useMainStore } from "../store";

export default defineComponent({
    props: {

    },
    data() {
        return {
            store: useMainStore(),
            searchedFilms: [],
            searched: false,
            searchText: ''
        }
    },
    methods: {
        searchToInit() {
            this.searched = false
            this.searchedFilms = []
            this.searchText = ''
        },
        searchInpOpen() {
            this.searched = this.searched ? false : true
            this.$refs.inpSearch.focus()
        },
        goToSearchView() {
            this.$router.push({'path': `/searchedFilms/${this.searchText}`})
            this.searchToInit()
        }
    },
    watch: {
        searchText() {
            if (this.searchText.length > 2) {
                setTimeout(() => {
                    this.store.getByTitle(this.searchText).then(res => this.searchedFilms = res)
                }, 400);

            } else {
                this.searchedFilms = []
            }
        }
    }
})
</script>

<style lang="scss">
.inp-search {
    border: none;
    outline: none;
    background-color: rgba(255, 255, 255, 0.05);
    padding: 0.5rem 0.8rem;
    font-size: 1.1rem;
}

.active {
    transform: scale(100%) !important;
}

.search {
    position: relative;
    transition: all 300ms;
    transform: scaleX(0);
    transform-origin: center;

    .searched__films {
        position: absolute;
        z-index: 10;
        top: 37px;
        left: 0;
        max-height: 300px;
        width: 100%;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        border-radius: 0.5rem;

        .searched__film {
            padding: 0.5rem 1rem;
            background-color: #121214;
            border-bottom: 1px solid white;
            transition: all 300ms;

            &:nth-child(1) {
                border-top: 1px solid white;
            }

            &:hover {
                background-color: #28282c;
            }
        }
    }
}
</style>