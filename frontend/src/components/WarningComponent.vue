<template>
    <div class="warning" :class="{ 'warning__open': isOpen }" @click="isOpen = false">
        <span class="warning__text">{{ text }}</span>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        text: {
            type: String,
            required: true
        },
        opened: {
            type: Boolean,
            defalut: false
        }
    },
    data() {
        return {
            isOpen: false
        }
    },
    watch: {
        opened(newVal) {
            if (newVal) {
                this.isOpen = true
                setTimeout(() => {
                    this.isOpen = false
                }, 3000);
            }
        },
        isOpen(newVal) {
            if (!newVal) {
                this.$emit('warningClosed')
            }
        }
    }
})
</script>

<style lang="scss" scoped>
.warning {
    position: fixed;
    top: -300px;
    left: 1rem;
    z-index: 1000;
    padding: 1rem;
    background-color: rgb(218, 218, 39);
    border-radius: 0.5rem;
    max-width: 650px;
    text-align: center;
    cursor: pointer;
    transition: all 300ms;

    &__text {
        font-size: 1.2rem;
        color: black;
    }
}

.warning__open {
    top: 3rem !important;
}
</style>