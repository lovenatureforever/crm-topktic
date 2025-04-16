<script setup>
import axios from '@axios';

const props = defineProps({
    isDialogVisible: {
        type: Boolean,
        required: true
    },
    campaign: {
        type: Object,
        required: true
    }
});

const isDialogVisible = toRef(props, 'isDialogVisible')
const uploadingInProgress = ref(false)
const importFile = ref(null)

const emit = defineEmits([
    'close-dialog'
])

const close = () => {
    emit('close-dialog')
}

const importCampaignData = () => {
    const campaign = props.campaign
    if (!campaign.id) 
        return

    const files = importFile.value
    if (files.length == 0)
        return
    
    uploadingInProgress.value = true
    const formData = new FormData();
    formData.append('csv', files[0]);

    axios.post(`/admin/campaigns/${campaign.id}/import`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(res => {
            finish()
        })
        .catch(err => {
            finish()
        });
}

const finish = () => {
    uploadingInProgress.value = false
    close();
}
</script>
<template>
    <VDialog
        v-model="isDialogVisible"
        max-width="600">
    
        <DialogCloseBtn @click="close" />

        <VCard title="Import Campaign Detail Data">
            <VCardText class="px-8">
                <VRow>
                    <VCol cols="12">
                        <b>Campaign: </b> 
                        <span class="ml-2 text-success">{{ props.campaign.name }}</span>
                    </VCol>
                </VRow>
                <VRow class="mt-4">
                    <VCol cols="12">
                        <VFileInput
                            accept="text/csv"
                            label="Select Import File(csv)"
                            :multiple="false"
                            v-model="importFile"
                        />
                    </VCol>
                </VRow>
                <VRow class="mt-4" v-show="uploadingInProgress">
                    <VCol cols="12">
                        <VProgressLinear 
                            indeterminate
                            color="primary" />
                    </VCol>
                </VRow>
            </VCardText>

            <VCardText class="d-flex justify-end flex-wrap gap-3">
                <VBtn
                    variant="tonal"
                    color="secondary"
                    @click="close">
                    Close
                </VBtn>

                <VBtn @click="importCampaignData">
                    Import Data
                </VBtn>
            </VCardText>
        </VCard>
  </VDialog>
</template>