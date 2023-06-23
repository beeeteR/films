<template>
    <div class="modal__wrapper" :class="{ 'is__open': isOpen }" ref="modalWrapper" @click="closeModal($event)">
        <div class="modal__content" v-if="store.playlists">
            <div class="modal__select" @click="changeSelectStateOpen()" tabindex="0" ref="filter">
                <div class="modal__select-title">{{ choisedItem ? choisedItem.name : 'Выберите плейлист' }}</div>
                <div class="modal__select-btns">
                    <span class="arrow" :class="{ 'flip': selectOpen }"><font-awesome-icon :icon="['fas', 'caret-down']"
                            size="lg" /></span>
                </div>
                <ul class="modal__select-drop" v-show="selectOpen">
                    <li v-for="playlist in store.playlists" :key="playlist.playlist_id"
                        @click="setChoisedItem(playlist.playlist_id, playlist.name)">{{ playlist.name }}</li>
                </ul>
            </div>
            <input type="button" value="Добавить" class="inp__btn" @click="addFilmToPlaylist($event)">
            <question-component :state="stateQuestion" :question="textQuestion"
                @getAnswer="getAnswer($event)"></question-component>
            <warning-component :text="warningText" :opened="warningState"
                @warningClosed="warningClosed()"></warning-component>
        </div>
        <div v-else>
            <create-playlist :opened="stateCreate" @closeModal="changeStateCreate()"
                @settedPlaylist="settedPlaylist()"></create-playlist>

        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import API from "../api/api";
import { useMainStore } from "../store";
import QuestionComponent from "./QuestionComponent.vue";
import WarningComponent from "./WarningComponent.vue";
import CreatePlaylist from "./CreatePlaylist.vue";

export default defineComponent({
    components: {
        QuestionComponent,
        WarningComponent,
        CreatePlaylist
    },
    props: {
        opened: {
            type: Boolean,
            required: false
        },
        film: {
            type: Object,
            required: true
        },
        season: {
            type: Number,
            default: null
        },
        episode: {
            type: Number,
            default: null
        },
        studio: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            store: useMainStore(),
            isOpen: false,
            selectOpen: false,
            choisedItem: null,
            stateQuestion: false,
            textQuestion: '',
            anwerOfQuestion: null,
            inPlaylist: false,
            warningState: false,
            warningText: '',
            stateCreate: true
        }
    },
    methods: {
        closeModal(event = null, isBtn) {
            if (event?.target == this.$refs.modalWrapper || isBtn) {
                event = this.$refs.modalWrapper
                event.classList.add('closed')
                setTimeout(() => {
                    event.classList.remove('closed')
                    this.isOpen = false
                    this.choisedItem = null
                    this.selectOpen = false
                    this.$emit('closeModalAdd')
                }, 300);
            }
        },
        changeSelectStateOpen() {
            this.selectOpen = !this.selectOpen
        },
        setChoisedItem(playlistID, playlistName) {
            this.choisedItem = {
                'id': playlistID,
                'name': playlistName
            }
        },
        addFilmToPlaylist() {
            if (!this.choisedItem) {
                this.textQuestion = 'Плейлист не выбран'
                this.stateQuestion = true
                return
            }

            this.store.playlists.forEach(playlist => {
                playlist.films.forEach(filmFromPlaylist => {
                    if (filmFromPlaylist.id_kp == this.film?.kinopoisk_id || filmFromPlaylist.id_kp == this.film?.id_kp) {
                        this.inPlaylist = {
                            'id': playlist.playlist_id,
                            'name': playlist.name,
                            'film_id': filmFromPlaylist.id
                        }
                    }
                })
            })

            if (!this.inPlaylist) {
                let rating = this.film.info.rating.rating_kp != 0 ? this.film.info.rating.rating_kp : this.film.info.rating.rating_imdb
                    != 0 ? this.film.info.rating.rating_imdb : 5.1
                let isSerial = this.film.serial == 1 ? 1 : 0
                API.setFilmInPlaylist(this.choisedItem.id, this.film.kinopoisk_id, `url(${this.film.info.poster})`, this.film.info.rus, rating, isSerial, this.season, this.episode, this.studio)
                API.getUserPlaylists(this.store.user.id).then(res => this.store.playlists = res)
                this.closeModal(null, true)
            } else if (this.inPlaylist.id == this.choisedItem.id) {
                this.warningText = 'Фильм уже находится в этом плейлисте'
                this.warningState = true
            } else {
                this.stateQuestion = true
                this.textQuestion = `Этот ${this.film.serial ? 'сериал' : 'фильм'} уже есть в плейлисте "${this.inPlaylist.name}". Переместить его в плейлист "${this.choisedItem.name}"?`
            }
        },
        getAnswer(value) {
            this.anwerOfQuestion = value
        },
        warningClosed() {
            this.warningState = false
        },
        changeStateCreate() {
            this.closeModal(null, true)
        },
        settedPlaylist() {
            let rating = this.film.info.rating.rating_kp != 0 ? this.film.info.rating.rating_kp : this.film.info.rating.rating_imdb
                != 0 ? this.film.info.rating.rating_imdb : 5.1
            let isSerial = this.film.serial == 1 ? 1 : 0
            setTimeout(() => {
                API.setFilmInPlaylist(this.store.playlists[0].playlist_id, this.film.kinopoisk_id, `url(${this.film.info.poster})`, this.film.info.rus, rating, isSerial, this.season, this.episode, this.studio)
                API.getUserPlaylists(this.store.user.id).then(res => this.store.playlists = res)
            }, 500);
        }
    },
    watch: {
        opened(newVal) {
            if (newVal) {
                this.isOpen = true
            }
        },
        anwerOfQuestion(newVal) {
            this.stateQuestion = false
            if (newVal) {
                API.moveFilmToAnotherPlaylist(this.inPlaylist.film_id, this.choisedItem.id)
                API.getUserPlaylists(this.store.user.id).then(res => this.store.playlists = res)
                console.log(this.inPlaylist, this.choisedItem);
                this.warningState = true
                this.warningText = `Фильм перемещен из "${this.inPlaylist.name}" в "${this.choisedItem.name}"`
            }
            this.closeModal(null, true)
        }
    }
})
</script>

<style lang="scss" scoped>
.modal__select {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #242424;
    user-select: none;
    z-index: 5;
    cursor: pointer;

    &-title {
        &::first-letter {
            text-transform: uppercase;
        }
    }

    &-btns {
        display: flex;
        gap: 1rem;

        .arrow {
            transform: rotate(0);
            transition: all 300ms;
        }

        .flip {
            transform: rotate(180deg) !important;
        }
    }

    &-drop {
        position: absolute;
        top: 38px;
        left: 0;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        border-radius: 0.5rem;
        border: 1px solid #242424;
        background-color: #2B2D31;
        list-style: none;

        li {
            padding: 0.5rem 1rem;

            &:hover {
                background-color: #242424;
            }
        }
    }
}
</style>