<template>
    <div
        class="flex items-center justify-center bg-white pb-36  text-slate-800 dark:bg-slate-900 dark:text-slate-100"
    >
        <div
            class="relative mx-6 w-96 sm:mx-auto"
            aria-haspopup="listbox"
            role="combobox"
            :aria-expanded="showList"
        >
            <div ref="boxContainer">
                <button
                    @click="toggleDropdown"
                    class="relative flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-900 dark:focus:ring-indigo-700"
                    aria-controls="tags-dropdown"
                    aria-labelledby="tags-label"
                >
                    <div class="flex items-center gap-2">
                        <template v-if="selectedTags.length > 0">
                            <span
                                v-for="tag in selectedTags.slice(0, 2)"
                                :key="tag.id"
                                class="inline-flex items-center rounded bg-indigo-100 px-2 py-0.5 text-xs font-medium dark:bg-indigo-700"
                            >
                                {{ tag.name }}
                            </span>
                            <span v-if="selectedTags.length > 2">
                                +{{ selectedTags.length - 2 }}
                            </span>
                        </template>
                        <p v-else>Choose Categories</p>
                    </div>
                    <span
                        class="absolute right-3 top-1/2 -translate-y-1/2"
                        aria-hidden="true"
                    >
                        <ChevronDownIcon
                            class="size-6 transition-transform duration-500"
                            :class="showList ? 'rotate-180' : 'rotate-0 '"
                        />
                    </span>
                </button>

                <div
                    v-show="showList"
                    class="absolute z-10 mt-1 w-full overflow-hidden rounded-lg border border-slate-300 bg-white shadow-md dark:border-slate-600 dark:bg-slate-900"
                    id="tags-dropdown"
                >
                    <div
                        class="flex items-center gap-3 border-b border-slate-200 px-3 dark:border-slate-600"
                    >
                        <input
                            type="checkbox"
                            id="select-all-checkbox"
                            :checked="areAllVisibleTagsSelected"
                            @change="selectAllTags"
                            class="size-5 cursor-pointer accent-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-700"
                            aria-checked="areAllVisibleTagsSelected"
                        />
                        <input
                            type="text"
                            v-model="searchTerm"
                            ref="searchInput"
                            placeholder="Search Interest"
                            class="w-full rounded-t-lg bg-white py-2 focus:outline-none dark:bg-slate-900"
                        />
                    </div>
                    <div class="combo-box-scrollbar max-h-96 overflow-y-auto">
                        <div
                            v-for="tag in filteredTags"
                            :key="tag.id"
                            @click="selectTag(tag)"
                            @keypress.space="selectTag(tag)"
                            class="flex cursor-pointer items-center gap-3 px-3 py-2 hover:bg-indigo-500 hover:text-white focus:outline-none dark:hover:bg-indigo-700"
                            role="option"
                            :aria-selected="isSelected(tag)"
                            tabindex="0"
                        >
                            <input
                                type="checkbox"
                                :id="tag.id"
                                :checked="isSelected(tag)"
                                class="size-5 cursor-pointer accent-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:focus:ring-indigo-700"
                                aria-checked="isSelected(tag)"
                            />
                            <label :for="tag.id" class="pointer-events-none">
                                {{ tag.name }}
                            </label>
                        </div>
                    </div>
                    <div
                        class="border-t border-slate-300 px-3 py-2 text-right dark:border-slate-600"
                    >
                        <button
                            @click="onSelectCategories"
                            class="rounded-md bg-indigo-500 px-3 py-1 text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:bg-indigo-700 dark:focus:ring-indigo-500"
                        >
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from "vue";
import { ChevronDownIcon } from "@heroicons/vue/24/outline";
import axios from "axios";


const emit = defineEmits<{
  (event: 'onUpdate', payload: Array<{ id: number; name: string }>): void;
}>();

const blogTags = ref<Array<{ id: number; name: string }>>([]);

const selectedTags = ref<Array<{ id: number; name: string }>>([]);
const searchTerm = ref("");
const showList = ref(false);
const boxContainer = ref(null);
const searchInput = ref(null);

function toggleDropdown() {
    showList.value = !showList.value;
}

function selectTag(tag) {
    if (isSelected(tag)) {
        selectedTags.value = selectedTags.value.filter((t) => t.id !== tag.id);
    } else {
        selectedTags.value.push(tag);
    }
    emit('onUpdate', selectedTags.value);
}

function isSelected(tag) {
    return selectedTags.value.some((t) => t.id === tag.id);
}

const filteredTags = computed(() => {
    const term = searchTerm.value.toLowerCase();
    return blogTags.value.filter((tag) =>
        tag.name.toLowerCase().includes(term)
    );
});

function selectAllTags() {
    const currentlyFilteredTags = filteredTags.value;
    if (areAllVisibleTagsSelected.value) {
        selectedTags.value = selectedTags.value.filter(
            (tag) => !currentlyFilteredTags.some((t) => t.id === tag.id)
        );
    } else {
        const newTags = currentlyFilteredTags.filter(
            (tag) => !selectedTags.value.some((t) => t.id === tag.id)
        );
        selectedTags.value.push(...newTags);
    }
    emit('onUpdate', selectedTags.value);
}

const areAllVisibleTagsSelected = computed(() => {
    const currentlyFilteredTags = filteredTags.value;
    return (
        currentlyFilteredTags.length > 0 &&
        currentlyFilteredTags.every((tag) =>
            selectedTags.value.some((t) => t.id === tag.id)
        )
    );
});

function closeDropdown(event) {
    if (
        !boxContainer.value.contains(event.target) &&
        !searchInput.value.contains(event.target)
    ) {
        searchTerm.value = "";
        showList.value = false;
    }
}

const onSelectCategories = () => {
    showList.value = false;
    emit('onUpdate', selectedTags.value);
};

const getCategories = async () => {
    axios.get(`http://127.0.0.1/api/categories`).then((response) => {
        blogTags.value=  response.data.categories;
    });
  };

  getCategories();

onMounted(() => {
    window.addEventListener("click", closeDropdown);
});

onUnmounted(() => {
    window.removeEventListener("click", closeDropdown);
});
</script>

<style scoped>
.combo-box-scrollbar::-webkit-scrollbar {
    width: 10px;
}

.combo-box-scrollbar::-webkit-scrollbar-track {
    background: #c7d2fe;
}

.combo-box-scrollbar::-webkit-scrollbar-thumb {
    background: #818cf8;
}

.combo-box-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #6366f1;
}
</style>
