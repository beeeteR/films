<template>
    <div class="wrapper">
        <div class="content__wrapper">
            <h1 @click="logout()">Выйти</h1>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import { useMainStore } from '@/store';
import CONSTS from '@/consts'

export default defineComponent({
    data() {
        return {
            store: useMainStore(),
            user: false
        }
    },
    mounted() {
        this.user = this.store.user
        if (!this.user) {
            this.$router.push({ path: '/auth' })
        }
    },
    methods: {
        logout() {
            this.store.user = false
            this.store.watchLater = []
            this.store.playlists = null
            this.$cookies.remove(CONSTS.cookieName)
            this.$router.push({ path: '/auth' })
        }
    }
})
</script>

<style lang="scss">
.content__wrapper {
    margin-top: 100px;
    width: 100%;
    display: flex;
    justify-content: center;
}
</style>