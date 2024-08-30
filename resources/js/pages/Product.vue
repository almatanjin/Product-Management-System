<template>
    <div class="bg-white">
        <div
            class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8"
        >
            <div class="flex justify-end pt-12">
                <router-link to="/create-product">
                    <button
                        type="button"
                        class="text-sm px-4 py-4 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition-colors"
                    >
                        Add Product
                    </button>
                </router-link>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                Products
            </h2>

            <div
                class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10 xl:gap-x-8"
            >
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="group relative"
                >
                    <div
                        class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80"
                    >
                        <img
                            :src="product.image"
                            class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                        />
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div class="w-full">
                           <div class="flex justify-between max-w-full">
                            <h3 class="text-lg text-gray-700">
                                <a :href="product.href">
                                    {{ product.name }}
                                </a>
                            </h3>
                            <router-link :to="`/view-product/${product.id}`">
                                <div> <p class="text-gray-500 text-sm">view</p></div>
                            </router-link>
                           </div>
                            <div class="pt-3 flex justify-between">
                                <router-link :to="`/edit-product/${product.id}`">
                                <button
                                    @click="addInput"
                                    type="button"
                                    class="text-sm px-2 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition-colors w-[70px]"
                                >
                                    Edit
                                </button>
                            </router-link>
                                <button
                                    @click="onDelete(product.id)"
                                    type="button"
                                    class="text-sm px-2 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors w-[70px]"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <DeleteModal
                    :isOpen="isModalOpen"
                    @close="isModalOpen = false"
                    @confirm="handleDelete"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from "axios";
import { ref, onMounted } from "vue";
import DeleteModal from "../components/DeleteModal.vue";
import { useRouter } from "vue-router";

interface Product {
    id: number;
    name: string;
    image: string;
    feature: string;
}


const router = useRouter();

const products = ref<Product[]>([]);
const isModalOpen = ref(false);
const deleteProductId = ref<number>();

const getCategories = async () => {
    axios.get(`http://127.0.0.1/api/products`).then((response) => {
        products.value = response.data.products.map((product) => {
            return {
                id: product.id,
                name: product.name,
                image: `http://127.0.0.1/storage/${product.image}`,
            };
        });
    });
};

getCategories();

const onDelete = (id: number) => {
    isModalOpen.value = true;
    deleteProductId.value = id;
};

const handleDelete = async () => {
    axios
        .delete(`http://127.0.0.1/api/products/${deleteProductId.value}`)
        .then(() => {
            isModalOpen.value = false;
            router.go(0);
        });
};
</script>
