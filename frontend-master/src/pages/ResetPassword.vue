<template>
    <div>
      <h1>Reset Password</h1>
      <form @submit.prevent="resetPassword">
        <div>
          <label for="password">New Password</label>
          <input type="password" v-model="password" required>
        </div>
        <div>
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" v-model="password_confirmation" required>
        </div>
        <button type="submit">Reset Password</button>
      </form>
      <p v-if="message">{{ message }}</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    data() {
      return {
        password: '',
        password_confirmation: '',
        message: ''
      };
    },
    methods: {
      async resetPassword() {
        try {
          const response = await axios.post('/api/auth/password/reset', {
            token: this.$route.params.token,
            email: this.$route.params.email,
            password: this.password,
            password_confirmation: this.password_confirmation
          });
          this.message = response.data.message;
        } catch (error) {
          this.message = 'Failed to reset password.';
        }
      }
    }
  };
  </script>
  