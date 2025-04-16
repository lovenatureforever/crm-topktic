
<script setup>
import { VDataTable } from 'vuetify/labs/VDataTable';

const props = defineProps({
  data: {
    type: Array,
    required: true,
  },
})

const headers = [
    {key: "name", title: "Name"},
    {key: "INEFFECTIVE", title: "INEFFECTIVE"},
    {key: "REJECT", title: "REJECT"},
    {key: "SUCCESS", title: "SUCCESS (Consumer Plan)"},
    {key: "WINBACK", title: "WINBACK"},
    {key: "NEW_NEW", title: "NEW NEW"},
    {key: "TOTAL_CALLS	", title: "TOTAL CALLS"},
    {key: "CONVERSION_RATE", title: "CONVERSION RATE"},
]

</script>

<template>
    <div class="mt-4">
        <VDataTable
            :headers="headers"
            :items="data">
        </VDataTable>
    </div>
</template>