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
        const division = usePage().props.division.data;
        const user_role = usePage().props.current_user.data.role.code;
        const hasAdmins = ["admin", "division_admin"].includes(user_role);
        const hasDivisionAdmins = ["division_admin"].includes(user_role);

        return {
            division,
            user_role,
            hasAdmins,
            links: [
                {
                    title: "Общая информация",
                    href: route("divisions.show", {
                        division: division.id,
                    }),
                    isActive: this.current === "info",
                    hasAccess: hasAdmins,
                },
                {
                    index: "admins",
                    href: route("division-admins.index", {
                        division: division.id,
                    }),
                    title: "Адмистраторы",
                    isActive: this.current === "admins",
                    hasAccess: hasAdmins,
                },
                {
                    index: "workers",
                    href: route("workers.index", {
                        division: division.id,
                    }),
                    title: "Сотрудники",
                    isActive: this.current === "workers",
                    hasAccess: hasAdmins,
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
                {
                    title: "Генерация iFrame",
                    href: route("frame.index", {
                        division: division.id,
                    }),
                    isActive: this.current === "frame",
                    hasAccess: hasDivisionAdmins,
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
