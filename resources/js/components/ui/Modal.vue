<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 flex items-center justify-center overflow-y-auto modal z-99999"
        >
            <div
                class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
                aria-hidden="true"
                @click="$emit('close')"
            ></div>
            <div
                ref="dialogRef"
                role="dialog"
                aria-modal="true"
                v-bind="$attrs"
                class="no-scrollbar relative w-full max-w-[680px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
            >
                <slot name="body"></slot>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from "vue";

defineOptions({ inheritAttrs: false });

const emit = defineEmits(["close"]);

const dialogRef = ref(null);
const previouslyFocused = ref(null);

const FOCUSABLE_SELECTOR =
    'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';

const getFocusableElements = () =>
    dialogRef.value
        ? Array.from(
              dialogRef.value.querySelectorAll(FOCUSABLE_SELECTOR)
          ).filter((el) => el.offsetParent !== null)
        : [];

const focusFirstElement = () => {
    const focusable = getFocusableElements();
    if (focusable.length > 0) {
        focusable[0].focus();
    } else {
        dialogRef.value?.focus();
    }
};

const trapFocus = (event) => {
    if (event.key !== "Tab") return;

    const focusable = getFocusableElements();
    if (focusable.length === 0) return;

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
};

const onKeyDown = (event) => {
    if (event.key === "Escape") {
        event.stopPropagation();
        emit("close");
        return;
    }
    trapFocus(event);
};

onMounted(() => {
    previouslyFocused.value = document.activeElement;
    document.addEventListener("keydown", onKeyDown);
    dialogRef.value?.setAttribute("tabindex", "-1");
    focusFirstElement();
});

onBeforeUnmount(() => {
    document.removeEventListener("keydown", onKeyDown);
    if (
        previouslyFocused.value &&
        typeof previouslyFocused.value.focus === "function"
    ) {
        previouslyFocused.value.focus();
    }
});
</script>
