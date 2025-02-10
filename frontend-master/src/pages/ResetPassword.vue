<template>
    <div>
      <h1>Reset Password</h1>
      <form @submit.prevent="resetPassword">
        <div>
          <label for="email">Email</label>
          <input type="email" v-model="email" required>
        </div>
        <button type="submit">Send Reset Link</button>
      </form>
      <p v-if="message">{{ message }}</p>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: '',
        message: ''
      };
    },
    methods: {
      async resetPassword() {
        try {
          const response = await axios.post('/api/password/email', { email: this.email });
          this.message = response.data.message;
        } catch (error) {
          this.message = 'Failed to send reset link.';
        }
      }
    }
  };
  </script>
  