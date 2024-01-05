<template>
    <div class="w-full p-10 mx-auto">
        <div class="flex justify-between">
            <h1 class="text-2xl"> Customers </h1>
            <span class="capitalize">Welcome {{ user && user.name }}, <button
                class="text-orange-500 underline hover:no-underline rounded-md"
                @click="handleLogout">Logout</button></span>
        </div>
        <Loader v-if="isLoading"/>
        <div class="mx-auto w-full mt-10 bg-blue-200 p-4 rounded-lg">
        <div class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-2 flex flex-col">
            <h1 class="text-gray-600 py-5 font-bold text-3xl"> Create Customer </h1>
            <ul class="list-disc text-red-400" v-for="(value, index) in errors" :key="index">
                <li>{{value[0]}}</li>
            </ul>
            <form method="post" @submit="handleSubmit">
            <div class="mb-4 mt-3">
                <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="name"
                >
                   Name
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    id="name"
                    type="text"
                    required
                    v-model="form.name"
                />
            </div>
            <div class="mb-4">
                <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="email"
                >
                    Email
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                    type="email"
                    id="email"
                    required
                    v-model="form.email"
                />
            </div>
            <div class="mb-4">
                <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="phone"
                >
                    Phone
                </label>
                <input
                    class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3"
                    id="phone"
                    type="text"
                    required
                    v-model="form.phone"
                    v-maska data-maska="###-###-####"
                />
            </div>
            <div class="mb-4">
                <label
                    class="block text-grey-darker text-sm font-bold mb-2"
                    for="address"
                >
                    Address
                </label>
                <input
                    class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3"
                    id="address"
                    type="address"
                    required
                    v-model="form.address"
                />
            </div>
            <div class="flex items-center justify-between">
                <router-link
                    class="inline-block align-baseline font-bold text-sm text-blue hover:text-blue-darker"
                    to="/home"
                >
                    Cancel
                </router-link>
                <button
                    class="bg-blue-500 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded"
                    type="submit"
                >
                    Create
                </button>
                
            </div>
            </form>
        </div></div>
    </div>
</template>
 
<script>
import {reactive, ref, onMounted} from 'vue';
import axios from 'axios';
import {useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';
import { vMaska } from "maska"

export default {
    directives: { maska: vMaska },
    components: {
        Loader,
    },
  data () {
    return {
    }
  },
    setup() {
        const errors = ref();
        const user = ref();
        let router = useRouter();
        const isLoading = ref();

        const form = reactive({
            name: '',
            email: '',
            phone: '',
            address: ''
        })


        onMounted(() => {
            authentication() 
            isLoading.value = false
        });

        const authentication = async () => {
            isLoading.value = true
            try {
                const req = await request('get', '/api/user')
                user.value = req.data
           } catch (e) {
                await router.push('/')
            }
        }

        const handleLogout = () => {
            localStorage.removeItem('APP_DEMO_USER_TOKEN')
            router.push('/')
        }

        const handleSubmit = async(evt) => {
            evt.preventDefault()
            try {
   
                const result = await request('post', '/api/customers', form);
              
                if (result.data.message) {
                    isLoading.value = false
                    return alert(result.data.message)
                }
                if (result.status === 201 && result.data.data ) {
                    await router.push('home')
                }
           
            }catch (e) {
                if(e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors)
                }
           }
        }
        return {
            form,
            errors,
            user,
            isLoading,
            handleSubmit,
            handleLogout,
        }
    },
    methods: {
        acceptNumber() {
            var x = this.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        }
    }
 
}
</script>