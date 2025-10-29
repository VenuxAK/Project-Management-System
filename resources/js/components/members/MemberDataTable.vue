<script setup>
import DataTable from "@/components/ui/DataTable.vue";
import { computed, onMounted, ref } from "vue";
import Button from "@/components/ui/Button.vue";
import PlusIcon from "@/icons/PlusIcon.vue";
import CreateMemberModal from "@/components/members/CreateMemberModal.vue";
import EditMemberModal from "./EditMemberModal.vue";

const props = defineProps({
    members: {
        type: Array,
        required: true,
    },
});

const cols = [
    { label: "#ID", key: "id", sortable: false },
    { label: "Name", key: "name", sortable: false },
    { label: "Email", key: "email", sortable: false },
    { label: "Role", key: "role", sortable: true },
];
const rows = computed(() => {
    return props.members.map((member) => {
        return {
            id: member.id,
            name: member.name,
            email: member.email,
            role: member.role.name,
            id: member.id,
        };
    });
});

const isCreateMemberModalOpen = ref(false);
const onCreateNewUser = () => {
    // console.log("HIT");
    isCreateMemberModalOpen.value = true;
};

const isEditMemberModalOpen = ref(false);
const selectedMember = ref({});
const onEditMember = (member) => {
    isEditMemberModalOpen.value = true;
    const tmp_member = props.members.filter((mb) => {
        return member.id === mb.id;
    });
    selectedMember.value = tmp_member.map((mb) => {
        return {
            id: mb.id,
            name: mb.name,
            email: mb.email,
            role_id: mb.role_id,
        };
    })[0];
    // console.log(selectedMember.value);
};

// onMounted(() => {
//     console.log(rows.value);
// });
</script>

<template>
    <CreateMemberModal
        v-if="isCreateMemberModalOpen"
        :isCreateMemberModalOpen="isCreateMemberModalOpen"
        @update:isCreateMemberModalOpen="(e) => (isCreateMemberModalOpen = e)"
    />
    <EditMemberModal
        v-if="isEditMemberModalOpen"
        :isEditMemberModalOpen="isEditMemberModalOpen"
        @update:isEditMemberModalOpen="(e) => (isEditMemberModalOpen = e)"
        :member="selectedMember"
    />
    <DataTable
        :columns="cols"
        :rows="rows"
        :initialPageSize="10"
        :editAction="true"
        @edit="onEditMember($event)"
    >
        <template #title>
            <Button
                size="sm"
                variant="outline"
                :endIcon="PlusIcon"
                @click="onCreateNewUser"
            >
                Create new user
            </Button>
        </template>
    </DataTable>
</template>

<style lang="scss" scoped></style>
