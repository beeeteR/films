<template>
    <comment-item :comment="commentItem" :error="answerError" @sendAnswer="this.$emit('sendAnswer', $event)"
        @updateCommentaries="this.$emit('updateCommentaries')">
    </comment-item>
    <comment-item v-for="answer in commentItem.answers" :key="answer.id" :comment="answer" :error="answerError" :commentItem="commentItem"
        @sendAnswer="this.$emit('sendAnswer', $event)" @updateCommentaries="this.$emit('updateCommentaries')">
    </comment-item>
</template>

<script>
import { defineComponent } from "vue";
import CommentItem from "./CommentItem.vue";

export default defineComponent({
    emits: ['sendAnswer', 'updateCommentaries'],
    components: {
        CommentItem
    },
    props: {
        commentItem: {
            type: Object,
            required: true
        },
        answerError: {
            type: Boolean,
            required: true
        }
    }
})
</script>

<style lang="scss"></style>