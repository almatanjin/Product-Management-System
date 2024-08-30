<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
      <h2 class="text-lg font-medium text-gray-900">Delete Item</h2>
      <p class="mt-2 text-sm text-gray-600">
        Are you sure you want to delete this item? This action cannot be undone.
      </p>
      <div class="mt-4 flex justify-end space-x-4">
        <button @click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
        <button @click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
});

const emits = defineEmits(['close', 'confirm']);

function closeModal() {
  emits('close');
}

function confirmDelete() {
  emits('confirm');
}
</script>

