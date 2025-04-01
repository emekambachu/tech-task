<script setup>
import {ref, reactive, onBeforeMount} from 'vue';
import handleErrors from "@/js/utils/handleErrors.js";
import apiClient from "@/js/utils/apiClient.js";
import formValidations from "@/js/utils/formValidations.js";

const loading = ref(false);
const countries = ref([]);
let errors = ref({});
let imagePreview = ref(null);

let form = reactive({
    name: '',
    surname: '',
    email: '',
    phone: '',
    country: '',
    gender: '',
    selfie: null,
    introduction: '',
    password: '',
    password_confirmation: '',
});

const submitForm = async () => {
    loading.value = true;
    try {
        const formData = new FormData();
        for (const key in form) {
            formData.append(key, form[key]);
        }
        const response = await apiClient.post('/users', formData);
        if(response.data.success){
            console.log(response.data.message);
        }
    } catch (error) {
        if (error.response) {
            handleErrors.hideErrorInProduction("ERROR_RESPONSE", error.response)
        }
    }
    loading.value = false;
};

const fetchCountries = async () => {
    try{
        const response = await apiClient.get('/countries');
        countries.value = response.data.countries;
        console.log(response.data);
    }catch(error){
        if (error.response) {
            handleErrors.hideErrorInProduction("ERROR_RESPONSE", error.response)
        }
    }
}

const uploadSelfie = (event) => {
  let validateFileType = formValidations.validateFileType(event.target.files[0], ['jpg', 'jpeg', 'png']);
  if(!validateFileType){
    errors.value['selfie'] = ['Incorrect file format. allowed: jpg, jpeg, png'];
    form.selfie = null;
    return false;
  }

  let validateFileSize = formValidations.validateFileSize(event.target.files[0], 2000000);
  if(!validateFileSize){
    errors.value['selfie'] = ['File too large, 2mb max'];
    form.selfie = null;
    return false;
  }

  //Assign image and path to this variable
  form.selfie = event.target.files[0];
  imagePreview.value = URL.createObjectURL(event.target.files[0]);
}

const validatePhone = (event) => {
  if(form.phone === ""){
    return false
  }
  let valid = formValidations.validateMobileNumber(event.target.value);
  if (!valid) {
    errors.value.mobile = ["Wrong format, international mobile number required"];
    return false;
  }else{
    errors.value.mobile = [];
    return true;
  }
}

const validatePassword = (event, password, password_confirm) => {
  let identical = formValidations.passwordConfirmation(password, password_confirm);
  if(!identical){
    errors.value['password'] = ['Password and confirmation do not match'];
    return false;
  }else{
    errors.value['password'] = [];
    return true;
  }
}

onBeforeMount(() => {
    fetchCountries();
});

</script>

<template>
    <div>

      <div v-if="Object.keys(errors).length" class="card">
        <p>
          <span class="text-red-500 font-bold">Error:</span>
          <span v-for="(error, index) in errors" :key="index" class="text-red-500">
            {{ error }}
          </span>
        </p>
      </div>

      <form @submit.prevent="submitForm" class="max-w-sm mx-auto">

            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <input v-model="form.name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" required />
                <p v-if="errors.name" class="text-rose-300">
                  {{ errors.name[0] }}
                </p>
            </div>

            <div class="mb-5">
                <label for="surname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Surname</label>
                <input
                    v-model="form.surname"
                    type="text"
                    id="surname"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="" required />
                <p v-if="errors.surname" class="text-rose-300">
                  {{ errors.surname[0] }}
                </p>
            </div>

            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input v-model="form.email" type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required />
              <p v-if="errors.email" class="text-rose-300">
                {{ errors.email[0] }}
              </p>
            </div>

            <div class="mb-5">
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                <input v-model="form.phone" @change="validatePhone($event)" type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required />
                <p v-if="errors.phone" class="text-rose-300">
                  {{ errors.phone[0] }}
                </p>
            </div>

            <div class="mb-5">
                <label for="gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                <select id="gender"
                        v-model="form.gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required
                >
                    <option value=""></option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
              <p v-if="errors.gender" class="text-rose-300">
                {{ errors.gender[0] }}
              </p>
            </div>

            <div class="mb-5">
                <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country</label>
                <select id="country" v-model="form.country" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required
                >
                    <option v-for="country in countries" :key="country.code" :value="country.name">
                        {{ country.name }}
                    </option>
                </select>
              <p v-if="errors.country" class="text-rose-300">
                {{ errors.country[0] }}
              </p>
            </div>

            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                <input
                    v-model="form.password"
                    @change="validatePassword($event, form.password, form.password_confirmation)"
                    type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
              <p v-if="errors.password"
                 class="text-rose-300 font-bold">
                {{ errors.password[0] }}
              </p>
            </div>

            <div class="mb-5">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                <input v-model="form.password_confirmation" type="password" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">Upload Selfie (2mb Max)</label>
                <input
                    accept="image/*"
                    @change="uploadSelfie($event)"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="user_avatar_help" id="user_avatar" type="file"
                >

              <div v-if="form.selfie !== null && !errors?.selfie" class="flex">
                <img :src="imagePreview" width="100"/>
                <span @click="form.selfie = null"
                      class="pl-1 text-red-500 cursor-pointer font-bold"
                      title="Remove image">
                  x
                </span>
              </div>

              <p v-if="errors.selfie"
                    class="text-rose-300 font-bold">
                  {{ errors.selfie[0] }}
              </p>
            </div>

            <div class="mb-5">
              <label
                  for="introduction"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Introduction</label>
              <textarea
                  v-model="form.introduction"
                  id="introduction"
                  rows="4"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Write your thoughts here..."></textarea>
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </form>
    </div>

</template>

<style scoped>

</style>
