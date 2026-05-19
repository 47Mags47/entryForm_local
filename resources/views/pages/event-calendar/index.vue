<script setup>
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { TimeLine } from "@components";
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="event-calendar">
            <TimeLine header="Записи на прием"/>
        </DivisionTab>
    </AuthenticatedLayout>
</template>
