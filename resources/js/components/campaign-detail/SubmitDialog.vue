<template>
    <VDialog
        v-model="isDialogVisible"
        persistent
        max-width="720"
    >
        <!-- Dialog close btn -->
        <DialogCloseBtn @click="close" />

        <!-- Dialog Content -->
        <VCard title="Submit to KTIC">
            <VCardText>
                <VRow class="mx-2">
                    <VCol>
                        <AppSelect
                            :items="productList"
                            v-model="submitInfo.product"
                            :label="submitInfoLabels.product"
                        />
                    </VCol>
                    <VCol>
                        <AppSelect
                            :items="packageList"
                            v-model="submitInfo.package"
                            :label="submitInfoLabels.package"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppSelect
                            :items="GENDERS"
                            v-model="submitInfo.gender"
                            :label="submitInfoLabels.gender"
                        />
                    </VCol>
                    <VCol>
                        <AppSelect
                            :items="RACE"
                            v-model="submitInfo.race"
                            :label="submitInfoLabels.race"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppTextField
                            :items="productList"
                            v-model="submitInfo.altNum"
                            :label="submitInfoLabels.altNum"
                        />
                    </VCol>
                    <VCol>
                        <AppSelect
                            :items="YES_NO"
                            v-model="submitInfo.existingUnifi"
                            :label="submitInfoLabels.existingUnifi"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.fullname"
                            :label="submitInfoLabels.fullname"
                        />
                    </VCol>
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.nric"
                            :label="submitInfoLabels.nric"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppSelect
                            :items="stateList"
                            v-model="submitInfo.state"
                            :label="submitInfoLabels.state"
                        />
                    </VCol>
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.address1"
                            :label="submitInfoLabels.address1"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppSelect
                            :items="areaList"
                            v-model="submitInfo.area"
                            :label="submitInfoLabels.area"
                        />
                    </VCol>
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.address2"
                            :label="submitInfoLabels.address2"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.postcode"
                            :label="submitInfoLabels.postcode"
                        />
                    </VCol>
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.address3"
                            :label="submitInfoLabels.address3"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.contact"
                            :label="submitInfoLabels.contact"
                        />
                    </VCol>
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.email"
                            :label="submitInfoLabels.email"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.citizen"
                            :label="submitInfoLabels.citizen"
                        />
                    </VCol>
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.agentcode"
                            :label="submitInfoLabels.agentcode"
                        />
                    </VCol>
                </VRow>
                <VRow class="mx-2">
                    <VCol>
                        <AppTextField
                            v-model="submitInfo.remark"
                            :label="submitInfoLabels.remark"
                        />
                    </VCol>
                </VRow>
            </VCardText>
            <VCardText class="d-flex justify-end flex-wrap gap-3">
                <VBtn
                    variant="tonal"
                    color="secondary"
                    @click="close"
                >
                    Close
                </VBtn>
                <VBtn @click="submit">
                    Submit
                </VBtn>
            </VCardText>
            <VSnackbar
                v-model="isError"
                :timeout="1000"
                location="top end"
                color="error"
            >
                {{ error }}
            </VSnackbar>
        </VCard>
    </VDialog>
</template>
<script setup>
import { GENDERS, RACE, YES_NO, defaultSubmitInfo, submitInfoLabels } from '@/plugins/constant';
import axios from 'axios';
import { onMounted, toRef, watch } from 'vue';

const API_URL = import.meta.env.VITE_API_URL;

const props = defineProps({
    isDialogVisible: {
        type: Boolean,
        required: true,
    }
})

const isDialogVisible = toRef(props, 'isDialogVisible')
const submitInfo = ref(defaultSubmitInfo)

const stateList = ref([])
const areaList = ref([])
const productList = ref([])
const packageList = ref([])

const isError = computed(() => error.value.length > 0)
const error = ref('')

const emit = defineEmits([
    'close-dialog',
    'update-record'
]);

const close = () => {
    emit('close-dialog')
}

watch(
    () => submitInfo.value.product,
    (newVal, oldVal) => {
        if (newVal != oldVal) {
            getAvailablePackageList();
        }        
    },
    { deep: true }
)

watch(
    () => submitInfo.value.state,
    (newVal, oldVal) => {
        if (newVal != oldVal) {
            getAreaList();
        }        
    },
    { deep: true }
);

const getStateList = () => {
    const param = {
        api_id: import.meta.env.VITE_API_ID,
        api_token: import.meta.env.VITE_API_TOKEN,
    }
    axios.get(`${API_URL}/api_state_list.php`, {
        params: param
    })
        .then(res => {
            const data = res.data
            if (!data.error) {
                stateList.value = data.map(item => item.state_name)
            } else {
                stateList.value = []
            }
        })
}

const getAreaList = () => {
    const param = {
        api_id: import.meta.env.VITE_API_ID,
        api_token: import.meta.env.VITE_API_TOKEN,
        state_name: submitInfo.value.state
    }
    axios.get(`${API_URL}/api_area_list.php`,  {
        params: param
    })
        .then(res => {
            const data = res.data
            if (!data.error) {
                areaList.value = data.map(item => item.area)
            } else {
                areaList.value = []
            }
        })

}

const getProductList = () => {
    const param = {
        api_id: import.meta.env.VITE_API_ID,
        api_token: import.meta.env.VITE_API_TOKEN,
    }
    axios.get(`${API_URL}/api_available_products.php`,  {
        params: param
    })
        .then(res => {
            const data = res.data
            if (!data.error) {
                productList.value = data.map(item => item.product_name)
            } else {
                productList.value = []
            }
        })
}

const getAvailablePackageList = () => {
    const param = {
        api_id: import.meta.env.VITE_API_ID,
        api_token: import.meta.env.VITE_API_TOKEN,
        product_name: submitInfo.value.product 
    }
    axios.get(`${API_URL}/api_available_package.php`,  {
        params: param
    })
        .then(res => {
            const data = res.data
            if (!data.error) {
                packageList.value = data.map(item => item.package_name)
            } else {
                packageList.value = []
            }
        })
}

const submit = () => {
    const param = submitInfo.value
    for (const key of Object.keys(param)) {
        if (key == 'address3')
            continue

        if (key == 'remark')
            continue

        if (param[key] == '' || !param[key]) {
            error.value = `'${submitInfoLabels[key]}' must not be empty!`
            return
        }
    }

    const postParam = {
        api_id: import.meta.env.VITE_API_ID,
        api_token: import.meta.env.VITE_API_TOKEN,
        product_name: param.product, 
        package_name: param.package, 
        customer_name: param.fullname, 
        nric: param.nric,
        citizen: param.citizen,
        address: `${param.address1} ${param.address2} ${param.address3}`,
        postcode: param.postcode, 
        state_name: param.state, 
        area: param.area, 
        contact: param.contact, 
        email: param.email, 
        agentcode: param.agentcode,
        gender: param.gender,
        race: param.race,
        existing_unifi: param.existingUnifi,
        alt_num: param.altNum,
        remark: param.remark,        
    }

    axios.post(
        `${API_URL}/casecrm_submitorder.php`, 
        new URLSearchParams(postParam).toString(), 
        {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }
    )
        .then(res => {
            const data = res.data
            if (data instanceof Array) {
                const firstItem = data[0];
                if (firstItem.status == 0) {
                    delete firstItem["status"]
                    const errKey = Object.keys(firstItem)[0]
                    error.value = firstItem[errKey] 
                } else {
                    if (firstItem.status == 1 && firstItem['ref_number'])
                        emit('update-record', firstItem['ref_number'])
                }
            }         
        })
}

onMounted(() => {
    getStateList();
    getProductList();
})

</script>