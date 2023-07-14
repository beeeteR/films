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
            <span class="answer__to" v-if="comment.is_answer">
                Ответ пользователю <b class="answer__to-name">{{ answerToName }}</b>,
            </span>
            <span ref="commentText">
                {{ comment.text }}
            </span>
        </div>
        <div class="comment__btns">
            <span @click="answer(comment.id)">Ответить</span>
        </div>
        <div class="comment__answer__write">
            <div class="write__comment" v-if="answerBtnState || editBtnState">
                <textarea placeholder="Написать ответ" v-model="answerText" v-if="answerBtnState" @keydown.enter="sendAnswer($event)"
                    ref="answerArea"></textarea>
                <textarea placeholder="Изменить комментарий" v-model="answerText" v-if="editBtnState" @keydown.enter="sendAnswer($event, 'edit')"
                    ref="editArea"></textarea>
                <input type="button" value="Ответить" v-if="answerBtnState" @click="sendAnswer(false)">
                <input type="button" value="Отправить" v-if="editBtnState" @click="sendAnswer(false, 'edit')">
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
    emits: ['sendAnswer', 'updateCommentaries'],
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
        },
        commentItem: {
            type: Object,
            required: false
        }
    },
    data() {
        return {
            store: useMainStore(),
            isAnwer: false,
            answerTo: '',
            answerToName: '',
            answerText: '',
            stateQuestion: false,
            textQuestion: 'Вы уверены, что удалить комментарий?',
            answerBtnState: false,
            editBtnState: false
        }
    },
    mounted() {
        if (this.comment.is_answer) {
            if (this.commentItem.id == this.comment.answer_to_id)
                this.answerToName = this.commentItem.user_name
            else {
                this.answerToName = this.commentItem.answers.find(el => el.id == this.comment.answer_to_id).user_name
            }
        }
    },
    methods: {
        answer(commentID) {
            if (this.answerTo == commentID) {
                this.answerTo = ''
                this.answerBtnState = false
            } else {
                this.answerTo = commentID
                this.answerBtnState = true
            }
        },
        sendAnswer(event = false, edit = false) {
            console.log(event);
            this.$emit('sendAnswer', { 'text': this.answerText, 'answerTo': this.answerTo, 'edit': edit, 'id': this.comment.id })
            if (this.error == false) {
                this.answerTo = ''
                this.answerText = ''
            }
        },
        delComment() {
            this.stateQuestion = true
        },
        editComment() {
            this.editBtnState = !this.editBtnState
            this.answerText = this.editBtnState ? this.$refs.commentText.innerText : ''
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
                this.editBtnState = false
                this.answerBtnState = false
            }
        },
        editBtnState(newVal) {
            if (newVal) {
                this.answerBtnState = false
                setTimeout(() => {
                    this.$refs.editArea.focus()
                }, 100)
            }
        },
        answerBtnState(newVal) {
            if (newVal) {
                this.editBtnState = false
                setTimeout(() => {
                    this.$refs.answerArea.focus()
                }, 100)
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

    .answer__to {
        font-style: italic;
        font-weight: 300;

        &-name {
            color: darkcyan;
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
    padding-left: 5rem;
}
</style>