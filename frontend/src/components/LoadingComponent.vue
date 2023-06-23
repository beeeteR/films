<template>
    <div class="loading">
        <font-awesome-icon :icon="['fas', 'spinner']" spin-pulse size="2xl" />
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        height: {
            type: Number,
            default: null
        },
        width: {
            type: Number,
            default: null
        }
    },
    computed: {
        w() {
            if (this.width) {
                return `${this.width}px`
            }else {
                return '100%'
            }
        },
        h() {
            if (this.height) {
                return `${this.height}px`
            }else {
                return '100%'
            }
        }
    }
})
</script>

<style lang="scss" scoped>
.loading {
    width: v-bind('w');
    height: v-bind('h');
    display: flex;
    justify-content: center;
    align-items: center;
}

</style>