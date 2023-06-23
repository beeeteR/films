<template>
    <div class="wrapper">
        <h1 class="title" v-if="!store.playlists">Плейлистов пока нет</h1>
        <h1 class="title" v-else>Ваши плейлисты</h1>
        <div class="playlists">
            <playlist-component class="playlist" v-for="playlist in store.playlists" :key="playlist.playlist_id"
                :playlist="playlist"></playlist-component>
        </div>
        <div class="inp__wrapper">
            <input type="button" value="Создать плейлист" class="inp__btn" @click="changeStateModal(true)">
        </div>
    </div>
    <create-playlist :opened="openedModal" @closeModal="changeStateModal(false)"></create-playlist>
</template>

<script>
import { defineComponent } from "vue";
import PlaylistComponent from "../components/PlaylistComponent.vue";
import CreatePlaylist from "@/components/CreatePlaylist.vue";
import { useMainStore } from "../store";

export default defineComponent({
    components: {
        PlaylistComponent,
        CreatePlaylist,
    },
    data() {
        return {
            store: useMainStore(),
            openedModal: false
        }
    },
    mounted() {
    },
    methods: {
        changeStateModal(value) {
            this.openedModal = value
        },
    },
})
</script>

<style lang="scss" scoped>
.wrapper {
    margin-top: 70px;

    .title {
        text-align: center;
        padding: 2rem;
        margin: 2rem 0;
        border-radius: 0.5rem;
        border: 1px solid #242424;
    }

    .playlists {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem 2rem;
    }

    .inp__wrapper {
        margin-top: 2rem;
        width: 100%;
        display: flex;
        justify-content: center;


    }
}
</style>
