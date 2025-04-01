<script setup>
import {ref, onBeforeMount} from 'vue';
import UserItem from "@/js/pages/user/UserItem.vue";
import apiClient from "@/js/utils/apiClient.js";
import handleErrors from "@/js/utils/handleErrors.js";

const users = ref([]);
const loading = ref(false);

const getUsers = async () => {
    loading.value = true;
    try {
        const response = await apiClient.get('/users');
        if(response.data.success){
            users.value = response.data.users;
            console.log(response.data.users);
        }

    } catch (error) {
        if (error.response) {
            handleErrors.hideErrorInProduction("ERROR_RESPONSE", error.response)
        }
    }
    loading.value = false;
};

onBeforeMount(() => {
    getUsers();
});

</script>

<template>
    <h1 class="text-3xl mb-2">User List</h1>

    <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        S/N
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Names
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Phone
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Country
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Gender
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Selfie
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Introduction
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
                </thead>

                <tbody>
                    <UserItem
                        v-for="(user, index) in users"
                        :key="user.id"
                        :index="index"
                        :user="user"
                    />
                </tbody>
            </table>
        </div>
</template>

<style scoped>

</style>
