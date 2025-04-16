<script setup>
import Filter from './Filter.vue';

const statusAssignLimit = ref("result: 2")
const statusFilterResult = ref("FOLLOWUP = 2")

const manualAssign = () => {
    
}

</script>
<template>
    <div>
        <div class="v-card-title">Manual Assign 2</div>
        <VRow class="my-4">
            <VCol cols="12" md="3">
                <Filter />
            </VCol>
            <VCol cols="12" md="6">
                <div class="mx-2">
                    <VAlert
                        variant="outlined"
                        color="error">
                        Note: This action will replace on assigned data, if data assigned, and each time will limit 200 result
                    </VAlert>

                    <VSelect
                        class="mt-4"
                        :items="users"
                        variant="outlined"
                        label="Assign Data To"
                    />

                    <VTextField
                        class="mt-4"
                        label="Assign Limit"
                    />

                    <div 
                        v-if="statusAssignLimit.length > 0"
                        class="text-right" 
                        style="color: rgb(var(--v-theme-error))">
                        {{ statusAssignLimit }}
                    </div>

                    <VBtn 
                        class="mt-4"
                        @click="manualAssign">
                        Assign
                    </VBtn>
                </div>
            </VCol>
            <VCol cols="12" md="3">
                <VAlert
                    v-if="statusFilterResult.length > 0"
                    variant="outlined"
                    color="error">
                    {{ statusFilterResult }}
                </VAlert>
            </VCol>
        </VRow>
    </div>
</template>
