<script>
import { Link } from "@inertiajs/vue3";

export default {
    components: {
        Link,
    },

    props: {
        links: Array,
    },
};
</script>

<template>
    <div class="tab-container">
        <ul class="nav">
            <li
                :class="{ 'tab-item': true, active: link.isActive }"
                v-for="(link, i) in links"
            >
                <Link
                    v-if="link.hasAccess"
                    :href="link.href"
                    :active="link.isActive"
                    :key="i"
                    class="tab-link"
                >
                    {{ link.title }}
                </Link>
            </li>
        </ul>
        <div class="tab-content">
            <slot />
        </div>
    </div>
</template>

<style lang="sass" scoped>
.tab-container
    width: 100%
    height: 100%
    display: flex
    flex-direction: column

    .nav
        display: flex
        height: 40px
        margin: 0
        padding: 0
        gap: 4px
        align-items: flex-end

        .tab-item
            list-style: none
            display: inline-block
            margin: 0 8px

            .tab-link
                display: inline-block
                padding: 10px 16px
                text-decoration: none
                color: #555
                border-radius: 6px 6px 0 0
                font-size: 14px
                transition: all 0.2s ease
                border-bottom: 3px solid transparent
                background: none
                border: none

                &:hover
                    color: var(--palette-color-4)
                    cursor: pointer

            &.active .tab-link
                color: var(--palette-color-4)
                font-weight: 600
                border-bottom: 3px solid var(--palette-color-4)

    .tab-content
        flex: 1
</style>
