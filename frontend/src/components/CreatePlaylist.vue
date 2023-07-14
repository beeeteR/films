<template>
    <div class="modal__wrapper" :class="{ 'is__open': isOpen }" ref="modalWrapper" @click="closeModal($event)">
        <div class="modal__content" ref="modalContent">
            <h2 style="text-align: center;">Создать плейлист</h2>
            <input type="text" placeholder="Название плейлиста" class="inp__text" ref="modalText" v-model="name">
            <input type="button" value="Создать" class="inp__btn" @click="createPlaylist()">
        </div>
    </div>
    <warning-component :opened="warningState" :text="warningText" @warningClosed="warningState = false"></warning-component>
</template>

<script>
import { defineComponent } from "vue";
import API from "../api/api";
import { useMainStore } from "../store";
import WarningComponent from "./WarningComponent.vue";

export default defineComponent({
    emits: ['closeModal', 'settedPlaylist'],
    components: {
        WarningComponent
    },
    props: {
        opened: {
            type: Boolean,
            required: false
        }
    },
    data() {
        return {
            store: useMainStore(),
            isOpen: false,
            name: '',
            validation: false,
            warningState: false,
            warningText: ''
        }
    },
    methods: {
        createPlaylist() {
            if (!this.name) {
                this.warningText = 'Введите название'
                this.warningState = true
            } else if (this.name.length > 30) {
                this.warningText = 'Название больше 30 символов'
                this.warningState = true
            } else {
                API.setPlaylist(this.store.user.id, this.name)
                this.validation = true
                this.validation = false
                API.getUserPlaylists(this.store.user.id).then(res => this.store.playlists = res)
                this.$emit("settedPlaylist")
                this.closeModal()
            }
        },
        closeModal(event = null) {
            if (event?.target == this.$refs.modalWrapper || this.validation) {
                event = this.$refs.modalWrapper
                this.name = ''
                event.classList.add('closed')
                setTimeout(() => {
                    event.classList.remove('closed')
                    this.isOpen = false
                    this.$emit('closeModal')
                }, 300);
            }

        }
    },
    watch: {
        opened(newVal) {
            this.isOpen = newVal
        },
        isOpen(newVal) {
            if (newVal) {
                this.$refs.modalText.focus()
            }
        }
    }
})
</script>

<style lang="scss"></style>