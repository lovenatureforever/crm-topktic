<script setup>
import CreateDialog from '@/components/users/CreateDialog.vue';
import FilterUser from '@/components/users/FilterUser.vue';
import { defaultUser, tableHeaders, tableOption } from '@/plugins/constant';
import axios from '@axios';
import { VDataTable } from 'vuetify/labs/VDataTable';

const headers = tableHeaders.user

const data = ref([])
const filteredData = ref([])

const options = ref(tableOption)
const editUser = ref(defaultUser)

const isUpdate = ref(false)
const showDialog = ref(false)
const deleteDialog = ref(false)
const deletedUserId = ref(-1)

const resetPasswordDialog = ref(false)
const resetUserId = ref(-1)

const resolveStatusVariant = (status) => {
  if (status === 'active')
    return { color: 'success', text: status }
  else 
    return { color: 'secondary', text: status }
}

const openCreateDialog = () => {
    showDialog.value = true
    isUpdate.value = false
    editUser.value = defaultUser
}

const closeCreateDialog = (changed) => {
    showDialog.value = false
    editUser.value = defaultUser
    if (changed) {
        getUserList();
    }
}

const editUserDialog = (id) => {
    axios.get(`/users/${id}`)
        .then((res) => {
            const { user } = res.data
            
            editUser.value = user
            showDialog.value = true
            isUpdate.value = true
        })
}

const deleteUserDialog = (id) => {
    deleteDialog.value = true
    deletedUserId.value = id
}

const closeDelete = () => {
    deleteDialog.value = false
    deletedUserId.value = -1
}

const deleteItemConfirm = () => {
    axios.delete(`/users/${deletedUserId.value}`)
        .then((res) => {
            const { users } = res.data
            data.value = users
            filteredData.value = users
            deleteDialog.value = false
            deletedUserId.value = -1
        })    
}


const openResetPasswordDialog = (id) => {
    resetPasswordDialog.value = true
    resetUserId.value = id
}

const closePassword = () => {
    resetPasswordDialog.value = false
    resetUserId.value = -1
}

const resetPasswordConfirm = () => {
    axios.put(`/users/${resetUserId.value}/reset-password`)
        .then((res) => {
            resetPasswordDialog.value = false
            resetUserId.value = -1
        })    
}

const getUserList = () => {
    axios.get('/users')
        .then((res) => {
            const { users } = res.data
            data.value = users
            filteredData.value = users 
        })
}

const activate = (id) => {
  axios.put(`/users/activate/${id}`)
  .then(res => {
    const { users } = res.data
    data.value = users
    filteredData.value = users 
  })
}

const applyFilter = (filter) => {
    let _data = data.value
    if (filter.id != "") {
        _data = _data.filter((item) => item.id == filter.id)
    }

    if (filter.name != "") {
        _data = _data.filter((item) => item.username.includes(filter.name))
    }

    if (filter.role != "All") {
        _data = _data.filter((item) => item.role == filter.role)
    }

    if (filter.status != "All") {
        _data = _data.filter((item) => item.status == filter.status)
    }
    
    filteredData.value = [..._data]
}

onMounted(() => {
    getUserList();
});

</script>
<template>
    <div>
        <FilterUser 
            @apply-filter="applyFilter"/>

        <VCard class="mt-4">
            <VCardItem>
                <VCardTitle>User List</VCardTitle>
                <template #append>
                    <VBtn
                        @click="openCreateDialog"
                        size="small"
                        icon="tabler-plus">
                    </VBtn>
                </template>
            </VCardItem>
            <VCardText>
                <VDataTable
                    :headers="headers"
                    :items="filteredData"
                    :items-per-page="options.itemsPerPage"
                    :page="options.page"
                    @update:options="options = $event">

                    <!-- status -->
                    <template #item.status="{ item }">
                        <VChip
                            :color="resolveStatusVariant(item.raw.status).color"
                            @click="activate(item.raw.id)"
                            size="small">
                            {{ resolveStatusVariant(item.raw.status).text }}
                        </VChip>
                    </template>

                    <template #item.team_leader="{ item }">
                        {{item.raw.leader ? item.raw.leader.name : ""}}
                    </template>

                    <template #item.created_at="{ item }">
                        {{new Date(item.raw.created_at).toISOString().slice(0, 19).replace('T', ' ')}}
                    </template>

                    <!-- Actions -->
                    <template #item.actions="{ item }">
                        <div class="d-flex gap-1">
                            <IconBtn @click="editUserDialog(item.raw.id)">
                                <VIcon icon="mdi-pencil-outline" />
                            </IconBtn>
                            <IconBtn @click="deleteUserDialog(item.raw.id)">
                                <VIcon icon="mdi-delete-outline" />
                            </IconBtn>                            
                            <VBtn rounded @click="openResetPasswordDialog(item.raw.id)">
                                Reset Password
                            </VBtn>
                        </div>
                    </template>
                    
                    <template #bottom>
                        <VCardText class="mt-8">
                            <VRow>
                                <VCol
                                    lg="2"
                                    cols="3">

                                    <VTextField
                                        v-model="options.itemsPerPage"
                                        label="Rows per page:"
                                        type="number"
                                        min="-1"
                                        max="15"
                                        hide-details
                                        />

                                </VCol>

                                <VCol
                                    lg="10"
                                    cols="9"
                                    class="d-flex justify-end"
                                >
                                    <VPagination
                                    v-model="options.page"
                                    total-visible="5"
                                    :length="Math.ceil(data.length / options.itemsPerPage)"
                                    />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </template>
                </VDataTable>
                <CreateDialog 
                    :isDialogVisible="showDialog"
                    :user = "editUser"
                    :isUpdate = "isUpdate"
                    @close-dialog="closeCreateDialog" />

                <VDialog
                    v-model="deleteDialog"
                    max-width="500px">
                    <VCard>
                        <VCardTitle class="px-8 py-8">
                            Are you sure you want to delete this user?
                        </VCardTitle>

                        <VCardActions>
                            <VSpacer />

                            <VBtn
                                color="error"
                                variant="outlined"
                                @click="closeDelete"
                            >
                                Cancel
                            </VBtn>

                            <VBtn
                                color="success"
                                variant="elevated"
                                @click="deleteItemConfirm"
                            >
                                OK
                            </VBtn>

                            <VSpacer />
                        </VCardActions>
                    </VCard>
                </VDialog>

                <VDialog
                    v-model="resetPasswordDialog"
                    max-width="600px">
                    <VCard>
                        <VCardTitle class="text-center px-8 py-8">
                            Are you sure you want to reset password<br/> of this user (Password: 123456)?
                        </VCardTitle>
                        <div>
                          
                        </div>

                        <VCardActions>
                            <VSpacer />

                            <VBtn
                                color="error"
                                variant="outlined"
                                @click="closePassword"
                            >
                                Cancel
                            </VBtn>

                            <VBtn
                                color="success"
                                variant="elevated"
                                @click="resetPasswordConfirm"
                            >
                                OK
                            </VBtn>

                            <VSpacer />
                        </VCardActions>
                    </VCard>
                </VDialog>
            </VCardText>
        </VCard>
    </div>
</template>
