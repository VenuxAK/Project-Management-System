<script setup>
import { onMounted, onUnmounted } from "vue";

const handleClickOutside = (event) => {
    if (!(event.target === el.value || el.value.contains(event.target))) {
        binding.value(event);
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
    console.log("Hit, v-click-outside");
});
onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
    // console.log("Hit, v-click-outside");
});
</script>
