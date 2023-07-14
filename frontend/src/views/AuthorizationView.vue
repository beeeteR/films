<template>
    <div class="wrapper">
        <div class="form__wrapper">
            <form ref="form">
                <h2 class="form__title">{{ register ? 'Регистрация' : 'Войти' }}</h2>
                <div class="input__group">
                    <div class="input__item" v-show="register">
                        <label>
                            <input type="text" id="name" name="name" autocomplete="on" v-model="name">
                            <span>Имя пользователя</span>
                        </label>
                    </div>
                    <div class="input__item">
                        <label for="email">
                            <input type="email" id="email" name="email" autocomplete="on" v-model="email">
                            <span>Email</span>
                        </label>
                    </div>
                    <div class="input__item">
                        <label for="password">
                            <input type="password" id="password" name="password" autocomplete="on" v-model="password">
                            <span>Пароль</span>
                        </label>
                    </div>
                    <div class="input__item" v-show="register">
                        <label for="conf_password">
                            <input type="password" id="conf_password" name="confPassword" autocomplete="on"
                                v-model="confPassword">
                            <span>Повторите пароль</span>
                        </label>
                    </div>
                </div>
                <p class="reset__password">Забыли пароль?</p>
                <div class="auth__btns">
                    <input type="button" :value="register ? 'Войти' : 'Регистрация'" @click="register = !register">
                    <input type="button" :value="register ? 'Зарегистрироваться' : 'Войти'" @click="auth()">
                </div>
            </form>
        </div>
    </div>
    <warning-component :opened="warningState" :text="warningText" @warningClosed="warningState = false"></warning-component>
</template>

<script>
import { defineComponent } from 'vue'
import { useMainStore } from '@/store';
import API from '@/api/api';
import CONSTS from '@/consts'
import WarningComponent from '@/components/WarningComponent.vue';

export default defineComponent({
    components: {
        WarningComponent
    },
    data() {
        return {
            store: useMainStore(),
            register: false,
            name: '',
            email: '',
            password: '',
            confPassword: '',
            warningText: '',
            warningState: false,
        }
    },
    methods: {
        auth() {
            if (this.register) {
                let validation = this.validate()
                if (validation != 'OK') {
                    this.warningText = validation
                    this.warningState = true
                    return
                } else {
                    API.createUser(this.$refs.form).then(res => {
                        if (res == 'email exist') {
                            this.warningText = 'Аккаунт с такой почтой уже существует'
                            this.warningState = true
                        } else {
                            this.updateStateUser(res)
                        }
                    })
                }
            } else {
                API.authUser(this.email, this.password).then(res => {
                    if (res == 'password error') {
                        this.warningText = 'Неверный пароль'
                        this.warningState = true
                    } else if (res == 'email not exist') {
                        this.warningText = 'Аккаунта с такой электронной почтой не существует'
                        this.warningState = true
                    } else {
                        this.updateStateUser(res)
                    }
                })
            }
        },
        validate() {
            if (this.name.length < 4) {
                return 'Длина имени меньше 4 символов'
            }
            if (this.name.length > 16) {
                return 'Длина имени больше 16 символов'
            }

            let emailCharIndex = this.email.indexOf('@')
            if (this.email.indexOf('@') == -1 ||
                this.email.indexOf('.') == -1 ||
                this.email.slice(0, emailCharIndex).length < 5 ||
                this.email.slice(emailCharIndex, -1).length < 5) {
                return 'Введите корректный адрес электронной почты'
            }
            if (this.password.length < 7) {
                return 'Длина пароля меньше 7 символов'
            }
            if (this.password != this.confPassword) {
                return 'Пароли не совпадают'
            }
            return 'OK'
        },
        async updateStateUser(userToken) {
            await API.getUserByToken(userToken).then(res => {
                this.store.user = res.user
                this.store.watchLater = res.watch_later
                this.store.playlists = res.playlists
            })
            this.$router.push({ path: '/profile' })
            this.$cookies.set(CONSTS.cookieName, userToken, '30d')
        }
    },
    computed: {
        checkUserAuth() {
            return this.store.user
        }
    },
    watch: {
        checkUserAuth(newVal) {
            if (newVal) {
                this.$router.push({ path: '/profile' })
            }
        }

    }
})
</script>

<style lang="scss">
.form__wrapper {
    margin-top: 100px;
    width: 100%;
    display: flex;
    justify-content: center;

    form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        height: fit-content;
        padding: 3rem 10rem;
        border: 2px solid darkcyan;
        border-radius: 0.5rem;

        .form__title {
            text-align: center;
        }

        .input__group {
            display: flex;
            flex-direction: column;
            gap: 2.25rem;

            .input__item {
                label {
                    position: relative;

                    span {
                        position: absolute;
                        left: 0.5rem;
                        top: -0.25rem;
                        cursor: text;
                        padding: 0.25rem 0.5rem;
                        border-radius: 0.5rem;
                        background-color: rgb(21, 21, 21);
                        transition: all 300ms;
                        z-index: 5;
                    }

                    input {
                        width: 100%;

                        &:focus~span {
                            transform: translateY(-2.5rem);
                            background-color: darkcyan;
                        }
                    }
                }
            }
        }
    }

    .reset__password {
        cursor: pointer;
        text-decoration: underline;
        text-align: right;
        margin-bottom: 1rem;
    }

    .auth__btns {
        width: 350px;
        display: flex;
        justify-content: space-between;
        transition: all 300ms;

        input {
            cursor: pointer;

            &:hover {
                transform: scale(105%);
                background-color: rgba(0, 0, 0, 0.4);
            }
        }
    }

    input {
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
        background-color: transparent;
        border: 1px solid rgba(255, 255, 255, 1);
        border-radius: 0.5rem;
        transition: all 300ms;

        &:not([type=button]) {

            &:focus,
            &:focus-within,
            &:focus-visible {
                border: 1px solid darkcyan !important;
                outline: none;
            }
        }
    }
}
</style>