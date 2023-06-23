<template>
    <div class="comment" :class="{ 'answer': comment?.is_answer }">
        <div class="comment__title">
            <div class="comment__info">
                <span class="username">{{ comment.user_name }}</span>
                <span class="date">, оставлен {{ getCorrectDate(comment.datetime) }}</span>
            </div>
            <div class="comment__user-btns" v-if="store?.user.id == comment.id_user">
                <span @click="editComment()">
                    <font-awesome-icon :icon="['fas', 'pen']" size="lg" />
                </span>
                <span @click="delComment()">
                    <font-awesome-icon :icon="['fas', 'trash']" size="lg" />
                </span>
            </div>
        </div>
        <div class="comment__body">
            {{ comment.text }}
        </div>
        <div class="comment__btns">
            <span @click="answer(comment.id)">Ответить</span>
        </div>
        <div class="comment__answer__write">
            <div class="write__comment" v-if="answerTo == comment.id">
                <textarea placeholder="Написать ответ" v-model="answerText"></textarea>
                <input type="button" value="Ответить" @click="sendAnswer()">
            </div>
        </div>
    </div>
    <question-component :state="stateQuestion" :question="textQuestion" @getAnswer="getAnswer($event)"></question-component>
</template>

<script>
import { defineComponent } from "vue";
import API from "../api/api";
import { useMainStore } from "../store";
import QuestionComponent from "./QuestionComponent.vue";


export default defineComponent({
    components: {
        QuestionComponent
    },
    props: {
        comment: {
            type: Object,
            required: true
        },
        error: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            store: useMainStore(),
            isAnwer: false,
            answerTo: '',
            answerText: '',
            stateQuestion: false,
            textQuestion: 'Вы уверены, что удалить комментарий?'
        }
    },
    methods: {
        answer(commentID) {
            if (this.answerTo == commentID) {
                this.answerTo = ''
            } else {
                this.answerTo = commentID
            }
        },
        sendAnswer() {
            this.$emit('sendAnswer', { 'text': this.answerText, 'answerTo': this.answerTo })
            if (!this.error) {
                this.answerTo = ''
                this.answerText = ''
            }
        },
        delComment() {
            this.stateQuestion = true
        },
        editComment() {
            alert('еще не сделал')
        },
        getAnswer(value) {
            if (value) {
                API.delComment(this.comment.id)
                this.updateCommentaries()
            }
            this.stateQuestion = false
        },
        updateCommentaries() {
            this.$emit('updateCommentaries')
        },
        getCorrectDate(timestamp) {
            let datetime = new Date(Number(timestamp))
            let date = [String(datetime.getDate()).length == 1 ? `0${datetime.getDate()}` : datetime.getDate(), String(datetime.getMonth()).length == 1 ? `0${datetime.getMonth()}` : datetime.getMonth(), datetime.getFullYear()]
            let time = [String(datetime.getHours()).length == 1 ? `0${datetime.getHours()}` : datetime.getHours(), String(datetime.getMinutes()).length == 1 ? `0${datetime.getMinutes()}` : datetime.getMinutes()]

            return date.join('.').concat(', ').concat(time.join(':'))
        },
    },
    watch: {
        error(newVal) {
            if (!newVal) {
                this.answerTo = ''
                this.answerText = ''
            }
        }
    }
})
</script>

<style lang="scss">
.comment {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1rem 2rem;

    &:nth-child(2n + 1) {
        background-color: #222427;
    }

    &:nth-child(2n) {
        background-color: #36393e;
    }

    .comment__title {
        display: flex;
        justify-content: space-between;

        .comment__user-btns {
            display: flex;
            // gap: 0.5rem;

            span {
                padding: 0.5rem;
                cursor: pointer;

                path {
                    transition: all 400ms;
                }

                &:hover path {
                    color: #555555;
                }
            }
        }

        .username {
            font-size: 1.1rem;
            font-weight: 700;
        }
    }
}

.comment__btns {
    display: flex;
    gap: 2rem;

    span {
        cursor: pointer;
        color: darkcyan;
        text-decoration: underline;

        &:hover {
            text-decoration: none;
            color: rgb(0, 164, 164);
        }
    }
}

.answer {
    padding-left: 4rem;
}
</style>