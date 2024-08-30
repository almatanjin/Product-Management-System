<template>
    <div class="flex justify-center items-center mt-20">
        <div class="container m-16 px-4 py-8 md:flex md:justify-center">

            <div class="md:w-1/4">
                <nav class="text-sm text-gray-500 mb-4">
                    <span class="hover:underline cursor-pointer">Product</span> /
                    <span class="hover:underline cursor-pointer">View</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ productName }}</h1>
                <div class="flex items-center mb-4">
                    <div class="flex items-center text-yellow-400">
                     
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.184c.969 0 1.372 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.286 3.958c.3.921-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.175 0l-3.39 2.462c-.785.57-1.84-.197-1.54-1.118l1.286-3.958a1 1 0 00-.364-1.118L2.07 9.385c-.784-.57-.38-1.81.588-1.81h4.184a1 1 0 00.95-.69l1.286-3.958z" />
                        </svg>
                        
                    </div>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Feature</h2>
                <p class="text-gray-700 mb-4">
                    {{ description }}
                </p>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Category</h2>
                <div class="flex space-x-4 mb-4">
                    <button v-for="(category, index) in categories" :key="index"
                        class="border rounded-md px-4 py-2 w-full text-center" :class="{
                            'border-indigo-500 text-indigo-500 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2': category.type === 'type1',
                            'border-gray-300 text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2': category.type === 'type2'
                        }">
                        {{ category.name }}
                    </button>
                </div>
                
                <div class="text-sm text-gray-500 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-7.707l-3-3a1 1 0 111.414-1.414l1.586 1.586L13.293 5.293a1 1 0 011.414 1.414l-5 5a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Lifetime Guarantee
                </div>
            </div>

            
            <div class="md:w-1/2 mt-8 md:mt-0 flex justify-center items-center">
                <img :src="image ?? 'https://via.placeholder.com/400'" alt="Product Image" class="w-[500px] h-auto rounded-md">
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { useRoute } from 'vue-router';


const route = useRoute();
const productName = ref<string>("");
const description = ref<string>("");
const category = ref<string>("");
const categories = ref([]);
const image = ref<string>();


const id = route.params.id;

const getProduct = async () => {
    axios.get(`http://127.0.0.1/api/products/${id}`).then((response) => {
        productName.value = response.data.data.name;
        description.value = response.data.data.feature.map(feature => feature.description).join(', ');
        categories.value = response.data.data.category;
        category.value = response.data.data.category.map(category => category.name).join(', ');
        image.value = `http://127.0.0.1/storage/${response.data.data.image}`
    });
};

getProduct();
</script>