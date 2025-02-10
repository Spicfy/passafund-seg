<script setup>

import { ref } from 'vue';
import GuestLayout from "../components/GuestLayout.vue";
import axios from 'axios';
import { useRouter } from 'vue-router';


const email = ref('');
const password = ref('');
const router = useRouter();


const login = async() => {
    try{
        const response = await axios.post('http://localhost:8000/api/auth/login', {
            email: email.value,
            password: password.value,
        });
        if(response.data.success && response.data.data.original.access_token ){
           const token = response.data.data.original.access_token;
            console.log('Login successful: ', response.data);
            alert('Login successful');
            document.cookie = `access_token=${token}; path=/; max-age=3600; Secure; SameSite=Strict`;
            router.push('/')
        }else{
            console.error('Login failed: ', response.data.message);
            alert('Login failed: ' + response.data.message);
        }
    }catch(error){
        console.error('Login failed: ', error.response ? error.response.data : error.message);
        alert('Login failed: ' + (error.response ? error.response.data.message : error.message));
    }
    console.log({
     
        email: email.value,
        password: password.value,
    });
};
</script>

<template>
    <GuestLayout>
        <form class="form-container" @submit.prevent="login">
            <div class="form_title">Login</div>
            <div class="form_field">
                <label for="email">Email:</label>
                <input id="email" v-model="email" type="email" required />
            </div>
            <div class="form_field">
                <label for="password">Password:</label>
                <input id="password" v-model="password" type="password" required />
            </div>
            <button class="form_submit" type="submit">Login</button>

            <p class="to-login">
                Don't have an account? <router-link to="/register">Register account</router-link>
            </p>
            <p class="to-login">
                <router-link to="/forgot-password">Forgot Password?</router-link>
            </p>
        </form>
    </GuestLayout>
</template>


<style scoped>


form {
    display: grid;
    gap: 20px;
    grid-template-rows: auto auto auto; /* Adjust based on actual content */
grid-template-columns: 1fr;

    align-items: center;
    padding: 30px;
    max-width: 450px;
    border-radius: 10px;
    box-shadow: rgba(3, 3, 3, 0.1) 10px 0px 50px;

    width: 100%;


    margin-top: 40px;
    background-color: #fff;

    margin-left: 500px;
}
.form_title{
    font-size: 38px;
    color: #555;
    margin-bottom: 7px;
    margin-left: 120px;
    
}
.form_field{
    margin-bottom: 20px;

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
.form_submit{
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
.form_submit:hover{
    background: #0271da;
}

button {
    padding: 0.5rem;
    font-size: 1rem;
    cursor: pointer;
}
</style>



