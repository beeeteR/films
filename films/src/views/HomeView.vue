<template>
  <div>
  </div>
</template>

<script>
import API from '@/api/api';
import { useMainStore } from '@/store';
import { defineComponent } from 'vue';

export default defineComponent({
  data() {
    return {
      store: useMainStore()
    }
  },
  mounted(){
    this.test()
  },
  methods: {
    test() {
        API.getByTitle('Магическая битва').then(res=>console.log(res))
      // API.getAll('all', 1, 'меха').then(res => console.log(res))
    }
  }
})
</script>

<style lang="scss" scoped></style>