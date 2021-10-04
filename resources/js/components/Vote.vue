<template>
    <div class="d-fex flex-column vote-controls">
        <a @click.prevent="voteUp" :title="title('up')"
           class="vote-up" :class="[classes, { 'voted': isVotedUp }]">
            <i class="fas fa-caret-up fa-3x"></i>
        </a>

        <span class="votes-count">{{ count }}</span>

        <a @click.prevent="voteDown" :title="title('down')"
           class="vote-down" :class="[classes, { 'voted': isVotedDown }]">
            <i class="fas fa-caret-down fa-3x"></i>
        </a>

        <favorite v-if="name === 'question'" :question="model"></favorite>
        <accept v-else :answer="model"></accept>
    </div>
</template>

<script>
// IMPORTANT ! You need these components only when Vote is loaded, therefore you import them here, not in the app.js
import Favorite from './Favorite.vue';
import Accept from './Accept.vue';

export default {
    props: ['name', 'model'],

    computed: {
        classes () {
            return [this.signedIn ? '' : 'off', 'hello'];
        },

        endpoint () {
            return `/${this.name}s/${this.id}/vote`;
        },

        isVotedDown() {
            return this.voted === -1;
        },
        isVotedUp() {
            return this.voted === 1;
        }
    },

    components: {
        Favorite,
        Accept
    },

    data () {
        return {
            count: this.model.vote_count,
            id: this.model.id,
            voted: this.model.voted,

        }
    },

    methods: {
        title (voteType) {
            let titles = {
                up: `This ${this.name} is useful`,
                down: `This ${this.name} is not useful`
            };

            return titles[voteType];
        },

        voteUp () {
            this._vote(1);
        },

        voteDown () {
            this._vote(-1);
        },

        _vote (vote) {
            if (! this.signedIn) {
                this.$toast.warning(`Please login to vote the ${this.name}`, "Warning", {
                    timout: 3000,
                    position: 'bottomLeft'
                });

                return;
            }
            axios.post(this.endpoint, { vote })
                .then(res => {
                    this.$toast.success(res.data.message, "Success", {
                        timeout: 3000,
                        position: 'bottomLeft'
                    });

                    this.count = res.data.votesCount;
                    this.voted = vote;
                })
        }
    }
}
</script>
