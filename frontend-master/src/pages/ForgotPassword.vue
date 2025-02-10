<template>
  <div class="forgot-password">
    <h1>Forgot Password</h1>
    <form @submit.prevent="sendResetLink">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" v-model="email" required>
      </div>
      <button type="submit">Send Reset Link</button>
    </form>
    <p v-if="message">{{ message }}</p>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      email: '',
      message: ''
    };
  },
  methods: {
    async sendResetLink() {
      try {
        const response = await axios.post('/api/auth/password/forgot', { email: this.email });
        this.message = response.data.message;
      } catch (error) {
        this.message = 'Failed to send reset link.';
      }
    }
  }
};
</script>

<style scoped>
.forgot-password {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
}

.forgot-password h1 {
  text-align: center;
  margin-bottom: 20px;
}

.forgot-password .form-group {
  margin-bottom: 15px;
}

.forgot-password label {
  display: block;
  margin-bottom: 5px;
}

.forgot-password input[type="email"] {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.forgot-password button {
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.forgot-password button:hover {
  background-color: #0056b3;
}

.forgot-password p {
  text-align: center;
  margin-top: 20px;
  color: green;
}
</style>