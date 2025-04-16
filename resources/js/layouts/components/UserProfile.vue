<script setup>
import { credential } from '@/plugins/auth';
import axios from '@axios';
import avatar1 from '@images/avatars/avatar-1.png';
const router = useRouter() 

const isDialogVisible = ref(false)
const password = ref({
  current: "",
  update: "",
  confirm: ""
})

const error = ref('')
const isError = computed(() => error.value.length > 0)
const isSuccess = ref(false)

const logout = () => {
  localStorage.clear()
  router.replace("/login")
}

const showChangePassword = () => {
  isDialogVisible.value = true
}

const closePasswordDialog = () => {
  isDialogVisible.value = false
}

const updatePassword = () => {
  const passwordObj = password.value
  if (passwordObj.current == "") {
    setErrorValue("Current Password must not be empty")
    return
  } else if (passwordObj.update == "") {
    setErrorValue("New Password must be not empty")
    return
  } else if (passwordObj.update != passwordObj.confirm) {
    setErrorValue("Password confirmation doesn't match")
    return
  }

  axios.post('/users/update-password', {
    password: passwordObj.current,
    new_password: passwordObj.update
  })
    .then(res => {
      const {message} = res.data
      if (message == "success") {
        isSuccess.value = true
        isDialogVisible.value = false
      } else {
        setErrorValue(res.data.error)
      }
    })
    .catch(err => {
      error.value = err
      isDialogVisible.value = false
    }) 

}

const setErrorValue = (msg) => {
  error.value = msg
  setTimeout(() => {
    error.value = ""
  }, 2000)
}


const account = credential();
</script>

<template>
  <div>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    bordered
    color="success"
  >
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
    >
      <VImg :src="avatar1" />

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
      >
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="avatar1" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ account.username }}
            </VListItemTitle>
            <VListItemSubtitle>{{ account.role }}</VListItemSubtitle>
          </VListItem>

          <!-- Divider -->
          <VDivider class="my-2" />

          <VListItem @click="showChangePassword">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="mdi-password"
                size="22"
              />
            </template>

            <VListItemTitle>Change Password</VListItemTitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem @click="logout">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="tabler-logout"
                size="22"
              />
            </template>

            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
  <VDialog
    v-model="isDialogVisible"
    persistent
    max-width="420"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="closePasswordDialog" />

    <!-- Dialog Content -->
    <VCard title="User Profile">
      <VCardText>
        <AvatarPicker />
        <VRow class="mt-4">
          <VCol
            cols="12"
          >
           <AppTextField
              v-model="password.current"
              label="Current Password"
              type="password"
            />
          </VCol>
          <VCol
            cols="12"
          >
           <AppTextField
              v-model="password.update"
              label="New Password"
              type="password"
            />
          </VCol>
          <VCol
            cols="12"
          >
           <AppTextField
              v-model="password.confirm"
              label="Password Confirm"
              type="password"
            />
          </VCol>
          <VCardText class="d-flex justify-end flex-wrap gap-3">
            <VBtn
              variant="tonal"
              color="secondary"
              @click="isDialogVisible = false"
            >
              Close
            </VBtn>
            <VBtn @click="updatePassword">
              Update
            </VBtn>
          </VCardText>
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
  <VSnackbar
      v-model="isError"
      :timeout="1000"
      location="top end"
      color="error"
    >
      {{ error }}
    </VSnackbar>
    <VSnackbar
      v-model="isSuccess"
      :timeout="1000"
      location="top end"
      color="success"
    >
        Password has been successfully updated.
    </VSnackbar>
  </div>
</template>
