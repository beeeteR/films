<template>
  <my-header @choiseStateSideBar="choiseStateSideBar"></my-header>
  <side-bar :state="stateSideBar"></side-bar>
  <div class="content">
    <div class="spacer" :class="{ 'spacer-slim': !stateSideBar }"></div>
    <router-view />
  </div>
</template>
<script>
import { defineComponent } from "vue";
import { useMainStore } from "./store";
import API from "./api/api";
import MyHeader from "./components/MyHeader.vue";
import SideBar from "./components/SideBar.vue"

export default defineComponent({
  components: {
    MyHeader,
    SideBar
  },
  data() {
    return {
      store: useMainStore(),
      stateSideBar: true
    }
  },
  async mounted() {
    let token = this.$cookies.get('films_user_id')
    if (token) {
      this.store.user = true
      let res = await API.getUserByToken(token)

      this.store.user = res.user
      this.store.watchLater = res.watch_later
      this.store.playlists = res.playlists
    }
  },
  methods: {
    choiseStateSideBar(state) {
      this.stateSideBar = state
    }
  }
})
</script>
<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Montserrat', sans-serif;
  color: white;
}

html,
body {
  background-color: #2B2D31;
}

a {
  text-decoration: none;
  color: inherit;
  user-select: none;
}

.wrapper {
  margin: 0 auto;
  padding: 0 1.5rem;
  width: 100%;
}

.content {
  display: flex;
  margin-bottom: 5rem;
}

.title {
  text-align: center;
  padding: 2rem;
  margin: 2rem 0;
  border-radius: 0.5rem;
  border: 1px solid #242424;
}


.spacer {
  height: 100vh;
  width: calc(242px + 1.5rem);
}

.spacer-slim {
  width: calc(72px + 1.5rem) !important;
}

.modal__wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: -1;

  .modal__content {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    padding: 3rem 7rem;
    border-radius: 0.5rem;
    border: 1px solid darkcyan;
    background-color: #2B2D31;
    transform: translateY(-1000px);
    transition: all 400ms;
  }
}

.is__open {
  z-index: 2;
  background-color: rgba(0, 0, 0, 0.4);
  transition: all 300ms;

  .modal__content {
    transform: translateY(0);
  }
}

.closed {
  background-color: rgba(0, 0, 0, 0);

  .modal__content {
    transform: translateY(-1000px) !important;
  }
}

.inp__text {
  padding: 0.5rem;
  background-color: transparent;
  border: unset;
  border-bottom: 1px solid white;
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
  color: white;
  transition: all 300ms;

  &:hover {
    background-color: rgba(0, 0, 0, 0.2);
  }

  &:focus-visible {
    outline: none;
  }
}

.inp__btn {
  background-color: darkcyan;
  padding: 1rem 5rem;
  font-size: 1.1rem;
  font-weight: 600;
  border-radius: 0.5rem;
  border: none;
  outline: 1px solid darkcyan;
  transition: all 300ms;
  cursor: pointer;

  &:hover {
    background-color: rgb(0, 89, 89);
    outline: 1px solid rgb(0, 89, 89);
  }

  &:active {
    outline: 1px solid #555555;
    background-color: rgb(0, 165, 165);
  }

  &:focus-visible {
    outline: none;
  }
}
</style>