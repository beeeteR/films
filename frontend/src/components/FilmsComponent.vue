<template>
    <div class="film__wrapper">
        <div class="film__content">
            <router-link :to="`/film/${film.kinopoisk_id}`">
                <div class="poster__wrapper">
                    <div class="poster">
                        <h4 class="rating">
                            {{ film.info.rating.rating_kp != 0 ? film.info.rating.rating_kp : film.info.rating.rating_imdb
                                != 0 ? film.info.rating.rating_imdb : 5.1 }}
                        </h4>
                        <h3 class="film__name">
                            {{ film.info.rus }}
                        </h3>
                    </div>
                </div>
            </router-link>
            <div class="film__actions" ref="actions">
                <div class="film__action">
                    <font-awesome-icon :icon="['fas', 'stopwatch']" size="lg"
                        @click="store.addToWatchLater(film.kinopoisk_id)" ref="watchLater"/>
                </div>
                <div class="film__action">
                    <font-awesome-icon :icon="['fas', 'share-nodes']" size="lg" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { useMainStore } from '@/store'

export default defineComponent({
    props: {
        film: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            store: useMainStore(),
            bgImg: `url(${this.film.info.poster})`
        }
    },
    methods: {

    }
})
</script>

<style lang="scss">
.film__wrapper {
    position: relative;
    user-select: none;
    cursor: pointer;

    .film__content {

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
            left: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            padding: 1rem;
            transform: scale(0);
            transform-origin: 1rem 1rem;
            z-index: 2;
            transition: all 300ms;

            .film__action {
                padding: 0.7rem;
                background-color: cadetblue;
                border-radius: 50%;
                transition: all 400ms;

                &-active, &:hover {
                    background-color: darkcyan;

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