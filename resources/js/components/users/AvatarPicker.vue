<template>
    <div class="text-center">
        <div class="text-center mb-2">Select Photo</div>
        <VBadge @click="openPhotoSelector">
            <template #badge>
                <VIcon icon="tabler-camera" />
            </template>

            <img class="photoPreview" v-if="previewImage" :src="previewImage" />
            <img class="photoPreview" v-else :src="avatar1"/>
        </VBadge>
        <input class="photo-selector" ref="photoSelector" type="file" @change="handleFileUpload" />
    </div>
</template>
  
<script setup>
import avatar1 from '@images/avatars/avatar-1.png';
import { ref } from 'vue';
  
const photoSelector = ref(null)
const previewImage = ref(null);
  
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    previewImage.value = URL.createObjectURL(file);
};

const openPhotoSelector = () => {
    photoSelector.value.click()
}

</script>
<style>
.photo-selector {
    display: none;
}
.photoPreview {
    width: 100px;
    height: 100px;
    border-radius: 20px;
}
</style>