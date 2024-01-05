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
        <div class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-2 flex flex-col" >
        <div style="display: flex; flex-direction: column;">
            <button style="align-self: flex-start" class="bg-blue-600 text-white px-5 py-2 rounded-md ml-2 hover:bg-blue-400" @click="$router.push('create')">Add Customer</button>
        </div>
        <table class="border-t mt-3 cursor-pointer w-full">
            <tr class="py-3 border-b text-gray-600 leading-10">
                <td><span class="pl-3 font-bold">ID</span></td>
                <td><span class="pl-3 font-bold">Name</span></td>
                <td><span class="pl-3 font-bold">Email</span></td>
                <td><span class="pl-3 font-bold">Phone</span></td>
                <td><span class="pl-3 font-bold">Address</span></td>
                <td></td>
            </tr>
            <tr class="py-3 border-b text-gray-600 leading-10"
                v-for="(val, idx) in customers" :key="idx">
                <td><span class="pl-3">{{ val.id }} </span></td>
                <td><span class="pl-3">{{ val.name }} </span></td>
                <td><span class="pl-3">{{ val.email }} </span></td>
                <td><span class="pl-3">{{ val.phone }} </span></td>
                <td class="line-clamp-1"><span class="pl-3">{{ val.address }} </span></td>
                <td>
                    <button 
                        class="float-right bg-red-400 px-2 text-white font-bold rounded-md hover:bg-red-600"
                        @click="deleteCustomer(val, idx)"
                    >
                        &times;
                    </button>
                    <router-link 
                        class="float-right bg-orange-400 px-2 mr-1 text-white font-bold rounded-md hover:bg-orange-600"
                        :to="{ name: 'update', params: { id: val.id } }"
                    >
                        &raquo;
                    </router-link>
                </td>
            </tr>
        </table></div></div>
    </div>
</template>
<script>
import {ref, onMounted} from 'vue'
import {useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';

export default {
    components: {
        Loader,
    },
    setup() {
        const customer = ref('')
        const customers = ref([])
        const user = ref()
        const isLoading = ref()

        let router = useRouter();
        onMounted(() => {
            authentication()
            handleCustomers()
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

        const handleCustomers = async () => {
            try {
                const req = await request('get', '/api/customers')
                customers.value = req.data.data
            } catch (e) {
                await router.push('/')
            }
            isLoading.value = false
        }

        const handleLogout = () => {
            localStorage.removeItem('APP_DEMO_USER_TOKEN')
            router.push('/')
        }

        const deleteCustomer = async (val, index) => {
            if (window.confirm("Are you sure")) {
                try {
                    const req = await request('delete', `/api/customers/${val.id}`)
                    if (req.data.message) {
                        isLoading.value = false
                        customers.value.splice(index, 1)
                    }
                } catch (e) {
                    await router.push('/')
                }
                isLoading.value = false
            }
        }

        return {
            customer,
            customers,
            user,
            isLoading,
            deleteCustomer,
            handleLogout,
        }
    },
}
</script>
