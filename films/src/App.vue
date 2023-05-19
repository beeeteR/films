<template>
  <my-header></my-header>
  <router-view />
</template>
<script>
import MyHeader from "./components/MyHeader.vue";

export default {
  components: {
    MyHeader
  }
}
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

html, body {
  width: 100vw;
  height: 100vh;
  background-color: #2B2D31;
}

a {
  text-decoration: none;
  color: inherit;
}

.wrapper {
  margin: 0 auto;
  padding: 0 1rem;
  @media screen and (max-width: 576px) {
    width: 100%;
  }
  @media screen and (max-width: 768px) {
    width: 540px;
  }
  @media screen and (max-width: 992px) {
    width: 720px;
  }
  @media screen and (max-width: 1200px) {
    width: 960px;
  }
  @media screen and (max-width: 1400px) {
    width: 1140px;
  }
  @media screen and (min-width: 1400px) {
    width: 1320px;
  }
}

</style>