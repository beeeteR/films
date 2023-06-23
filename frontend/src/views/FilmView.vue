<template>
    <div class="wrapper">

        <template v-if="loading">
            <div>
                <loading-component></loading-component>
            </div>
        </template>

        <template v-else>
            <div>
                <div class="player">
                    <h3>Выбрать озвучку:</h3>
                    <div class="studios">
                        <button class="studio" v-for="studio in studios" :key="studio.name"
                            :class="{ 'selected__btn': studio.name.trim() == selectedStudio.trim() }"
                            @click="selectedStudio = studio.name">{{
                                studio.name
                            }}</button>
                    </div>
                    <div class="seasons" v-if="isSerial">
                        <button class="season" v-for="seasonNum in Object.keys(episodes)" :key="seasonNum"
                            :class="{ 'selected__btn': seasonNum == selectedSeason }"
                            @click="selectedSeason = seasonNum">Сезон {{ seasonNum
                            }}</button>
                    </div>

                    <div class="frame__wrapper" v-if="studios.length">
                        <iframe :src="link" allowfullscreen frameborder="0" width="100%" height="600px" seamless
                            ref="iframe"></iframe>
                    </div>

                    <div class="episodes" v-if="isSerial">
                        <button class="episode" v-for="episodeNum in Object.keys(this.episodes[this.selectedSeason])"
                            :key="episodeNum" :class="{ 'selected__btn': episodeNum == selectedEpisode }"
                            @click="selectedEpisode = episodeNum">Серия {{
                                episodeNum }}</button>
                    </div>
                </div>
                <h1 class="film__title">
                    <span>{{ film[0].info.rus }}</span>
                    <span>
                        <input type="button" class="inp__btn" value="Добавить в плейлист" @click="changeStateModal(true)">
                        {{ film[0].info.age + '+' }}
                    </span>
                </h1>
                <table class="film__info">
                    <tr>
                        <th>Рейтинг КиноПоиск / IMDB</th>
                        <td>{{ rating.kp.rating }} / {{ rating.imdb.rating }}</td>
                    </tr>
                    <tr>
                        <th>Кол-во проголосовавших КиноПоиск / IMDB</th>
                        <td>{{ rating.kp.votes }} / {{ rating.imdb.votes }}</td>
                    </tr>
                    <tr>
                        <th>Страна</th>
                        <td>{{ film[0].info.country }}</td>
                    </tr>
                    <tr>
                        <th>Дата выпуска</th>
                        <td>{{ film[0].info.premiere }}</td>
                    </tr>
                    <tr>
                        <th>Режиссер</th>
                        <td>{{ film[0].info.director }}</td>
                    </tr>
                    <tr>
                        <th>Жанры</th>
                        <td>
                            <span v-for="genre in genres" :key="genre">{{ ' ' + genre }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Актеры</th>
                        <td>
                            <span v-for="actor in actors" :key="actor">{{ actor == actors[-1] ? actor : actor + ', '
                            }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Продолжительность</th>
                        <td>{{ time['hour'] ? `${time['hour']} ч. ${time['minutes']} мин.` : `${time['minutes']} мин.` }}
                        </td>
                    </tr>
                    <tr v-if="film[0].info.slogan">
                        <th>Слоган</th>
                        <td>{{ film[0].info.slogan }}</td>
                    </tr>
                    <tr>
                        <th>Описание</th>
                        <td>{{ film[0].info.description }}</td>
                    </tr>
                </table>
            </div>
            <comments-film :kpID="Number(this.film[0].kinopoisk_id)"></comments-film>
            <modal-add-to-playlist v-if="store.playlists" :opened="openedModal" :film="film[0]" :season="selectedSeason"
                :episode="selectedEpisode" :studio="selectedStudio"
                @closeModalAdd="changeStateModal(false)"></modal-add-to-playlist>
            <create-playlist v-else :opened="openedModal" @closeModal="changeStateModal(false)"></create-playlist>
        </template>
    </div>
</template>

<script>
import { useMainStore } from "@/store";
import { defineComponent } from "vue";
import API from "../api/api";
import CommentsFilm from "../components/CommentsFilm.vue";
import CreatePlaylist from "../components/CreatePlaylist.vue";
import LoadingComponent from '../components/LoadingComponent.vue'
import ModalAddToPlaylist from "../components/ModalAddToPlaylist.vue";

export default defineComponent({
    components: {
        LoadingComponent,
        CommentsFilm,
        ModalAddToPlaylist,
        CreatePlaylist
    },
    data() {
        return {
            store: useMainStore(),
            loading: true,
            isSerial: false,
            selectedStudio: '',
            selectedSeason: 1,
            selectedEpisode: 1,
            film: [],
            studios: [],
            genres: [],
            actors: [],
            episodes: [],
            time: { 'hour': 0, 'minutes': 0 },
            rating: { 'kp': { 'votes': 0, 'rating': 0 }, 'imdb': { 'votes': 0, 'rating': 0 } },
            link: '',
            openedModal: false,
            inPlaylist: false
        }
    },
    async mounted() {
        await this.store.getById(this.$route.params.id).then(({ results }) => this.film = results)
    },
    methods: {
        parse() {
            this.film.forEach(el => {
                this.studios.push({ 'name': `${el.studio ? el.studio : ''} ${el.translation}`, 'link': el.link })
            })
            this.selectedStudio = this.studios[0].name

            this.rating.kp.rating = this.film[0].info.rating.rating_kp
            this.rating.kp.votes = this.film[0].info.rating.vote_num_kp
            this.rating.imdb.rating = this.film[0].info.rating.rating_imdb
            this.rating.imdb.votes = this.film[0].info.rating.vote_num_imdb

            if (this.film[0].serial == 1) {
                this.isSerial = true
                this.episodes = this.film[0].episodes
            }

            this.genres = this.film[0].info.genre.split(',')
            this.actors = this.film[0].info.actors.split(',')

            this.time['hour'] = Math.floor(this.film[0].info.time / 60 / 60)
            this.time['minutes'] = Math.floor(this.film[0].info.time / 60) - this.time['hour'] * 60

            if (this.store.playlists) {
                setTimeout(() => {
                    this.store.playlists.forEach(playlist => {
                        playlist.films.forEach(filmFromPlaylist => {
                            if (filmFromPlaylist.id_kp == this.film[0].kinopoisk_id) {
                                this.inPlaylist = filmFromPlaylist.id
                                this.selectedEpisode = filmFromPlaylist.episode
                                this.selectedSeason = filmFromPlaylist.season
                                this.selectedStudio = filmFromPlaylist.studio
                            }
                        })
                    })
                }, 500);
            }
            this.changeLink()
        },
        changeLink() {
            let studioLink
            this.studios.forEach(el => {
                if (el.name.trim() == this.selectedStudio.trim()) {
                    studioLink = el.link
                    return
                }
            })
            this.link = this.isSerial ? `${studioLink}?season=${this.selectedSeason}&episode=${this.selectedEpisode}` : studioLink
            if (this.inPlaylist && this.isSerial) {
                API.updateFilmInPlaylist(this.inPlaylist, this.selectedSeason, this.selectedEpisode)
            }
        },
        changeEpisodes() {
            this.film.forEach(el => {
                if (this.selectedStudio.indexOf(el.translation) != -1) {
                    this.episodes = el.episodes
                    return
                }
            })
        },
        changeStateModal(value) {
            this.openedModal = value
        }
    },
    watch: {
        $route() {
            this.parse()
            window.location.reload()
        },
        selectedSeason() {
            this.changeLink()
        },
        selectedEpisode() {
            this.changeLink()
        },
        selectedStudio() {
            this.changeEpisodes()
            this.changeLink()
        },
        film() {
            this.parse()
            this.loading = false
        }
    }
})
</script>

<style lang="scss" scoped>
.wrapper {
    margin-top: 70px;

    .player {
        h3 {
            margin-top: 1rem;
        }

        button {
            padding: 0.8rem 0.5rem;
            color: black;
            border: unset;
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.7);
            font-size: 1.05rem;
            transition: all 300ms;
            cursor: pointer;

            &:hover {
                background-color: rgba(255, 255, 255, 0.4);
            }
        }

        .selected__btn {
            color: white;
            background-color: #181818 !important;
        }

        .seasons,
        .episodes {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 0.5rem;
            padding: 1rem 0;
        }

        .studios {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            padding: 1rem 0;
        }

        .seasons {
            padding: 1rem 0;
        }

        .episodes {
            padding-top: 1rem;
        }
    }

    .film__title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        margin: 2rem 0;

        span:nth-child(2) {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
    }

    .film__info {
        width: 100%;
        border-collapse: collapse;
        outline: 1px solid #B5BAC1;
        border-radius: 1rem;
        padding: 1rem;
        overflow: hidden;

        tr {
            padding: 0.5rem;

            &:nth-child(2n + 1) {
                background-color: #313338;
            }

            th {
                text-align: left;
                padding: 1rem;
            }

            td {
                padding: 0.5rem;
            }
        }
    }
}

#dropdown {
    display: none !important;
}
</style>