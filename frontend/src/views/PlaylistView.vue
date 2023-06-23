<template>
    <div class="wrapper">
        <div class="films">
            <film-component v-for="film in playlist?.films" :key="film.id" :film="film" :isHome="false"
                :isPlaylist="true"></film-component>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { useMainStore } from "../store";
import FilmComponent from "@/components/FilmComponent.vue";

export default defineComponent({
    components: {
        FilmComponent
    },
    data() {
        return {
            store: useMainStore(),
        }
    },
    methods: {

    },
    computed: {
        playlist() {
            if (this.store.playlists) {
                return this.store.playlists.find(el => el.playlist_id == this.$route.params.id)
            }
            return undefined
        }
    },
})
</script>

<style lang="scss" scoped>
.wrapper {
    margin-top: 100px;
    .films {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
    }
}
</style>