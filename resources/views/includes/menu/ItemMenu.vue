<script>
import { Link, router } from "@inertiajs/vue3";

export default {
    components: {
        Link,
    },

    props: {
        href: String,
        label: {
            type: String,
            default: "",
        },
        method: String,
    },
};
</script>

<template>
    <li class="link-box">
        <Link class="link" :href="href" :method="method">
            <slot />
            <span class="link-label">{{ label }}</span>
        </Link>
    </li>
</template>

<style lang="sass" scoped>
.link-box
    display: flex
    justify-content: center
    margin: 6px 0

    .link
        position: relative
        display: flex
        align-items: center
        justify-content: center
        background-color: transparent
        width: 50px
        height: 50px
        border-radius: 12px
        transition: background 0.2s ease
        color: white

        svg
            width: 26px
            height: 26px

        &:hover
            background: var(--blue-button-background-color-hover)

    .link-label
        position: absolute
        left: 60px
        top: 50%
        transform: translateY(-50%) translateX(-10px)
        background: var(--blue-button-background-color-hover)
        color: #fff
        padding: 6px 12px
        border-radius: 6px
        font-size: 14px
        white-space: nowrap
        opacity: 0
        pointer-events: none
        z-index: 10000
        transition: opacity 0.25s ease, transform 0.25s ease

    .link:hover .link-label
        opacity: 1
        transform: translateY(-50%) translateX(0)
</style>
