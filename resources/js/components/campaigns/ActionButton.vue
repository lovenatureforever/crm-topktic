<script setup>
const props = defineProps({
  menuList: {
    type: Array,
    required: false,
  },
  menuTitle: {
    type: String,
    required: true,
  },
  itemProps: {
    type: Boolean,
    required: false,
  },
})

const emit = defineEmits([
  'update-action',
])

const handleClick = (value) => {
    emit('update-action', value);
}

</script>

<template>
  <VBtn
    color="primary"
    rounded="pill"
  >
    <span>{{ menuTitle }}</span>
    <VIcon icon="tabler-chevron-down" />

    <VMenu
      v-if="props.menuList"
      activator="parent">

      <VList>
        <VListItem 
            v-for="(menu, index) in props.menuList"
            @click="handleClick(menu.value)">

            {{ menu.title }}
        
        </VListItem>
      </VList>

    </VMenu>
  </VBtn>
</template>