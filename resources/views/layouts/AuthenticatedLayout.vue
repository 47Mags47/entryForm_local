<script>
import { default as BaseLayout } from "./BaseLayout.vue";
import { default as Menu } from "../includes/menu/Menu.vue";
import { router } from "@inertiajs/vue3";
import { SpinnerIco } from "@components";

export default {
    components: {
        BaseLayout,
        Menu,
        SpinnerIco
    },

    data() {
        return {
            isLoading: false,
        }
    },

    mounted() {
        router.on('start', () => {
            this.isLoading = true;
        });

        router.on('finish', () => {
            this.isLoading = false;
        });
    },
};
</script>

<template>
    <BaseLayout name="authenticated-layout">
        <Menu />
        <div v-if="isLoading" class="loading-ico-wrapper">
            <SpinnerIco />
        </div>
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

    .loading-ico-wrapper
        position: fixed
        display: flex
        justify-content: center
        align-items: center

        width: 100%
        height: 100%

        z-index: 1000

        backdrop-filter: blur(1px)

    .main-content
        flex: 1
        background: #ffffffff
        overflow: auto
        @include scroll()
</style>
