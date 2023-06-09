<template>
    <header>
        <div class="wrapper">
            <nav class="navbar">
                <font-awesome-icon :icon="['fas', 'bars']" size="xl" class="bars" @click="choiseStateSideBar()" />
                <div class="logo">
                    <router-link to="/">
                        <img src="../img/icons8-logo-50.png" alt="logo">
                    </router-link>
                </div>
                <div class="nav__items">
                    <div class="nav__item">
                        <div class="search" :class="{ active: searched }">
                            <input type="text" class="inp-search" v-model="searchText" ref="inpSearch">
                            <div class="searched__films">
                                <router-link class="searched__film" v-for="film in searchedFilms" :key="film.date"
                                    :to="`/film/${film.kinopoisk_id}`" @click="searchToInit()">
                                    {{ film.info.rus }} {{ film.info.year }} {{ film.serial == '1' ? 'Сериал' : '' }}
                                </router-link>
                            </div>
                        </div>
                        <font-awesome-icon :icon="['fas', 'magnifying-glass']" size="lg" ref="search" @click="searchInpOpen"
                            style="cursor: pointer;" />
                    </div>
                    <div class="nav__item">
                        <font-awesome-icon :icon="['far', 'bell']" size="lg" style="cursor: pointer;" />
                    </div>
                    <div class="nav__item">
                        <router-link to="/">
                            <p class="nav__item-text">
                                Профиль
                            </p>
                        </router-link>
                    </div>
                </div>
            </nav>
        </div>
    </header>
</template>
<script>
import { useMainStore } from "@/store"
import { defineComponent } from "vue"

export default defineComponent({
    data() {
        return {
            store: useMainStore(),
            searched: false,
            searchText: '',
            stateSideBar: true,
            searchedFilms: []
        }
    },
    methods: {
        searchInpOpen() {
            this.searched = this.searched ? false : true
            this.$refs.inpSearch.focus()
        },
        choiseStateSideBar() {
            this.stateSideBar = !this.stateSideBar
            this.$emit('choiseStateSideBar', this.stateSideBar)
        },
        searchToInit() {
            this.searched = false
            this.searchText = ''
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
header {
    background-color: #121214;
    position: fixed;
    width: 100%;
    z-index: 5;
}

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

.navbar {
    height: 70px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;

    .bars {
        padding: 0.75rem;
        border-radius: 50%;
        cursor: pointer;
        transition: all 300ms;

        &:hover {
            transform: scale(110%);
            background-color: rgba(255, 255, 255, 0.05);
        }

        &:active {
            background-color: rgba(255, 255, 255, 0.15);
        }
    }

    .logo {
        transition: all 400ms;
        border-radius: 50%;
        padding: 0.5rem;
        overflow: hidden;

        &:hover {
            box-shadow: 0px 0px 10px 5px #2B2D31;
            transform: scale(110%);
        }
    }

    .nav__items {
        display: flex;
        align-items: center;
        gap: 1rem;

        .nav__item {
            .nav__item-text {
                transition: all 300ms;

                &:hover {
                    color: #808489;
                    text-decoration: underline;
                }
            }

            &:nth-child(1) {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
        }
    }

    .search {
        position: relative;
        transition: all 300ms;
        transform: scaleX(0);
        transform-origin: right;

        .searched__films {
            position: absolute;
            z-index: 10;
            top: 37px;
            left: 0;
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

}
</style>