<template>
    <VCard>
        <VCardItem>
            <VCardTitle>Filter </VCardTitle>
        </VCardItem>
        <VCardText>    
            <div class="d-flex align-center mx-4">
                <VTextField
                    v-model="filter.id"
                    label="Id"
                />

                <VTextField
                    class="ml-4"
                    v-model="filter.name"
                    label="Name"
                />       
                
                <VSelect
                    class="ml-4"
                    v-model="filter.role"
                    :items="roles"
                    variant="outlined"
                    label="Role"
                />

                <VSelect
                    class="ml-4"
                    v-model="filter.status"
                    :items="status"
                    variant="outlined"
                    label="Status"
                />

                <VBtn 
                    class="ml-4"
                    @click="applyFilter">
                    Apply
                </VBtn>
                
            </div>
        </VCardText>
    </VCard>
</template>
<script setup>
import { ALL, allStatus, rolesByManagerForFilter } from '@/plugins/constant'

const roles = rolesByManagerForFilter()
const status = allStatus

const filter = ref({
    id: "",
    name: "",
    role: ALL,
    status: ALL
})

const emit = defineEmits([
    'apply-filter'
])

const applyFilter = () => {
    emit('apply-filter', filter.value)
}

</script>