<script>
export default {
    props: {
        selectedLabel: String,
        placeholder: String,
        isOpen: Boolean,
        disabled: Boolean,
    },
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

    &.is-open
        border-bottom-left-radius: 0
        border-bottom-right-radius: 0

    &.is-disabled
        background-color: #f5f5f5
        border: 1px solid #ccc
        color: #999
        cursor: not-alloweds
        .select-value
            flex: 1
            text-align: left

    .select-arrow
        width: 16px
        height: 16px
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232c3e50' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")
        background-repeat: no-repeat
        background-position: center
        background-size: 16px
        transition: transform 0.2s ease
        color: var(--text-color)

        .is-open &
            transform: rotate(180deg)

        .is-disabled &
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23999999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")
</style>
