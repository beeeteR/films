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
                    <!-- <div class="studios" v-if="Object.keys(studios).length > 1">
                        <button class="studio" v-for="studio in Object.keys(studios)" :key="studio"
                            @click="selectedStudio = studios[studio]">{{ studio }}</button>
                    </div>-->
                    <div class="seasons" v-if="isSerial">
                        <button class="season" v-for="seasonNum in Number(film[0].last_season)" :key="seasonNum"
                            :class="{ 'selected__btn': seasonNum == selectedSeason }"
                            @click="selectedSeason = seasonNum">Сезон {{ seasonNum
                            }}</button>
                    </div>

                    <iframe :src="link" allowfullscreen frameborder="0" width="100%" height="600px" seamless
                        v-if="studios"></iframe>

                    <div class="episodes" v-if="isSerial">
                        <button class="episode" v-for="episodeNum in Number(film[0].last_episode)" :key="episodeNum"
                            :class="{ 'selected__btn': episodeNum == selectedEpisode }"
                            @click="selectedEpisode = episodeNum">Серия {{
                                episodeNum }}</button>
                    </div>
                </div>
                <h1 class="film__title">
                    <span>{{ film[0].info.rus }}</span>
                    <span>{{ film[0].info.age + '+' }}</span>
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
        </template>
    </div>
</template>

<script>
import { useMainStore } from "@/store";
import { defineComponent } from "vue";
import LoadingComponent from '../components/LoadingComponent.vue'

export default defineComponent({
    components: {
        LoadingComponent
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
            studios: {},
            genres: [],
            actors: [],
            time: { 'hour': 0, 'minutes': 0 },
            rating: { 'kp': { 'votes': 0, 'rating': 0 }, 'imdb': { 'votes': 0, 'rating': 0 } },
            link: ''
        }
    },
    async mounted() {
        await this.store.getById(this.$route.params.id).then(result => this.film = result.results)
        this.parse()
        this.loading = false
    },
    methods: {
        parse() {
            this.film.forEach(el => {
                this.studios[`${el.studio ? el.studio : ''} ${el.translation}`] = el.link
            })
            this.selectedStudio = Object.keys(this.studios)[0]

            this.rating.kp.rating = this.film[0].info.rating.rating_kp
            this.rating.kp.votes = this.film[0].info.rating.vote_num_kp
            this.rating.imdb.rating = this.film[0].info.rating.rating_imdb
            this.rating.imdb.votes = this.film[0].info.rating.vote_num_imdb

            if (this.film[0].serial == 1) {
                this.isSerial = true
            }

            this.genres = this.film[0].info.genre.split(',')
            this.actors = this.film[0].info.actors.split(',')

            this.time['hour'] = Math.floor(this.film[0].info.time / 60 / 60)
            this.time['minutes'] = Math.floor(this.film[0].info.time / 60) - this.time['hour'] * 60

            this.changeLink()
        },
        changeLink() {
            this.link = this.isSerial ? `${this.studios[this.selectedStudio]}?season=${this.selectedSeason}&episode=${this.selectedEpisode}` : this.studios[this.selectedStudio]
        }
    },
    watch: {
        selectedSeason() {
            this.changeLink()
        },
        selectedEpisode() {
            this.changeLink()
        }
    }
})
</script>

<style lang="scss" scoped>
.wrapper {
    margin-top: 70px;
    .player {
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

        .studios,
        .seasons,
        .episodes {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 0.5rem;
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
        justify-content: space-between;
        gap: 2rem;
        margin: 2rem 0;
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
</style>