<script>
import { usePage } from "@inertiajs/vue3";

export default {
    data() {
        return {
            isOpen: true,
        };
    },

    computed: {
        flash() {
            const flash = usePage().props.flash;
            this.changeIsOpen();
            return flash;
        },
    },

    methods: {
        changeIsOpen() {
            setTimeout(
                function () {
                    this.isOpen = false;
                }.bind(this),
                5000,
            );
        },
    },

    watch: {
        flash(newVal) {
            const hasMessages = Object.values(newVal).some(
                (arr) => Array.isArray(arr) && arr.length > 0,
            );
            if (hasMessages) {
                this.isOpen = true;
            }
        },
    },
};
</script>

<template>
    <div class="flash-messages-container" v-if="isOpen">
        <div
            class="flash-message-container"
            v-for="(messages, type) in flash"
            :key="type"
        >
            <div
                :class="['flash-message', type]"
                v-for="(message, index) in messages"
                :key="index"
            >
                <span>{{ message }}</span>
            </div>
        </div>
    </div>
</template>

<style lang="sass">
.flash-messages-container
    position: fixed
    top: 15px
    left: 50%
    transform: translateX(-50%)
    z-index: 9999
    width: max-content
    max-width: 90%
    display: flex
    flex-direction: column
    align-items: center
    gap: 10px

    .flash-message-container
        .flash-message
            padding: 10px 16px
            border-radius: 8px
            background: white
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15)
            font-size: 14px
            color: #333
            min-width: 200px
            text-align: center

            transform: translateY(-20px)
            opacity: 0
            animation: slideDown 0.3s ease-out forwards, fadeOut 0.3s ease-in 4.7s forwards

            &.error
                background: var(--alert-error-background,)
                color: #c62828
            &.warning
                background: var(--alert-warning-background)
                color: #ef6c00
            &.success
                background: var(--alert-message-background)
                color: #2e7d32
@keyframes slideDown
    from
        transform: translateY(-20px)
        opacity: 0
    to
        transform: translateY(0)
        opacity: 1

@keyframes fadeOut
    from
        opacity: 1
    to
        opacity: 0
        transform: translateY(-20px)
</style>
