<script setup>
import { isAdmin } from '@/plugins/auth';
import axios from '@axios';
import Filter from './Filter.vue';

const available = ref(0)
const total = ref(0) 
const availablePercent = ref(0)

const filterStatus = ref()

const leadersList = ref([])
const leader = ref()
const amount = ref()

const error = ref('')
const isError = computed(() => error.value.length > 0)

const getLeadersList = () => {
    const url = isAdmin() ? '/users/leaders' : '/users/agents'
    
    axios.get(url)
        .then(res => {
            const { leaders } = res.data
            leadersList.value = leaders.map(leader => {
                return {
                    value: leader.id, 
                    title: leader.username
                }
            })
        })
}


const manualAssign = () => {
    const _leader = leader.value
    const _amount = amount.value
    if (_leader == null || _leader == '') {
        error.value = 'Please select a team leader'
        return 
    }

    if (_amount == 0) {
        error.value = 'Amount must not be 0'
        return 
    }

    if (_amount > available.value) {
        error.value = 'amount must be less than or equal to available count'
        return 
    }

    const param = {
        leader: _leader,
        method: 'Random',
        amount: _amount,
        ...(filterStatus.value) 
    }

    axios.post(`/admin/campaigns/${id}/assign-filter`, param)
        .then(res => {
            getUnassignedCount(filterStatus.value);
        })
}

const setCustomFilter = (filter) => {
    getUnassignedCount({...filter})
    filterStatus.value = filter
} 

const getUnassignedCount = (filter) => {
    axios.get(`/admin/campaigns/${id}/count-unassigned-filter`, {params: filter})
        .then(res => {
            const { unassigned_count , total_count} = res.data
            available.value = unassigned_count
            total.value = total_count
            if (total_count != 0)
                availablePercent.value = 100 - unassigned_count * 100 / total_count
        })
}

const route = useRoute()
const id = parseInt(route.params.id);

onMounted(() => {
    getLeadersList()
    getUnassignedCount({})
})

</script>
<template>
    <div>
        <div class="v-card-title">Manual Assign</div>
        <VRow class="my-4">
            <VCol cols="12" md="4">
                <Filter 
                    @set-custom-filter="setCustomFilter" />
            </VCol>
            <VCol cols="12" md="8">
                <div class="mx-2">
                    <VAlert
                        variant="outlined"
                        color="error">
                        Note: This action will replace on assigned data
                    </VAlert>

                    <h4 class="mt-6">Assigned Count</h4>
                    <VProgressLinear
                        color="primary"
                        class="mt-2"
                        v-model="availablePercent"
                        striped />

                    <div class="text-right">
                        <small class="font-weight-bold">unassigned {{ available }}</small> 
                        <small> / total {{ total }}</small>
                    </div>

                    <AppSelect
                        class="mt-4"
                        :items="leadersList"
                        v-model="leader"
                        variant="outlined"
                        label="Assign Data To"
                    />

                    <VTextField
                        class="mt-4"
                        v-model="amount"
                        label="Assign Limit"
                    />

                    <div class="text-right">
                        <VBtn 
                            class="mt-4"
                            @click="manualAssign">
                            Assign
                        </VBtn>
                    </div>
                </div>
            </VCol>
        </VRow>
        
        <VSnackbar
            v-model="isError"
            :timeout="1000"
            location="top end"
            color="error"
        >
            {{ error }}
        </VSnackbar>
    </div>
</template>