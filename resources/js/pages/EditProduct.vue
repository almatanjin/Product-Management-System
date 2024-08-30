<template>
<div class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
        <div
            class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]"
            aria-hidden="true"
        >
            <div
                class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]"
                style="
                    clip-path: polygon(
                        74.1% 44.1%,
                        100% 61.6%,
                        97.5% 26.9%,
                        85.5% 0.1%,
                        80.7% 2%,
                        72.5% 32.5%,
                        60.2% 62.4%,
                        52.4% 68.1%,
                        47.5% 58.3%,
                        45.2% 34.5%,
                        27.5% 76.7%,
                        0.1% 64.9%,
                        17.9% 100%,
                        27.6% 76.8%,
                        76.1% 97.7%,
                        74.1% 44.1%
                    );
                "
            ></div>
        </div>
        <div class="mx-auto max-w-2xl text-center">
            <h2
                class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
            >
                Create Product
            </h2>

        </div>

        <div class="mx-auto mt-16 max-w-[700px] sm:mt-20 w-[400px]">
            <div>
                <div>
                    <div>
                        <div>
                            <input
                                v-model="productName"
                                type="text"
                                name="name"
                                id="name"
                                autocomplete="given-name"
                                placeholder="Product Name"
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            />
                        </div>

                        <div
                            class="mt-6 rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                            <div>
                                <img
                                    :src="
                                        imageUrl ??
                                        'https://www.syncros.com/_ui/responsive/common/images/no-product-image-available.png'
                                    "
                                    alt="Image Preview"
                                    style="max-width: 100%; height: auto"
                                />
                            </div>
                        </div>

                        <div class="max-w-sm pt-6">
                            <form>
                                <label class="block">
                                    <span class="sr-only">Choose photo</span>
                                    <input
                                        type="file"
                                        @change="previewImage"
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:me-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:disabled:opacity-50 file:disabled:pointer-events-none dark:text-neutral-500 dark:file:bg-blue-500 dark:hover:file:bg-blue-400"
                                    />
                                </label>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <button
                    class="mt-10 flex w-[50%] items-center justify-center rounded-md border border-transparent bg-indigo-600 px-3.5 py-2 text-base font-medium text-white w-[120px]"
                    @click="onProductEdit"
                >
                    Save
                </button>
        </div>
</div>



</template>
<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router';


interface InputField {
    description: string;
}

const route = useRoute();
const router = useRouter();

const imageUrl = ref<string | ArrayBuffer | null>(null);
const imageResponse = ref<string>();
const productName = ref<string>("");
const fileInput = ref<HTMLInputElement | null>(null);

const previewImage = (event: Event) => {
    const input = event.target as HTMLInputElement;
    fileInput.value = event.target;
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = (e: ProgressEvent<FileReader>) => {
            imageUrl.value = e.target?.result;
        };

        reader.readAsDataURL(file);
    }
};


const id = route.params.id;

const getProduct = async () => {
    axios.get(`http://127.0.0.1/api/products/${id}`).then((response) => {
        productName.value = response.data.data.name;
        imageUrl.value = `http://127.0.0.1/storage/${ response.data.data.image}`
    });
  };

  getProduct();

  const onProductEdit = async () => {
    try {
       if(fileInput.value)
       {
        const formData = new FormData();
        formData.append("image", fileInput.value.files[0]);
        const imageresponse = await axios.post(
            "http://127.0.0.1/api/upload",
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data", // Required for file uploads
                },
            }
        );
        imageResponse.value = imageresponse.data.url;
       }

        const response = await axios.put(`http://127.0.0.1/api/products/${id}`, {
            name: productName.value,
            image: imageResponse.value,
        });

        if (response.data.success) {
            router.push("/product");
        }
    } catch (err) {
        console.error("Error", err);
    }
};
</script>
