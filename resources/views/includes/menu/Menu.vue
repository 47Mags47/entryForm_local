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
        const current_user = usePage().props.current_user.data;
        return {
            current_user,
        };
    },

    methods: {
        hasRole(code){
            return this.current_user.roles.filter((role) => role.role.code === code).length > 0
        }
    }
};
</script>

<template>
    <nav class="sidebar">
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

            <ItemMenu :href="route('user.show', { user: current_user.id })" label="Личный Кабинет">
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
</style>
