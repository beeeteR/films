<template>
    <div class="filter" @click="changeStateOpen($event, 'li')" tabindex="0" ref="filter">
        <div class="filter__type">{{ choisedItem ? choisedItem : title }}</div>
        <div class="filter__btns">
            <span v-if="choisedItem" @click="resetFilter($event)"><font-awesome-icon :icon="['fas', 'xmark']"
                    size="lg" /></span>
            <span class="arrow" :class="{ 'flip': isOpen }"><font-awesome-icon :icon="['fas', 'caret-down']"
                    size="lg" /></span>
        </div>
        <ul class="filter__drop" v-show="isOpen">
            <li v-for="item in items" :key="item" @click="setChoisedItem(item)">{{ item }}</li>
        </ul>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        items: {
            type: Array,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        opened: {
            type: Boolean,
            default: false
        },
        type: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            isOpen: false,
            choisedItem: null
        }
    },
    methods: {
        setChoisedItem(value) {
            this.choisedItem = value
            this.isOpen = false
        },
        resetFilter(event) {
            this.choisedItem = null
            this.isOpen = false
            this.changeStateOpen(event, 'span')
        },
        changeStateOpen(event, checkFor) {
            this.isOpen = !this.isOpen
            this.$refs.filter.querySelectorAll(checkFor).forEach(el => {
                if (el == event.target) {
                    this.isOpen = false
                }
            })
        }
    },
    watch: {
        opened() {
            if (this.opened != null) {
                this.isOpen = this.opened
            }
        },
        choisedItem() {
            this.$emit('changeFilterValue', { 'value': this.choisedItem, 'type': this.type })
        }
    }
})
</script>

<style lang="scss" scoped>
.filter {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #242424;
    user-select: none;
    cursor: pointer;

    .filter__type {
        &::first-letter {
            text-transform: uppercase;
        }
    }

    .filter__btns {
        display: flex;
        gap: 1rem;

        .arrow {
            transform: rotate(0);
            transition: all 300ms;
        }

        .flip {
            transform: rotate(180deg) !important;
        }
    }

    .filter__drop {
        position: absolute;
        top: 38px;
        left: 0;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        border-radius: 0.5rem;
        border: 1px solid #242424;
        background-color: #2B2D31;
        list-style: none;

        li {
            padding: 0.5rem 1rem;

            &:hover {
                background-color: #242424;
            }

            &::first-letter {
                text-transform: uppercase;
            }
        }
    }
}
</style>