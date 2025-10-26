<script setup>
import flatPickr from "vue-flatpickr-component";
import CalenderIcon from "@/icons/CalenderIcon.vue";

const flatpickrConfig = {
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "F j, Y",
    wrap: true,
};

const props = defineProps({
    label: {
        type: String,
    },
});
const model = defineModel({
    type: String,
    default: null,
});
</script>

<template>
    <div>
        <label
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
        >
            {{ label }}
        </label>
        <div class="relative">
            <flat-pickr
                v-model="model"
                :config="flatpickrConfig"
                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                placeholder="Select date"
            />
            <span
                class="absolute text-gray-500 -translate-y-1/2 pointer-events-none right-3 top-1/2 dark:text-gray-400"
            >
                <CalenderIcon />
            </span>
        </div>
    </div>
</template>

<style lang="scss" scoped></style>
