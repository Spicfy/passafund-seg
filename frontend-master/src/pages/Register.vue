<script setup>
import { ref } from 'vue';
import axios from 'axios';
import GuestLayout from "../components/GuestLayout.vue";

import { useRouter } from 'vue-router';
const first_name = ref('');
const last_name = ref('');
const address = ref('');
const email = ref('');
const password = ref('');
const router = useRouter();

const register = async () => {
    console.log({
        first_name: first_name.value,
        last_name: last_name.value,
        address: address.value,
        email: email.value,
        password: password.value,
    });
    try {
        const response = await axios.post('http://localhost:8000/api/auth/register', {
            first_name: first_name.value,
            last_name: last_name.value,
            address: address.value,
            email: email.value,
            password: password.value,
        });
        console.log('Registration successful:', response.data);
        router.push('/login')
    } catch (error) {
        console.log(error.response.data)
        // Handle registration error
        console.error('Registration failed:', error.response ? error.response.data : error.message);
        if (error.response && error.response.status === 422) { // Laravel validation errors
            // Display validation errors in the UI
            let errorMessages = '';
            for (const field in error.response.data.errors) {
                errorMessages += error.response.data.errors[field].join('\n') + '\n';
            }
            alert('Registration failed due to validation errors:\n' + errorMessages);
        } else {
            alert('Registration failed: ' + (error.response ? error.response.data.message : error.message)); // Display an error message
        }
    }
};
</script>

<template>
    <GuestLayout>
        <form class="form-container" @submit.prevent="register" >
            <div class="form_title">Registration</div>
            <div class="form_field">
                <label for="firstName">First Name:</label>
                <input id="firstName" v-model="first_name" type="text" required />
            </div>
            <div class="form_field">
                <label for="lastName">Last Name:</label>
                <input id="lastName" v-model="last_name" type="text" required />
            </div>
            <div class="form_field">
                <label for="address">Address:</label>
                <input id="address" v-model="address" type="text" required />
            </div>
            <div class="form_field">
                <label for="email">Email:</label>
                <input id="email" v-model="email" type="email" required />
            </div>
            <div class="form_field">
                <label for="password">Password:</label>
                <input id="password" v-model="password" type="password" required />
            </div>
            <button class="form_submit" type="submit">Register</button>
            <p class="to-login">
                Already have an account? <router-link to="/login">Login</router-link>
            </p>
        </form>
    </GuestLayout>
</template>

<style scoped>
form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    justify-content: center;
    align-items: center;
    padding: 30px;
    max-width: 450px;
    border-radius: 10px;
    box-shadow: rgba(3, 3, 3, 0.1) 10px 0px 50px;
    width: 100%;
    background-color: #fff;

    margin-left: 500px;
}
.form_title {
    font-size: 38px;
    color: #555;
    margin-bottom: 7px;
}
.form_field {
    margin-bottom: 30px;
}
label {
    margin-bottom: 10px;
    font-size: 15px;
    font-weight: 600;
    color: #777;
}
input {
    width: 100%;
    border: 0;
    background: #f0f0f0;
    border-radius: 5px;
    font-size: 18px;
    color: #555;
    font-weight: 600;
}
.form_submit {
    background: #037ef3;
    color: #fff;
    font-weight: 600;
    width: 100%;
    border-radius: 5px;
    padding: 25px 15px;
    border: 0;
    transition: all 0.8s;
    font-size: 18px;
}
.form_submit:hover {
    background: #0271da;
}
button {
    padding: 0.5rem;
    font-size: 1rem;
    cursor: pointer;
}
</style>
