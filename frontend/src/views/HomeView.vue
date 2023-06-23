<template>
  <div class="wrapper">
    <div class="compilation">
      <h1>Новые фильмы 2023 года</h1>
      <compilation-films :films="films23" v-if="films23.length > 0"></compilation-films>
      <loading-component v-else></loading-component>
    </div>
    <div class="compilation">
      <h1>Новые сериалы 2023 года</h1>
      <compilation-films :films="serials23" v-if="serials23.length > 0"></compilation-films>
      <loading-component v-else></loading-component>
    </div>
  </div>
</template>

<script>
import { useMainStore } from '@/store';
import { defineComponent } from 'vue';
import CompilationFilms from '@/components/CompilationFilms.vue'
import LoadingComponent from '@/components/LoadingComponent.vue';
let store = useMainStore()

export default defineComponent({
  components: {
    CompilationFilms,
    LoadingComponent
  },
  data() {
    return {
      films23: [],
      serials23: [],
    }
  },
  mounted() {
    store.getFilmsByYear(2023).then(res => this.films23 = res)
    setTimeout(() => {
      store.getSerialsByYear(2023).then(res => this.serials23 = res)
    }, 200);
  },
  methods: {
   
  }
})
</script>

<style lang="scss" scoped>
.wrapper {
  display: flex;
  flex-direction: column;
  gap: 5rem;
  margin-top: 70px;

  .compilation {
    margin-top: 1rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }
}
</style>