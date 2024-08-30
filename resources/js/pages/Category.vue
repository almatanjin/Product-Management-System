<template>
    <div class="p-6 mx-auto flex justify-center items-center mt-14">
        <table class="w-[70%] divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in items" :key="item.id">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
              <button 
                @click="deleteItem(item.id)" 
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <DeleteModal
                    :isOpen="isModalOpen"
                    @close="isModalOpen = false"
                    @confirm="handleDelete"
                />
    </div>
  </template>
  
  <script lang="ts" setup>
  import axios from 'axios';
import { ref } from 'vue';
import DeleteModal from '../components/DeleteModal.vue';
import { useRouter } from "vue-router";

  interface Item {
    id: number;
    name: string;
  }

  const items = ref<Item[]>([]);
  const isModalOpen = ref(false);
  const deleteCategoryId = ref<number>();
  const router = useRouter();

  const getCategories = async () => {
    axios.get(`http://127.0.0.1/api/categories`).then((response) => {
        items.value=  response.data.categories;
    });
  };

  getCategories();
  
  const deleteItem = (id: number) => {
    deleteCategoryId.value = id;
    isModalOpen.value = true;
  };
  const handleDelete = async () => {
    axios
        .delete(`http://127.0.0.1/api/categories/${deleteCategoryId.value}`)
        .then(() => {
            isModalOpen.value = false;
            router.go(0);
        });
};
  </script>
