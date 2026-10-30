<script>
import CrossIco from '../../icons/CrossIco.vue';

export default {
    components: {
        CrossIco
    },

    props: {
        selectedLabel: String,
        placeholder: String,
        isOpen: Boolean,
        disabled: Boolean,
        hasClear: {
            type: Boolean,
            default: false,
        },
        onClear: {
            type: Function,
            deafult: () => {}
        },
    },

    methods: {
        clickClearHandler() {
            this.onClear()
        }
    }
};
</script>

<template>
    <div
        class="select-input"
        :class="{ 'is-open': isOpen, 'is-disabled': disabled }"
    >
        <span class="select-value">
            {{ selectedLabel || placeholder }}
        </span>
        <button v-if="hasClear" class="button-cross-ico" @click.stop="clickClearHandler">
            <CrossIco class="cross-ico"/>
        </button>
        <div class="select-arrow" />
    </div>
</template>

<style lang="sass" scoped>
.select-input
    @include input()
    cursor: pointer
    display: flex
    align-items: center
    justify-content: space-between
    max-height: 32px

    .select-value
        flex: 1
        min-width: 0
        overflow: hidden
        text-overflow: ellipsis
        white-space: nowrap

    &.is-open
        border-bottom-left-radius: 0
        border-bottom-right-radius: 0
        .select-arrow
            transform: rotate(180deg)

    &.is-disabled
        background-color: #f5f5f5
        border: 1px solid #ccc
        color: #999
        cursor: not-alloweds

    .button-cross-ico
        min-height: 0
        height: 20px
        padding: 0
        background: transparent
        display: flex
        justify-content: center
        align-items: center

        top: 0
        right: 30px

        .cross-ico
            height: 20px
            color: red

        &:hover
            .cross-ico
                color: var(--text-color)

    .select-arrow
        width: 20px
        height: 20px
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232c3e50' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")
        background-repeat: no-repeat
        background-position: center
        background-size: 20px
        transition: transform 0.2s ease
        color: var(--text-color)

        .is-disabled &
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23999999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")
</style>
