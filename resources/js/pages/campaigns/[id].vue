<template>
  <div>
    <VCard>
      <VCardItem>
        <VCardTitle>Campaign Detail (Total: {{ totalFilteredCount }})</VCardTitle>
        <template v-if="isAdminOrTeamLeader()" #append>
          <div class="d-flex align-end justify-end">

            <ActionButton 
              class="mr-2"
              menuTitle="Assign" 
              @update-action="setAction"
              :menuList="assignList"
            />

            <ActionButton 
              class="mr-2"
              menuTitle="Lead Progress" 
              @update-action="setAction"
              :menuList="progressList" 
            />

            <VBtn 
              class="mr-2"
              color="success"
              rounded="pill"
              @click="() => setAction('list')">
              Manage
            </VBtn>
          </div>
        </template>
      </VCardItem>
      <VCardText>
        <div v-if="action == 'auto-assign'">
          <AutoAssign @update-action="setAction"/>
        </div>

        <div v-if="action == 'manual-assign'">
          <ManualAssign @update-action="setAction"/>
        </div>

        <div v-if="action == 'manual-assign2'">
          <ManualAssign2 />
        </div>

        <div v-if="action == 'list'">
          <ListWithFilter :campaign_id="id" />
        </div>

        <div v-if="action == 'accumulate'">
          <AccumulateList />
        </div>

        <div v-if="action == 'today'">
          <DateAccumulateList />
        </div>

        <div v-if="action == 'blocking'">
          <Blocking />
        </div>

      </VCardText>
    </VCard>
  </div>
</template>
<script setup>
import ActionButton from "@/components/campaigns/ActionButton.vue";
import Blocking from "@/components/campaigns/Blocking.vue";
import AutoAssign from "@/components/campaigns/assign/AutoAssign.vue";
import ManualAssign from "@/components/campaigns/assign/ManualAssign.vue";
import ListWithFilter from "@/components/campaigns/list/ListWithFilter.vue";
import AccumulateList from "@/components/campaigns/statistics/AccumulateList.vue";
import DateAccumulateList from "@/components/campaigns/statistics/DateAccumulateList.vue";
import { isAdminOrTeamLeader } from "@/plugins/auth";
import axios from '@axios';

const assignList = [
  {
    title: 'Auto Assign',
    value: 'auto-assign',
  },
  {
    title: 'Manual Assign',
    value: 'manual-assign',
  },
]

const progressList = [
  {
    title: 'Accumulate',
    value: 'accumulate',
  },
  {
    title: 'Today',
    value: 'today',
  }
]

const route = useRoute()
const id = parseInt(route.params.id);
const action = ref("list")
const totalFilteredCount = ref(0)

const setAction = (name) => {
  action.value = name;
}

const getTotalFilteredCount = () => {
  axios.get(`/campaign-detail/${id}/total-filtered-count`) 
  .then(res => {
    const {total} = res.data
    totalFilteredCount.value = total
  }) 
}

onMounted(() => {
  getTotalFilteredCount(); 
})


</script>

<style>
.w-full {
  width: 100%;
}
.app-picker-field {
  width: 250px;
}
</style>
