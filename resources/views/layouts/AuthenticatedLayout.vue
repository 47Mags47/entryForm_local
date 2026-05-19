<script>
import { default as BaseLayout } from "./BaseLayout.vue";
import { default as Menu } from "../includes/menu/Menu.vue";

export default {
    components: {
        BaseLayout,
        Menu,
    },
};
</script>

<template>
    <BaseLayout name="authenticated-layout">
        <Menu />
        <main class="main-content">
            <slot />
        </main>
    </BaseLayout>
</template>

<style lang="sass" scoped>
.authenticated-layout
    position: relative
    display: flex
    height: 100vh
    width: 100vw
    overflow: hidden
    background: #ffffffff


.main-content
    flex: 1
    background: #ffffffff
    overflow: auto
    @include scroll()
</style>
