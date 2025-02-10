<template>
  <div class="reset-password-container">
    <div class="auth-card">
      <div class="auth-header">
       
        <h1 class="auth-title">Reset Your Password</h1>
      </div>
      
      <form @submit.prevent="resetPassword" class="auth-form">
        <div class="input-group">
          <label for="password">New Password</label>
          <input 
            type="password" 
            id="password" 
            v-model="password" 
            required
            placeholder="Enter new password"
            class="form-input"
          >
          <span class="input-icon">🔒</span>
        </div>

        <div class="input-group">
          <label for="password_confirmation">Confirm Password</label>
          <input 
            type="password" 
            id="password_confirmation" 
            v-model="password_confirmation" 
            required
            placeholder="Confirm your password"
            class="form-input"
          >
          <span class="input-icon">✅</span>
        </div>

        <button type="submit" class="auth-button">
          <span class="button-text">Reset Password</span>
          <span class="button-icon">→</span>
        </button>
      </form>

      <div class="auth-message" :class="{ error: isError, success: !isError }">
        <span class="message-icon">{{ isError ? '⚠️' : '✓' }}</span>
        {{ message }}
      </div>
    </div>
  </div>
</template>

<style scoped>
.reset-password-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8fafc;
  padding: 2rem;
}

.auth-card {
  background: white;
  width: 100%;
  max-width: 440px;
  padding: 2.5rem;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  transition: transform 0.3s ease;
}

.auth-header {
  text-align: center;
  margin-bottom: 2rem;
}

.logo {
  height: 48px;
  margin-bottom: 1.5rem;
}

.auth-title {
  font-size: 1.5rem;
  color: #1e293b;
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.input-group {
  position: relative;
}

.input-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #64748b;
  font-size: 0.875rem;
  font-weight: 500;
}

.form-input {
  width: 100%;
  padding: 0.875rem 2.5rem 0.875rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.form-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
  outline: none;
}

.input-icon {
  position: absolute;
  right: 1rem;
  bottom: 0.875rem;
  color: #94a3b8;
}

.auth-button {
  background: #2563eb;
  color: white;
  padding: 1rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: all 0.3s ease;
}

.auth-button:hover {
  background: #1d4ed8;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
}

.button-icon {
  font-size: 1.2rem;
  margin-left: 0.5rem;
}

.auth-message {
  margin-top: 1.5rem;
  padding: 1rem;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.9rem;
}

.auth-message.success {
  background: #dcfce7;
  color: #166534;
  border: 1px solid #22c55e;
}

.auth-message.error {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #ef4444;
}

.message-icon {
  font-size: 1.2rem;
}

@media (max-width: 480px) {
  .auth-card {
    padding: 1.5rem;
  }
  
  .auth-title {
    font-size: 1.25rem;
  }
}
</style>

  
<script>
import axios from "axios";

export default {
  data() {
    return {
      password: "",
      password_confirmation: "",
      message: "",
      isError: false, // Tracks if the message is an error
    };
  },
  methods: {
    async resetPassword() {
      try {
        // Get token and email from the route parameters
        const token = this.$route.query.token;
        const email = this.$route.query.email;

        // Ensure token and email are available
        if (!token || !email) {
          this.isError = true;
          this.message = "Invalid reset password link.";
          return;
        }

        // Make API request to reset password
        const response = await axios.post("http://localhost:8000/api/auth/password/reset", {
          token,
          email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });

        // Handle success response
        this.isError = false;
        this.message = response.data.message;

        // Redirect to login after success
        setTimeout(() => {
          this.$router.push("/login");
        }, 3000);
      } catch (error) {
        // Handle error response
        this.isError = true;

        // Display backend validation errors
        if (error.response && error.response.data && error.response.data.errors) {
          this.message = Object.values(error.response.data.errors).join(" ");
        } else {
          this.message = "Failed to reset password. Please try again.";
        }
      }
    },
  },
};
</script>

