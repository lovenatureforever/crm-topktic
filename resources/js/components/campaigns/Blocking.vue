
<template>
    <div>
        <div class="v-card-title">Blocking</div>
        <div class="mt-2">
            <VRow>
                <VCol cols="12" md="4" />
                <VCol cols="12" md="4" >
                    <div 
                        class="d-flex align-center mt-4">
                        <div
                            class="text-high-emphasis fixed-label font-weight-bold">
                            Name
                        </div>

                        <div
                            class="text-high-emphasis fixed-label font-weight-bold">
                            Status
                        </div>                    
                    </div>
                    <div 
                        v-for="(agent, index) in agents"
                        :key="index"
                        class="d-flex align-center mt-4">
                        <VLabel
                            :for="elementId"
                            class="text-high-emphasis fixed-label"
                            :text="`(${agent.id}) ${agent.name}`"
                        />
                        <AppSelect 
                            :items="items"
                        />
                    </div>
                    <div v-if="agents.length == 0">
                        There are no agents.
                    </div>
                    <div class="mt-6 d-flex justify-center">
                        <VBtn>
                            Apply
                        </VBtn>
                    </div>
                </VCol>
                <VCol cols="12" md="4" />
            </VRow>
        </div>
    </div>
</template>
<script setup>
import AppSelect from '@/@core/components/app-form-elements/AppSelect.vue';
const agents = ref([
    {id: 1952, name: "NICOLE"},
    {id: 1953, name: "JENNY"},
    {id: 1954, name: "SOPHIE"},    
])
const items = ["Allow", "Block"]

</script>
<style>
.fixed-label {
    width: 150px;
    margin: 0 20px;
    text-align: right !important;
}
</style>