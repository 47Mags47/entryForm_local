<script>
import { usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";

export default {
    components: {
        AuthenticatedLayout,
        DivisionTab,
    },

    data() {
        const subscribe = usePage().props.subscribe.data;
        const user_role = usePage().props.current_user.data.role.code;
        const division = usePage().props.division.data;
        return {
            subscribe,
            user_role,
            division,
        };
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="subscribes">
            <div class="subscribe-show">
                <div class="cards-container">
                    <div class="card">
                        <h2 class="card-title">Данные клиента</h2>
                        <div class="card-content">
                            <p>
                                <strong>ФИО:</strong> {{ subscribe.last_name }}
                                {{ subscribe.first_name }}
                                {{ subscribe.middle_name }}
                            </p>
                            <p><strong>Email:</strong> {{ subscribe.email }}</p>
                            <p>
                                <strong>Телефон:</strong> {{ subscribe.phone }}
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <h2 class="card-title">Данные обращения</h2>
                        <div class="card-content">
                            <p v-if="['admin'].includes(user_role)">
                                <strong>Подразделение:</strong>
                                {{ subscribe.division?.name }}
                            </p>
                            <p>
                                <strong>Специалист:</strong>
                                {{ subscribe.worker.name }}
                            </p>
                            <p>
                                <strong>Услуга:</strong>
                                {{ subscribe.service.name }}
                            </p>
                            <p>
                                <strong>Время начала:</strong>
                                {{ subscribe.start_at }}
                            </p>
                            <p>
                                <strong>Комментарий:</strong>
                                {{ subscribe.comment }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

<style scoped>
.subscribe-show {
    display: flex;
    flex-direction: column;
    gap: 24px;
    padding: 20px;
    background: #f9f9fb;
    min-height: 100vh;
}

.cards-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    width: 100%;
}

.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.2s ease;
    border: 1px solid #eaeaea;
    display: flex;
    flex-direction: column;
}

.card-title {
    margin: 0;
    padding: 18px 24px;
    background: var(--palette-color-4);
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    border-bottom: 1px solid #e0e0e0;
}

.card-content {
    padding: 20px 24px;
    color: #333;
    line-height: 1.6;
    flex: 1;
}

.card-content p {
    margin: 8px 0;
    display: flex;
    align-items: baseline;
    gap: 8px;
}

.card-content strong {
    min-width: 140px;
    color: #2c3e50;
    font-weight: 600;
}
</style>
