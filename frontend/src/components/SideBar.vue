<template>
    <div class="side__wrapper">
        <div class="side__content">
            <ul class="side__group">
                <li class="side__item" :class="{ 'side__item-active': link == 'home' }">
                    <router-link to="/" class="side__item-content" :class="{ 'side__item-content--hidden': !state }">
                        <div class="side__item-icon">
                            <font-awesome-icon :icon="['fas', 'house']" size="lg" />
                        </div>
                        <p class="side__item-text" v-show="state">Главная</p>
                    </router-link>
                </li>
                <li class="side__item" :class="{ 'side__item-active': link == 'trands' }">
                    <router-link to="/trands" class="side__item-content" :class="{ 'side__item-content--hidden': !state }">
                        <div class="side__item-icon">
                            <font-awesome-icon :icon="['fas', 'fire']" size="lg" />
                        </div>
                        <p class="side__item-text" v-show="state">В тренде</p>
                    </router-link>
                </li>
                <li class="side__item" :class="{ 'side__item-active': link == 'filters' }">
                    <router-link to="/filters" class="side__item-content" :class="{ 'side__item-content--hidden': !state }">
                        <div class="side__item-icon">
                            <font-awesome-icon :icon="['far', 'compass']" size="lg" />
                        </div>
                        <p class="side__item-text" v-show="state">Навигатор</p>
                    </router-link>
                </li>
            </ul>
            <span class="side__spacer"></span>
            <ul class="side__group">
                <li class="side__item" :class="{ 'side__item-active': link == 'playlists' }">
                    <router-link to="/playlists" class="side__item-content" :class="{ 'side__item-content--hidden': !state }">
                        <div class="side__item-icon">
                            <font-awesome-icon :icon="['far', 'eye']" size="lg" />
                        </div>
                        <p class="side__item-text" v-show="state">Плейлисты</p>
                    </router-link>
                </li>
                <li class="side__item" :class="{ 'side__item-active': link == 'later' }">
                    <router-link to="/later" class="side__item-content" :class="{ 'side__item-content--hidden': !state }">
                        <div class="side__item-icon">
                            <font-awesome-icon :icon="['fas', 'stopwatch']" size="lg" />
                        </div>
                        <p class="side__item-text" v-show="state">Смотреть позже</p>
                    </router-link>
                </li>
            </ul>
            <span class="side__spacer"></span>
            <ul class="side__group">
                <li class="side__item">
                    <router-link to="/" class="side__item-content" :class="{ 'side__item-content--hidden': !state }">
                        <div class="side__item-icon">
                            <font-awesome-icon :icon="['fab', 'telegram']" size="lg" />
                        </div>
                        <p class="side__item-text" v-show="state">Телеграмм</p>
                    </router-link>
                </li>
                <li class="side__item">
                    <router-link to="/" class="side__item-content" :class="{ 'side__item-content--hidden': !state }">
                        <div class="side__item-icon">
                            <font-awesome-icon :icon="['fab', 'vk']" size="lg" />
                        </div>
                        <p class="side__item-text" v-show="state">Вк</p>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        state: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        link() {
            return this.$route.name
        }
    }
})
</script>

<style lang="scss">
.side__wrapper {
    user-select: none;
    height: 100vh;
    width: fit-content;
    position: fixed;
    top: 0;
    left: 0;
    padding: 70px 1.5rem 0;
    background-color: #121214;

    .side__content {
        display: flex;
        flex-direction: column;
        gap: 1rem;

        .side__spacer {
            height: 1px;
            width: 100%;
            background-color: white;
        }

        .side__group {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;

            .side__item {

                .side__item-content--hidden {
                    justify-content: center;
                }

                .side__item-content {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 0.8rem;
                    border-radius: 0.8rem;
                    transition: all 200ms;

                    &:hover {
                        background-color: rgba(255, 255, 255, 0.05);
                    }

                    &:active {
                        background-color: rgba(255, 255, 255, 0.15);
                    }
                }
            }
        }

        .side__item-active {
            .side__item-content {
                transform: scale(105%);
                background-color: rgba(255, 255, 255, 0.15);

                &:hover {
                    background-color: rgba(255, 255, 255, 0.2) !important;
                }
            }
        }
    }
}
</style>