<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    columns: { type: Array, required: true }, // [{ key: 'name', label: 'Name', sortable: true }, ...]
    rows: { type: Array, default: () => [] },
    pageSizes: { type: Array, default: () => [10, 8, 5] },
    initialPageSize: { type: Number, default: 10 },
    showSearch: { type: Boolean, default: true },
    useActions: { type: Boolean, default: false },
});

const emit = defineEmits(["edit", "delete", "update:pageSize"]);

const pageSize = ref(props.initialPageSize);
const page = ref(1);
const search = ref("");
const sortKey = ref(null);
const sortDir = ref(null); // 'asc' | 'desc' | null

watch(pageSize, (v) => {
    page.value = 1;
    emit("update:pageSize", v);
});

watch([() => props.rows, search], () => {
    page.value = 1;
});

// simple helpers
const getCell = (row, key) => {
    // support nested keys like 'user.name'
    return key.split(".").reduce((acc, k) => (acc ? acc[k] : undefined), row);
};

const filtered = computed(() => {
    const q = (search.value || "").toString().toLowerCase().trim();
    if (!q) return props.rows || [];
    return (props.rows || []).filter((r) =>
        props.columns.some((c) => {
            const v = getCell(r, c.key);
            return v != null && String(v).toLowerCase().includes(q);
        })
    );
});

const sorted = computed(() => {
    if (!sortKey.value) return filtered.value;
    const dir = sortDir.value === "asc" ? 1 : -1;
    return [...filtered.value].sort((a, b) => {
        const va = getCell(a, sortKey.value);
        const vb = getCell(b, sortKey.value);
        if (va == null && vb == null) return 0;
        if (va == null) return -dir;
        if (vb == null) return dir;
        if (typeof va === "number" && typeof vb === "number")
            return (va - vb) * dir;
        return String(va).localeCompare(String(vb)) * dir;
    });
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(sorted.value.length / pageSize.value))
);
const paginatedRows = computed(() => {
    const start = (page.value - 1) * pageSize.value;
    return sorted.value.slice(start, start + pageSize.value);
});

const startItem = computed(() =>
    sorted.value.length === 0 ? 0 : (page.value - 1) * pageSize.value + 1
);
const endItem = computed(() =>
    Math.min(sorted.value.length, page.value * pageSize.value)
);

function toggleSort(key) {
    if (sortKey.value !== key) {
        sortKey.value = key;
        sortDir.value = "asc";
    } else if (sortDir.value === "asc") {
        sortDir.value = "desc";
    } else {
        sortKey.value = null;
        sortDir.value = null;
    }
}

function nextPage() {
    if (page.value < totalPages.value) page.value++;
}
function prevPage() {
    if (page.value > 1) page.value--;
}
</script>

