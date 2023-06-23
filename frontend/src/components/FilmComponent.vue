<template>
    <loading-component v-if="!bgImg"></loading-component>
    <div class="film__wrapper" v-else>
        <div class="film__content">
            <router-link :to="`/film/${isHome ? film.kinopoisk_id : isPlaylist ? film.id_kp : film.kp}`">
                <div class="poster__wrapper">
                    <div class="poster">
                        <h4 class="rating">
                            {{ Number(rating).toFixed(2) }}
                        </h4>
                        <h3 class="film__name">
                            {{ isHome ? film.info.rus : film.name }}
                        </h3>
                    </div>
                </div>
            </router-link>
            <div class="film__actions" v-if="!isPlaylist">
                <div class="film__action" title="Смотреть позже" :class="{ 'film__action-active': inStoreWatchLater }"
                    @click="store.changeWatchLater(Number(isHome ? film.kinopoisk_id : isPlaylist ? film.id_kp : film.kp), bgImg, isHome ? film.info.rus : film.name, rating)">
                    <font-awesome-icon :icon="['fas', 'stopwatch']" size="lg" />
                </div>
                <div class="film__action" title="Поделиться">
                    <font-awesome-icon :icon="['fas', 'share-nodes']" size="lg" />
                </div>
                <div></div>
                <div class="film__action" title="Добавить в плейлист" @click="stateModal = true">
                    <font-awesome-icon :icon="['far', 'eye']" size="lg" />
                </div>
            </div>
            <div class="film__actions" v-if="isPlaylist">
                <div class="film__action" title="Переместить в другой плейлист" @click="stateModal = true">
                    <font-awesome-icon :icon="['far', 'eye']" size="lg" />
                </div>
                <div class="film__action" title="Поделиться">
                    <font-awesome-icon :icon="['fas', 'share-nodes']" size="lg" />
                </div>
                <div></div>
                <div class="film__action" title="Удалить из плейлиста" @click="removeFromPlaylist()">
                    <font-awesome-icon :icon="['fas', 'trash']" size="lg" />
                </div>
            </div>
        </div>
        <modal-add-to-playlist :opened="stateModal" :film="film"
            @closeModalAdd="stateModal = false"></modal-add-to-playlist>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { useMainStore } from '@/store'
import LoadingComponent from '@/components/LoadingComponent.vue'
import ModalAddToPlaylist from "./ModalAddToPlaylist.vue";
import API from "../api/api";

export default defineComponent({
    components: {
        LoadingComponent,
        ModalAddToPlaylist
    },
    props: {
        film: {
            type: Object,
            required: true
        },
        isHome: {
            type: Boolean,
            default: true
        },
        isPlaylist: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            store: useMainStore(),
            bgImg: '',
            rating: 0,
            stateModal: false
        }
    },
    mounted() {
        if (this.isHome) {
            this.bgImg = `url(${this.film.info.poster})`
            this.rating = this.film.info.rating.rating_kp != 0 ? this.film.info.rating.rating_kp : this.film.info.rating.rating_imdb
                != 0 ? this.film.info.rating.rating_imdb : 5.1
        } else if (this.isPlaylist) {
            this.rating = this.film.rating
            this.bgImg = this.film.poster_url
        } else {
            this.rating = this.film.rating
            this.bgImg = this.film.posterUrl
        }
    },
    methods: {
        removeFromPlaylist() {
            API.delFilmFromPlaylist(this.film.id)
            API.getUserPlaylists(this.store.user.id).then(res => this.store.playlists = res)
        }
    },
    computed: {
        inStoreWatchLater() {
            if (this.isHome || this.isPlaylist) {
                let value = false
                this.store.watchLater.forEach(el => {
                    if (el.kp == this.film.kinopoisk_id) {
                        value = true
                    }
                })
                return value
            } else {
                return true
            }
        }
    }
})
</script>

<style lang="scss">
.film__wrapper {
    position: relative;
    user-select: none;
    cursor: pointer;

    .film__content {
        border-radius: 0.5rem;
        overflow: hidden;

        .poster__wrapper {
            height: 320px;

            .poster {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                padding: 0 1rem;
                background-image: v-bind('bgImg');
                background-position: center;
                background-repeat: no-repeat;
                background-size: 100%;
                transition: all 400ms;

                &::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.6);
                }

                .film__name,
                .rating {
                    z-index: 1;
                }

                .film__name {
                    text-align: center;
                }

                .rating {
                    position: absolute;
                    top: 1rem;
                    left: 1rem;
                }
            }
        }

        .film__actions {
            position: absolute;
            top: 0;
            right: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            padding: 1rem;
            transform: scale(0);
            transform-origin: 1rem 1rem;
            text-align: center;
            z-index: 2;
            transition: all 300ms;

            .film__action {
                padding: 0.7rem;
                background-color: cadetblue;
                border-radius: 50%;
                transition: all 400ms;

                &:hover {
                    background-color: darkcyan;
                }

                &-active {
                    background-color: rgb(0, 75, 75);
                }
            }
        }
    }

    &:hover {
        .poster {
            background-size: 120% !important;
        }

        .film__actions {
            transform: scale(100%);
        }
    }
}
</style>