<script setup>
import {ref, defineProps} from "vue";
import TrashIcon from "@/js/components/Icons/TrashIcon.vue";
import EditIcon from "@/js/components/Icons/EditIcon.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    index: {
        type: Number,
        required: true
    }
})
</script>

<template>
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            {{ index + 1}}
        </th>
        <td class="px-6 py-4">
            {{ user.name + " " + user.surname }}
        </td>
        <td class="px-6 py-4">
            {{ user.email}}
        </td>
        <td class="px-6 py-4">
            {{ user.phone }}
        </td>
        <td class="px-6 py-4">
            {{ user.country }}
        </td>
        <td class="px-6 py-4">
            {{ user.gender }}
        </td>
        <td class="px-6 py-4">
            <img :src="user.selfie" class="w-10 h-10 rounded-full">
        </td>
        <td class="px-6 py-4">
            {{ user.introduction }}
        </td>
        <td class="px-6 py-4">
            <div class="flex">
                <EditIcon width="20" height="20"/>
                <TrashIcon width="20" height="20"/>
            </div>
        </td>
    </tr>
</template>

<style scoped>

</style>
