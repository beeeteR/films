<template>
    <header>
        <div class="wrapper">
            <nav class="navbar">
                <div class="social" @mouseover="openDrop()" @mouseout="closeDrop()">
                    <p class="social__dropdown">Подписаться на новости</p>
                    <div class="social__links" ref="droplist">
                        <div class="social__link">
                            <a href="vk.com">
                                <img src="../img/icons8-vk.svg" alt="vk">
                                <p>ВКонтакте</p>
                            </a>
                        </div>
                        <div class="social__link">
                            <a href="twitter.com">
                                <img src="../img/icons8-twitter.svg" alt="twitter">
                                <p>Твиттер</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="logo">
                    <router-link to="/">
                        <img src="../img/icons8-logo-50.png" alt="logo">
                    </router-link>
                </div>
                <div class="nav__items">
                    <div class="nav__item">
                        <router-link to="/">
                            <p class="nav__item-text">
                                Досмотреть
                            </p>
                        </router-link>
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
export default {
    methods: {
        openDrop() {
            this.$refs.droplist.classList.add('drop-active')
        },
        closeDrop() {
            this.$refs.droplist.classList.remove('drop-active')
        }
    }
}
</script>
<style lang="scss" scoped>
header {
    background-color: #121214;
}

.navbar {
    height: 70px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;

    .social {
        position: relative;
        padding: 1rem;

        .social__dropdown {
            cursor: pointer;
            transition: all 300ms;
            position: relative;
            background-color: #121214;
            padding: 1.6rem 0;
            z-index: 2;
        }

        .social__links {
            position: absolute;
            left: 50%;
            transform: translate(-50%, -150%);
            display: flex;
            flex-direction: column;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0px 0px 10px 3px #1E1F22;
            transition: all 400ms;
            z-index: 1;

            .social__link {

                a {
                    display: flex;
                    gap: 1rem;
                    align-items: center;
                    padding: 0.5rem;
                    padding-right: 2rem;
                    background-color: #2B2D31;
                    transition: all 200ms;


                    img {
                        width: 36px;
                        height: 36px;
                    }

                    &:hover {
                        background-color: #414249;
                    }
                }
            }
        }

        &:hover .social__dropdown {
            text-decoration: underline;
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
        gap: 1rem;

        .nav__item {
            .nav__item-text {
                transition: all 300ms;
                &:hover{
                    color: #808489;
                    text-decoration: underline;
                }
            }
        }
    }
    .drop-active {
        transform: translate(-50%, 0%) !important;
    }
}
</style>