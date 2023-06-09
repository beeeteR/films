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
import MyHeader from "./components/MyHeader.vue";
import SideBar from "./components/SideBar.vue"

export default defineComponent({
  components: {
    MyHeader,
    SideBar
  },
  data() {
    return {
      stateSideBar: true
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
}

.spacer {
  height: 100vh;
  width: calc(242px + 1.5rem);
}

.spacer-slim {
  width: calc(72px + 1.5rem) !important;
}
</style>