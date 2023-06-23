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
                        <my-search></my-search>
                    </div>
                    <div class="nav__item">
                        <font-awesome-icon :icon="['far', 'bell']" size="lg" style="cursor: pointer;" />
                    </div>
                    <div class="nav__item">
                        <router-link :to="auth ? '/profile' : 'auth'">
                            <p class="nav__item-text">
                                {{ auth ? auth.name : 'Войти' }}
                            </p>
                            <font-awesome-icon :icon="[auth ? 'far' : 'fas', auth ? 'user' : 'right-to-bracket']"
                                size="lg" />
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
import MySearch from "./MySearch.vue"

export default defineComponent({
    components: {
        MySearch
    },
    data() {
        return {
            store: useMainStore(),
            stateSideBar: true,
        }
    },
    methods: {
        choiseStateSideBar() {
            this.stateSideBar = !this.stateSideBar
            this.$emit('choiseStateSideBar', this.stateSideBar)
        }
    },
    computed: {
        auth() {
            return this.store.user ? this.store.user : false
        }
    }
})
</script>

<style lang="scss">
header {
    background-color: #121214;
    position: fixed;
    width: 100%;
    z-index: 100;
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

            &:last-child a {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
        }
    }
}
</style>