<template>
    <GuestLayout>
        <form class="form-container" @submit.prevent="forgotPassword">
            <div class="form_title">Forgot your password?</div>
            <div class="form_field">
                <label for="password">New Password</label>
                <input id="password" v-model="password" type="password" required />
            </div>
            <div class="form_field">
                <label for="password_confirmation">Confirm Password:</label>
                <input id="password_confirmation" v-model="password_confirmation" required>
            </div>
            <button class="form_submit" type="submit">Reset password</button>
      
            <p class="to-login">
            Go home <router-link to="/">Return home</router-link>
        </p>
        </form>

      
    </GuestLayout>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            password: '',
            password_confirmation: '',
            email: ''
        };
    },

    methods:{
        submitForm() {
            let token = this.$route.params.token;
            axios.post(`http://127.0.0.1:8000/api/resetpassword/${token}`,{
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
                token: token,
            },)
            .then(response => {
                console.log(response.data);
            })
            .catch(error => {
                console.log(error.response.data)
            })
        }
    }
}



</script>
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