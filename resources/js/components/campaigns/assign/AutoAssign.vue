<script setup>
import { isAdmin } from '@/plugins/auth';
import axios from '@axios';

const emit = defineEmits([
    'update-action'
])

const available = ref(0)
const total = ref(0) 
const availablePercent = ref(0)

const route = useRoute()
const router = useRouter()
const id = parseInt(route.params.id);

const error = ref('')
const isError = computed(() => error.value.length > 0)

const methods = [
    'Normal',
    'Random'
]

const leadersList = ref([])
const leader = ref(null)
const method = ref("Normal")
const amount = ref(0)

const applyAutoAssign = () => {
    const _leader = leader.value
    const _method = method.value
    const _amount = amount.value
    if (_leader == null || _leader == '') {
        error.value = 'Please select a team leader'
        return 
    }

    if (_method == null || _method == '') {
        error.value = 'Please select a assign method'
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
        method: _method,
        amount: _amount 
    }

    axios.post(`/admin/campaigns/${id}/assign`, param)
        .then(res => {
            getUnassignedCount();
        })
}

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

const getUnassignedCount = () => {
    axios.get(`/admin/campaigns/${id}/count-unassigned`)
        .then(res => {
            const { unassigned_count , total_count} = res.data
            available.value = unassigned_count
            total.value = total_count
            if (total_count != 0)
                availablePercent.value = 100 - unassigned_count * 100 / total_count
        })
}

const navigateMain = () => {
    emit('update-action', 'list')
}

onMounted(() => {
    getLeadersList();
    getUnassignedCount();
})

</script>
<template>
    <div>
        <div class="v-card-title">Auto Assign</div>
        <VRow class="my-4">
            <VCol cols="12" md="3" />
            <VCol cols="12" md="6">
                <VAlert
                    class="mt-4"
                    variant="outlined"
                    color="error">
                    Note: This will apply on unassigned data
                </VAlert>

                <div class="mt-4 font-weight-bold">
                    <small>Assigned Count</small>
                </div>

                <VProgressLinear
                    color="primary"
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
                    label="Assign Data To"
                />

                <AppSelect
                    class="mt-4"
                    :items="methods"
                    v-model="method"
                    label="Method"
                />

                <AppTextField
                    class="mt-4"
                    label="Amount"
                    type="number"
                    v-model="amount"
                />
                
                <VBtn 
                    class="mr-4 my-4"
                    color="error"
                    @click="navigateMain">
                    Back to Main
                </VBtn>

                <VBtn 
                    class="my-4"
                    @click="applyAutoAssign">
                    Assign
                </VBtn>
            </VCol>
            <VCol cols="12" md="3" />
            <VSnackbar
                v-model="isError"
                :timeout="1000"
                location="top end"
                color="error"
            >
                {{ error }}
            </VSnackbar>
        </VRow>
    </div>
</template>