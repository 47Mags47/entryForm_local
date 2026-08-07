<script>
import { usePage } from "@inertiajs/vue3";
import { default as Tab } from "../../components/tab/Tab.vue";

export default {
    components: {
        Tab,
    },

    props: {
        current: String,
    },

    data() {
        const division = usePage().props.current_division.data
        const current_user = usePage().props.current_user.data

        const current_role = current_user.roles.filter((role) => role.division.id === division.id)[0]?.role ?? current_user.roles[0].role

        return {
            division,
            links: [
                {
                    index: "workers",
                    href: route("workers.index", {
                        division: division.id,
                    }),
                    title: "Сотрудники",
                    isActive: this.current === "workers",
                    hasAccess: current_role.code === 'admin' || current_role.code === 'division_admin',
                },
                {
                    title: "Генерация iFrame",
                    href: route("frame.index", {
                        division: division.id,
                    }),
                    isActive: this.current === "frame",
                    hasAccess: current_role.code === 'admin' || current_role.code === 'division_admin',
                },
                {
                    title: "Общая информация",
                    href: route("divisions.show", {
                        division: division.id,
                    }),
                    isActive: this.current === "info",
                    hasAccess: current_role.code === 'admin' || current_role.code === 'division_admin',
                },

                {
                    index: "event-calendar",
                    href: route("events.index", { division: division.id }),
                    title: "Календарь событий",
                    isActive: this.current === "event-calendar",
                    hasAccess: true,
                },
                {
                    index: "subscribes",
                    href: route("subscribes.index", {
                        division: division.id,
                    }),
                    title: "Обращения",
                    isActive: this.current === "subscribes",
                    hasAccess: true,
                },
            ],
        };
    },
};
</script>

<template>
    <Tab :links>
        <slot />
    </Tab>
</template>