<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <div class="px-6 py-5">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                <slot name="title">Data Table</slot>
            </h3>
        </div>

        <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
            <div class="space-y-5">
                <div class="overflow-hidden">
                    <div
                        class="flex flex-col gap-2 py-4 rounded-b-none border-b-0 px-4 sm:flex-row sm:items-center rounded-xl border border-gray-100 sm:justify-between dark:border-gray-800 dark:bg-white/[0.03]"
                    >
                        <div class="flex items-center gap-3">
                            <span class="text-gray-500 dark:text-gray-400"
                                >Show</span
                            >
                            <div class="relative">
                                <select
                                    v-model="pageSize"
                                    class="w-full py-2 pl-3 pr-8 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg appearance-none dark:bg-dark-900 h-9 bg-none shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                >
                                    <option
                                        v-for="s in pageSizes"
                                        :key="s"
                                        :value="s"
                                    >
                                        {{ s }}
                                    </option>
                                </select>
                                <span
                                    class="absolute z-30 text-gray-500 -translate-y-1/2 right-2 top-1/2 dark:text-gray-400"
                                >
                                    <!-- chevron -->
                                    <svg
                                        class="stroke-current"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 16 16"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165"
                                            stroke-width="1.2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </svg>
                                </span>
                            </div>
                            <span class="text-gray-500 dark:text-gray-400"
                                >entries</span
                            >
                        </div>

                        <div class="relative">
                            <input
                                v-if="showSearch"
                                v-model="search"
                                type="text"
                                placeholder="Search..."
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-11 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 xl:w-[300px]"
                            />
                            <button
                                class="absolute text-gray-500 -translate-y-1/2 left-4 top-1/2 dark:text-gray-400"
                                v-if="showSearch"
                                @click.prevent
                            >
                                <svg
                                    class="fill-current"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M3.04199 9.37363C3.04199 5.87693 5.87735 3.04199 9.37533 3.04199C12.8733 3.04199 15.7087 5.87693 15.7087 9.37363C15.7087 12.8703 12.8733 15.7053 9.37533 15.7053C5.87735 15.7053 3.04199 12.8703 3.04199 9.37363ZM9.37533 1.54199C5.04926 1.54199 1.54199 5.04817 1.54199 9.37363C1.54199 13.6991 5.04926 17.2053 9.37533 17.2053C11.2676 17.2053 13.0032 16.5344 14.3572 15.4176L17.1773 18.238C17.4702 18.5309 17.945 18.5309 18.2379 18.238C18.5308 17.9451 18.5309 17.4703 18.238 17.1773L15.4182 14.3573C16.5367 13.0033 17.2087 11.2669 17.2087 9.37363C17.2087 5.04817 13.7014 1.54199 9.37533 1.54199Z"
                                        fill=""
                                    ></path>
                                </svg>
                            </button>

                            <slot name="top-actions"></slot>
                        </div>
                    </div>

                    <div class="max-w-full overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th
                                        v-for="col in columns"
                                        :key="col.key"
                                        class="px-4 py-3 border border-gray-100 dark:border-white/[0.05] text-left"
                                    >
                                        <div
                                            class="flex items-center justify-between w-full cursor-pointer"
                                            @click="
                                                col.sortable
                                                    ? toggleSort(col.key)
                                                    : null
                                            "
                                        >
                                            <p
                                                class="font-medium text-gray-700 text-theme-xs dark:text-gray-400"
                                            >
                                                {{ col.label }}
                                            </p>
                                            <span
                                                v-if="col.sortable"
                                                class="flex flex-col gap-0.5 font-medium text-gray-700 text-theme-xs dark:text-gray-400"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE -->
                                                    <path
                                                        fill="currentColor"
                                                        d="M4 18q-.425 0-.712-.288T3 17t.288-.712T4 16h4q.425 0 .713.288T9 17t-.288.713T8 18zm0-5q-.425 0-.712-.288T3 12t.288-.712T4 11h10q.425 0 .713.288T15 12t-.288.713T14 13zm0-5q-.425 0-.712-.288T3 7t.288-.712T4 6h16q.425 0 .713.288T21 7t-.288.713T20 8z"
                                                    />
                                                </svg>
                                            </span>
                                        </div>
                                    </th>

                                    <th
                                        v-if="useActions"
                                        class="px-4 py-3 text-left border border-gray-100 dark:border-white/[0.05]"
                                    >
                                        <p
                                            class="font-medium text-gray-700 text-theme-xs dark:text-gray-400"
                                        >
                                            Action
                                        </p>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="(row, rowIndex) in paginatedRows"
                                    :key="rowIndex"
                                    class="border-t border-gray-100 dark:border-white/[0.5]"
                                >
                                    <td
                                        v-for="col in columns"
                                        :key="col.key"
                                        class="px-4 py-3 border border-gray-100 dark:border-white/[0.05]"
                                    >
                                        <slot
                                            :name="`cell-${col.key}`"
                                            :row="row"
                                        >
                                            <p
                                                class="text-theme-sm text-gray-700 dark:text-gray-400"
                                            >
                                                {{ getCell(row, col.key) }}
                                            </p>
                                        </slot>
                                    </td>

                                    <td
                                        v-if="useActions"
                                        class="px-4 py-[17.5px] border border-gray-100 dark:border-white/[0.05]"
                                    >
                                        <div
                                            class="flex items-center w-full gap-2"
                                        >
                                            <slot name="actions" :row="row">
                                                <!-- default actions -->
                                                <button
                                                    @click="
                                                        $emit('delete', row.id)
                                                    "
                                                    class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500"
                                                >
                                                    <svg
                                                        data-v-f8e13455=""
                                                        class="fill-current"
                                                        width="21"
                                                        height="21"
                                                        viewBox="0 0 21 21"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            data-v-f8e13455=""
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503V9.25033Z"
                                                            fill=""
                                                        ></path>
                                                    </svg>
                                                </button>
                                                <button
                                                    @click="$emit('edit', row)"
                                                    class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                                >
                                                    <svg
                                                        data-v-f8e13455=""
                                                        class="fill-current"
                                                        width="21"
                                                        height="21"
                                                        viewBox="0 0 21 21"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            data-v-f8e13455=""
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                            fill=""
                                                        ></path>
                                                    </svg>
                                                </button>
                                            </slot>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="paginatedRows.length === 0">
                                    <td
                                        :colspan="columns.length + 1"
                                        class="px-4 py-6 text-center text-gray-500"
                                    >
                                        No records found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        class="border-t-0 border rounded-b-xl border-gray-100 py-4 pl-[18px] pr-4 dark:border-white/[0.05]"
                    >
                        <div
                            class="flex flex-col xl:flex-row xl:items-center xl:justify-between"
                        >
                            <p
                                class="pt-3 text-sm font-medium text-center text-gray-500 border-t border-gray-100 dark:border-gray-800 dark:text-gray-400 xl:border-t-0 xl:pt-0 xl:text-left"
                            >
                                Showing <span>{{ startItem }}</span> to
                                <span>{{ endItem }}</span> of
                                <span>{{ filtered.length }}</span> entries
                            </p>

                            <div
                                class="flex items-center justify-center gap-0.5 xl:justify-normal xl:pt-0"
                            >
                                <button
                                    :disabled="page === 1"
                                    @click="prevPage"
                                    class="mr-2.5 flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                                >
                                    <svg
                                        class="fill-current"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
                                            fill=""
                                        ></path>
                                    </svg>
                                </button>
                                <button
                                    v-for="p in totalPages"
                                    :key="p"
                                    @click="page = p"
                                    :class="[
                                        'flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500',
                                        page === p
                                            ? 'bg-blue-500/[0.08] text-brand-500'
                                            : 'text-gray-700 dark:text-gray-400',
                                    ]"
                                >
                                    {{ p }}
                                </button>

                                <button
                                    :disabled="page === totalPages"
                                    @click="nextPage"
                                    class="ml-2.5 flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                                >
                                    <svg
                                        class="fill-current"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z"
                                            fill=""
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped></style>
