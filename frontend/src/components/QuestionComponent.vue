<template>
    <div class="question__wrapper" :class="{ 'question__open': state && localState }" ref="question">
        <div class="question__content">
            <p class="question__text">{{ question }}</p>
            <div class="question__btns">
                <input class="yes" type="button" value="Да" @click="changeState(true)">
                <input class="no" type="button" value="Нет" @click="changeState(false)">
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        state: {
            type: Boolean,
            default: false
        },
        question: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            answer: null,
            localState: false
        }
    },
    methods: {
        changeState(value) {
            this.$refs.question.classList.add('closed')
            setTimeout(() => {
                this.$refs.question.classList.remove('closed')
            }, 400);
            this.answer = value
            this.localState = false
            this.$emit('getAnswer', this.answer)
        }
    },
    watch: {
        state() {
            this.localState = this.state
        }
    }
})
</script>

<style lang="scss" scoped>
.question__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: -1;
    transition: all 400ms;

    .question__content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        transform: translateY(-1000px);
        max-width: 600px;
        padding: 2rem 2rem;
        background-color: rgb(226 226 226);
        border-radius: 0.5rem;
        transition: all 400ms;

        .question__text {
            font-size: 1.3rem;
            color: black;
            text-align: center;
        }

        .question__btns {
            display: flex;
            justify-content: space-between;

            input {
                padding: 0.5rem 1rem;
                font-size: 1.1rem;
                color: black;
                border: none;
                background-color: transparent;
                cursor: pointer;
                border-radius: 0.5rem;
                font-weight: 500;
                font-size: 1.2rem;
                transition: all 300ms;

                &:focus-within {
                    outline: none;
                }
            }

            .yes {
                border-bottom: 1px solid green;

                &:hover {
                    background-color: greenyellow;
                }
            }

            .no {
                border-bottom: 1px solid red;

                &:hover {
                    background-color: brown;
                }
            }
        }
    }
}

.question__open {
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 100;
    .question__content {
        transform: translateY(0);
    }
}

.closed {
    background-color: rgba(0, 0, 0, 0);

    .question__content {
        transform: translateY(-1000px);
    }
}

</style>