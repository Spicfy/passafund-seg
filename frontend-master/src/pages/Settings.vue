<template>

   
    <div class="settings-container">

      <h1>User Settings</h1>
  
      <div v-if="error" class="error-message">
        {{ error }}
      </div>
      <div v-if="successMessage" class="success-message">
        {{ successMessage }}
      </div>
  
      <form @submit.prevent="updateProfile" class="form-group">
          <label for="firstName">First Name:</label>
          <input type="text" id="firstName" v-model="form.first_name" required>
      
          <label for="lastName">Last Name:</label>
          <input type="text" id="lastName" v-model="form.last_name" required>
  
          <label for="address">Address:</label>
          <input type="text" id="address" v-model="form.address">
  
        <button type="submit">Update Profile</button>
      </form>
  
      <form @submit.prevent="changePassword" class="form-group">
        <label for="currentPassword">Current Password:</label>
        <input type="password" id="currentPassword" v-model="passwordForm.current_password" required>
  
        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" v-model="passwordForm.new_password" required>
  
        <label for="confirmPassword">Confirm New Password:</label>
        <input type="password" id="confirmPassword" v-model="passwordForm.new_password_confirmation" required>
  
        <button type="submit">Change Password</button>
      </form>
  
      <form @submit.prevent="changeEmail" class="form-group">
        <label for="newEmail">New Email:</label>
        <input type="email" id="newEmail" v-model="emailForm.new_email" required>
  
        <button type="submit">Change Email</button>
      </form>
      <p class="home">
        <router-link to="/">Return to home</router-link>
    </p>
    </div>
  </template>
  
  <script setup>
  /* eslint-disable */
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  const form = ref({
      first_name: '',
      last_name: '',
      address: ''
  });

  const router = useRouter();
  
  const passwordForm = ref({
      current_password: '',
      new_password: '',
      new_password_confirmation: ''
  });
  
  const emailForm = ref({
      new_email: ''
  });
  
  const error = ref(null);
  const successMessage = ref(null);
  
  const getAccessToken = () => {
      const cookieString = document.cookie;
  
      const cookies = cookieString.split(';');
      console.log('Cookies:', cookies);
  
      for(let i = 0; i < cookies.length; i++){
          const cookie = cookies[i].trim();
          if(cookie.startsWith('access_token' + '=')){
              return cookie.substring(('access_token').length +1);
          }
      }
      return null;
  }
  
  const fetchUserData = async () => {
      try {
          const token = getAccessToken();
          if (token) {
              const response = await axios.get('http://localhost:8000/api/user', {
                  headers: {
                      Authorization: `Bearer ${token}`
                  }
              });
  
              if (response.data && response.data.data) {
                  // Use response.data.data to populate the form
                  form.value.first_name = response.data.data.first_name;
                  form.value.last_name = response.data.data.last_name;
                  form.value.address = response.data.data.address || ''; // Use an empty string if address is null
              } else {
                  console.error('Could not retrieve user data: Invalid response or data structure');
              }
          }
      } catch (error) {
          console.error('Failed to fetch user data:', error);
          error.value = 'Failed to fetch user data. Please try again.';
      }
  };
  
  const updateProfile = async () => {
    error.value = null;
    successMessage.value = null;
    try {
      const token = getAccessToken();
      const response = await axios.post('http://localhost:8000/api/user/update', form.value, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
  
      successMessage.value = 'Profile updated successfully!';
    } catch (e) {
      error.value = e.response?.data?.message || 'Failed to update profile. Please try again.';
      console.error('Update profile error:', e);
    }
  };
  
  const changePassword = async () => {
      error.value = null;
      successMessage.value = null;
      if (passwordForm.value.new_password !== passwordForm.value.new_password_confirmation) {
          error.value = 'New password and confirm password do not match.';
          return;
      }
  
      try {
          const token = getAccessToken();
          const response = await axios.post('http://localhost:8000/api/user/password', passwordForm.value, {
              headers: {
                  Authorization: `Bearer ${token}`
              }
          });
  
          successMessage.value = 'Password changed successfully!';
          alert('Password changed successfully!'); // Alert the user
          // Clear the form
          passwordForm.value.current_password = '';
          passwordForm.value.new_password = '';
          passwordForm.value.confirm_password = '';
  
      } catch (e) {
          error.value = e.response?.data?.message || 'Failed to change password. Please try again.';
          console.error('Change password error:', e);
      }
  };


  
  const changeEmail = async () => {
      error.value = null;
      successMessage.value = null;
  
      try {
          const token = getAccessToken();
          const response = await axios.post('http://localhost:8000/api/user/email', emailForm.value, {
              headers: {
                  Authorization: `Bearer ${token}`
              }
          });
  
          successMessage.value = 'Email changed successfully!  You may need to re-login.';
          emailForm.value.new_email = ''; // Clear form
      } catch (e) {
          error.value = e.response?.data?.message || 'Failed to change email. Please try again.';
          console.error('Change email error:', e);
      }
  };
  
  onMounted(() => {
      fetchUserData();
  });
  </script>
  
  <style scoped>
  .settings-container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
  }
  
  .form-group {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #eee;
    border-radius: 5px;
    background-color: white;
  }
  .home{
    margin: 20px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }
  
  input[type="text"],
  input[type="email"],
  input[type="password"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Important: Keeps padding inside the width */
  }
  
  button {
    background-color: #5cb85c;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #449d44;
  }
  
  .error-message {
    color: red;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid red;
    background-color: #ffe6e6;
  }
  
  .success-message {
    color: green;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid green;
    background-color: #e6ffe6;
  }
  </style>