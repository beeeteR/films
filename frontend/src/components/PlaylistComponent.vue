<template>
    <div class="playlist" :class="{ 'inp__show': stateInput }">
        <h2 :to="`/playlist/${playlist.playlist_id}`" class="playlist__title">{{ playlist.films.length == 0 ?
            `Плейлист "${playlist.name}" пуст.` : playlist.name }}
        </h2>
        <div class="playlist__content">
            <div class="playlist__poster" v-for="film in playlist.films.slice(0, 4)" :key="film.ip"
                :style="{ 'backgroundImage': film.poster_url }"></div>
        </div>
        <div class="actions">
            <router-link :to="`/playlist/${playlist.playlist_id}`">
                <div class="action" title="Переименовать плейлист" @click="stateInput = true">
                    <font-awesome-icon :icon="['fas', 'file-signature']" size="lg" />
                </div>
                <div class="action" title="Удалить плейлист" @click="delPlaylist()">
                    <font-awesome-icon :icon="['fas', 'trash']" size="lg" />
                </div>
            </router-link>
        </div>
        <div class="input__wrapper">
            <div class="input__content">
                <input type="text" :placeholder="playlist.name" v-model="textInput" ref="input" @keypress="rename($event)">
                <div class="input__send-btn">
                    <font-awesome-icon :icon="['fas', 'arrow-right']" size="lg" @click="rename()" />
                </div>
            </div>
            <div class="action" @click="stateInput = false">
                <font-awesome-icon :icon="['fas', 'xmark']" size="2xl" />
            </div>
        </div>
    </div>
    <question-component :question="textQuestion" :state="stateQuestion" @getAnswer="getAnswer($event)"></question-component>
</template>

<script>
import { defineComponent } from "vue";
import API from "../api/api";
import { useMainStore } from "../store";
import QuestionComponent from "./QuestionComponent.vue";

export default defineComponent({
    components: {
        QuestionComponent
    },
    props: {
        playlist: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            store: useMainStore(),
            stateQuestion: false,
            textQuestion: '',
            stateInput: false,
            textInput: ''
        }
    },
    methods: {
        delPlaylist() {
            this.textQuestion = `Вы действительно хотите удалить плейлист "${this.playlist.name}"?`
            this.stateQuestion = true
        },
        rename(event = false) {
            if (event == false || event.code == 'Enter') {
                API.updateNamePlaylist(this.playlist.playlist_id, this.textInput)
                API.getUserPlaylists(this.store.user.id).then(res => this.store.playlists = res)
                this.stateInput = false
                this.textInput = ''
            }
        },
        getAnswer(value) {
            this.stateQuestion = false
            if (value) {
                API.delPlaylist(this.playlist.playlist_id)
                API.getUserPlaylists(this.store.user.id).then(res => this.store.playlists = res)
            }
        }
    }
})
</script>

<style lang="scss" scoped>
.inp__show {
    cursor: auto !important;

    .actions a,
    .playlist__title {
        top: -100% !important;
    }

    .input__wrapper {
        top: 0 !important;

        .action {
            cursor: pointer;
        }

        .input__send-btn {
            cursor: pointer;
        }
    }
}

.playlist {
    position: relative;
    height: 400px;
    border-radius: 0.5rem;
    padding: 1rem;
    outline: 1px solid darkcyan;
    cursor: pointer;
    overflow: hidden;

    &:hover {
        .playlist__title {
            top: -100%;
        }

        .actions a {
            top: 0;
        }

        .playlist__content {
            padding: 0;
        }
    }

    .playlist__title,
    .actions a,
    .input__wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        font-size: 1.4rem;
        text-shadow: 4px 4px 4px black;
        font-weight: 600;
        background-color: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(1px);
        transition: all 400ms;
    }

    .actions a {
        display: flex;
        gap: 3rem;
        top: 100%;
    }

    .action {
        padding: 1rem;
        background-color: cadetblue;
        border-radius: 50%;
        transition: all 400ms;
        text-align: center;

        &:hover {
            background-color: darkcyan;
        }
    }

    .input__wrapper {
        top: 100%;
        display: flex;
        flex-direction: column;
        gap: 3rem;

        .input__content {
            display: flex;

            input {
                border: none;
                outline: none;
                background-color: rgba(0, 0, 0, 0.6);
                border-bottom: 1px solid white;
                padding: 0.5rem;
                font-size: 1.3rem;
                text-shadow: 4px 4px 4px black;
            }

            .input__send-btn {
                padding: 0.5rem;
                background-color: cadetblue;
                transition: all 400ms;

                &:hover {
                    background-color: darkcyan;
                }
            }
        }

        .action {
            svg {

                width: 48px;
                height: 48px;
            }
        }
    }

    .playlist__content {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: 0.5rem;
        width: 100%;
        height: 100%;
        padding: 0.5rem;
        transition: all 300ms;

        .playlist__poster {
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100%;
            border-radius: 0.5rem;
        }
    }
}
</style>