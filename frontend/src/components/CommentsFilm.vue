<template>
    <div class="comments__wrapper">
        <div class="write__comment">
            <h2>Напишите свой отзыв</h2>
            <textarea placeholder="Написать отзыв" v-model="commentText"></textarea>
            <input type="button" value="Добавить" @click="sendComment(this.commentText)">
        </div>
        <div class="comments" v-if="commentaries.length">
            <comment-item v-for="comment in currentComments" :key="comment.id" :comment="comment" :error="answerError" @sendAnswer="sendComment($event.text, $event.answerTo)" @updateCommentaries="getComments()"></comment-item>
            
        </div>
        <h1 class="no__comments" v-else>Комменариев пока нет</h1>
        <warning-component :text="warningText" :opened="warningState" @warningClosed="warningClosed()"></warning-component>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import API from "../api/api";
import { useMainStore } from "../store";
import CommentItem from "./CommentItem.vue";
import WarningComponent from "./WarningComponent.vue";

export default defineComponent({
    components: {
        CommentItem,
        WarningComponent
    },
    props: {
        kpID: {
            type: Number,
            required: true
        }
    },
    mounted() {
        this.getComments()
    },
    data() {
        return {
            store: useMainStore(),
            commentaries: [],
            answers: [],
            commentText: '',
            pages: 0,
            currentPage: 0,
            warningText: '',
            warningState: false,
            answerError: false
        }
    },
    methods: {
        getComments() {
            API.getComments(this.kpID).then(res => {
                this.commentaries = res.comments
                this.answers = res.answers
            })
        },
        sendComment(text, answerTo=false) {
            if (text.length < 30) {
                this.answerError = true
                this.warningText = 'Длина комментария меньше 30 символов'
                this.warningState = true
            } else if (text.length > 3000) {
                this.answerError = true
                this.warningText = 'Длина комментария больше 3000 символов'
                this.warningState = true
            } else {
                this.answerError = false
                this.warningState = false
                if (answerTo) {
                    API.setComment(this.store.user.id, this.kpID, new Date().getTime(), text, 1, answerTo)
                    this.getComments()
                } else {
                    API.setComment(this.store.user.id, this.kpID, new Date().getTime(), text)
                    this.commentText = ''
                    this.getComments()
                }
            }
        },
        warningClosed() {
            this.warningState = false
        },
        getParrentComment(comment) {
            if (comment.is_answer) {
                this.getParrentComment(this.answers.find(el => el.id == comment.answer_to_id))
            }else {
                return comment.answer_to_id
            }
        }
    },
    computed: {
        currentComments() {
            return this.commentaries.slice(0, 20)
        }
    }
})
</script>

<style lang="scss">
.comments__wrapper {
    margin-top: 5rem;

    .write__comment {

        textarea {
            margin-top: 1rem;
            resize: vertical;
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            background-color: transparent;
            border: 1px solid #555555;

            &:focus-visible {
                border: 1px solid darkcyan;
                outline: none;
            }
        }

        input {
            background-color: darkcyan;
            margin-top: 1rem;
            padding: 1rem 5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            outline: 1px solid darkcyan;
            transition: all 300ms;
            cursor: pointer;

            &:hover {
                background-color: rgb(0, 89, 89);
                outline: 1px solid rgb(0, 89, 89);
            }

            &:active {
                outline: 1px solid #555555;
                background-color: rgb(0, 165, 165);
            }

            &:focus-visible {
                outline: none;
            }
        }
    }

    .comments {
        margin-top: 2rem;
        margin-bottom: 2rem;
        display: flex;
        flex-direction: column;
    }

    .no__comments {
        margin-top: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
}
</style>