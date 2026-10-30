<script>
import { usePage } from "@inertiajs/vue3";
import { default as ItemMenu } from "./ItemMenu.vue";
import { default as HomeIco } from "../../components/icons/HomeIco.vue";
import { default as BuildingsIco } from "../../components/icons/BuildingsIco.vue";
import { default as CollectionIco } from "../../components/icons/CollectionIco.vue";
import { default as LogoutIco } from "../../components/icons/LogoutIco.vue";
import { default as PersonIco } from "../../components/icons/PersonIco.vue";
import ListIco from "../../components/icons/ListIco.vue";

export default {
    components: {
        ItemMenu,
        HomeIco,
        BuildingsIco,
        ListIco,
        CollectionIco,
        LogoutIco,
        PersonIco,
    },
    data() {
        const current_user = usePage().props.current_user?.data;
        return {
            current_user,
        };
    },

    methods: {
        hasRole(code){
            return this.current_user?.roles.filter((role) => role.role.code === code).length > 0
        }
    },

    computed: {
        isChristmas() {
            const now = new Date()
            const month = now.getMonth()
            const day = now.getDate()

            return month >= 11 || (month === 0 && day <= 10)
        },
    },
};
</script>

<template>
    <nav class="sidebar">
        <div v-if="isChristmas" class="christmas-garland">
            <span v-for="n in 20" :key="n" :style="{ '--i': n }"></span>
        </div>

        <ul class="sidebar-menu">
            <ItemMenu :href="route('divisions.index')" label="Подразделения">
                <HomeIco />
            </ItemMenu>

            <ItemMenu v-if="hasRole('admin')" :href="route('cities.index')" label="Города">
                <BuildingsIco />
            </ItemMenu>

            <ItemMenu v-if="hasRole('admin')" :href="route('services.index')" label="Услуги">
                <ListIco />
            </ItemMenu>

            <ItemMenu v-if="hasRole('admin')" :href="route('division-group.index')" label="Группы подразделений">
                <CollectionIco />
            </ItemMenu>

            <ItemMenu :href="route('user.show', { user: current_user?.id })" label="Личный Кабинет">
                <PersonIco />
            </ItemMenu>
        </ul>

        <div class="sidebar-footer">
            <ItemMenu :href="route('logout')" method="post" label="Выход">
                <LogoutIco />
            </ItemMenu>
        </div>
    </nav>
</template>

<style lang="sass" scoped>
.sidebar
    position: relative
    width: 50px
    background: var(--palette-color-4)
    color: #FFF
    display: flex
    flex-direction: column

    &-menu
        margin: 0
        padding: 0 0 8px 0
        list-style: none

    &-menu > *:not(:last-child)
        margin-bottom: 4px

    &-footer
        margin-top: auto
        padding-bottom: 8px

    .christmas-garland
        position: absolute
        top: 0
        right: -5px
        bottom: 0
        width: 10px
        z-index: 11
        pointer-events: none

        &::before
            content: ''
            position: absolute
            top: 0
            bottom: 0
            left: 4px
            width: 2px
            background: #263b2c

        span
            position: absolute
            top: calc((var(--i) - 1) * 100% / 19)
            left: 1px
            width: 7px
            height: 7px
            border-radius: 50%
            animation: garland-twinkle 1.4s ease-in-out infinite alternate
            animation-delay: calc(var(--i) * -.19s)
            box-shadow: 0 0 5px 2px currentColor, 0 0 10px currentColor

            &:nth-child(4n + 1)
                background: #ff4242
                color: #ff4242

            &:nth-child(4n + 2)
                background: #ffd966
                color: #ffd966

            &:nth-child(4n + 3)
                background: #4dff9a
                color: #4dff9a

            &:nth-child(4n)
                background: #72baff
                color: #72baff

@keyframes garland-twinkle
    from
        opacity: .4
        transform: scale(.8)
        filter: brightness(.8)

    to
        opacity: 1
        transform: scale(1.1)
        filter: brightness(1.5)
</style>
