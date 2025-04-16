<script setup>
import { ROLE_AGENT, defaultUser, rolesByManager, user_states } from '@/plugins/constant.js';
import axios from '@axios';
import { toRef } from 'vue';
import AvatarPicker from './AvatarPicker.vue';

const props = defineProps({
    isDialogVisible: {
        type: Boolean,
        required: false
    },
    user: {
      type: Object,
      required: false
    },
    isUpdate: {
      type: Boolean,
      required: false
    }
})

const emit = defineEmits([
    'close-dialog'
])

const roles = rolesByManager()
const statuses = user_states

const leadersList = ref([])

const isDialogVisible = toRef(props, 'isDialogVisible')
const user = toRef(props, 'user')
const password_confirm = ref('')

const isError = computed(() => error.value.length > 0)
const enableLeaderSelect = computed(() => user.value.role == ROLE_AGENT)
const error = ref('')

const close = () => {
    emit('close-dialog', false)
}

const clearError = () => setTimeout(() => {error.value = ""}, 1500)

const save = () => {
  const _user = user.value
  if (_user.username.length == 0) {
    error.value = 'Username must be not empty.'
    clearError();
    return;
  }

  if (!/^[a-zA-Z0-9_]+$/.test(_user.username)) {
    error.value = "Username must contain alphanumeric and underscore characters only."
    clearError();
    return
  }

  if (_user.role == 'Agent') {
    if (_user.team_leader == 0 || _user.team_leader == "") {
      error.value = 'You must select team leader.'
      return;
    }
  }

  if (_user.status != 'active' && _user.status != 'inactive') {
    error.value = 'Status is wrong.'
    clearError();
    return;
  }
    
  if (!props.isUpdate && _user.password.length == 0) {
    error.value = 'Password must be not empty.'
    clearError();
    return;
  }

  if (!props.isUpdate && _user.password != password_confirm.value) {
    error.value = 'Password mismatch.'
    clearError();
    return;
  }

  if (props.isUpdate)
    axios.put('/users', _user)
      .then((res) => {
        emit('close-dialog', true)
        user.value = defaultUser
      })
      .catch((err)=> {
        if (err.response) {
          
        } else if (err.request) {
          
        } else {
          error.value = err.message
        }
      })
  else
    axios.post('/users', _user)
      .then((res) => {
        emit('close-dialog', true)
        user.value = defaultUser
      })
      .catch((err) => {
        if (err.response) {
        } else if (err.request) {
        } else {
          error.value = err.message
        }
      })

}

const getLeadersList = () => {
  axios.get('/users/leaders')
    .then(res => {
      const { leaders } = res.data
      leadersList.value = leaders.map(leader => {
        return {
          value: leader.id, 
          title: leader.name
        }
      })
    })
}

onMounted(() => {
  getLeadersList();
})

</script>

<template>
  
  <VDialog
    v-model="isDialogVisible"
    persistent
    max-width="720"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="close" />

    <!-- Dialog Content -->
    <VCard title="User Profile">
      <VCardText>
        <AvatarPicker />
        <VRow class="mt-4">
          <VCol
            cols="12"
            sm="6"
            md="4"
          >
            <AppTextField
              v-model="user.username"
              label="User Name"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="4"
          >
            <AppTextField
              v-model="user.name"
              label="Name"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="4"
          >
            <AppTextField
              v-model="user.email"
              label="Email"
              persistent-hint
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="4"
          >
            <AppSelect
              v-model="user.role"
              :items="roles"
              label="Role"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="4"
          >
            <AppSelect
              v-model="user.team_leader"
              v-if="enableLeaderSelect"
              :items="leadersList"
              label="Team Leader"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
            md="4"
          >
            <AppSelect
              v-model="user.status"
              :items="statuses"
              label="Status"
            />
          </VCol>
          <VCol 
            v-if="!props.isUpdate"
            cols="12"
            sm="6">
            <AppTextField
              v-model="user.password"
              label="Password"
              type="password"
            />
          </VCol>
          <VCol 
            v-if="!props.isUpdate"
            cols="12"
            sm="6">
            <AppTextField
              v-model="password_confirm"
              label="Password Confirmation"
              type="password"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
          >
            <AppTextField
              v-model="user.caller_id"
              label="Caller ID"
              type="number"
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
          >
            <AppTextField
              v-model="user.contact_number"
              label="Contact Number"
              type="text"
            />
          </VCol>
        </VRow>
      </VCardText>

      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn
          variant="tonal"
          color="secondary"
          @click="isDialogVisible = false"
        >
          Close
        </VBtn>
        <VBtn @click="save">
          Save
        </VBtn>
      </VCardText>
    </VCard>
    <VSnackbar
      v-model="isError"
      :timeout="1000"
      location="top end"
      color="error"
    >
      {{ error }}
    </VSnackbar>
  </VDialog>
</template>
