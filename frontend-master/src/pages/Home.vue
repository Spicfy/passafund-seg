

<script setup>
/* eslint-disable */
import { onMounted, ref, watchEffect } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const message = ref('Welcome to the Home Page!');


onMounted(() => {
    fetchUserData();
    fetchPosts();
})

const router = useRouter();
const isLoggedIn = ref(false); // Add a reactive state for login status

const TOKEN_KEY = 'access_token';


const posts = ref([
  { id: 1, title: 'First Post', message: 'This is the first post.', type: 'news', author: 'John Doe' },
  { id: 2, title: 'Update', message: 'An important update is available.', type: 'update', author: 'Jane Smith' },
  { id: 3, title: 'Project Task', message: 'Complete task X by Friday.', type: 'task', author: 'Peter Jones' },
    { id: 4, title: 'Another Post', message: 'This is another post.', type: 'news', author: 'Alice Brown' },
    { id: 5, title: 'Update', message: 'A new update is now live.', type: 'update', author: 'Bob White' },
    { id: 6, title: 'Task Reminder', message: 'Don\'t forget to complete task Y.', type: 'task', author: 'Eve Green' },
    { id: 7, title: 'Important News', message: 'Breaking news: something important happened.', type: 'news', author: 'Sam Black' },
    { id: 8, title: 'Update', message: 'The latest update is now available.', type: 'update', author: 'Max Grey' },
    { id: 9, title: 'Task Deadline', message: 'Task Z must be completed by the end of the week.', type: 'task', author: 'Lucy Red' },
    { id: 10, title: 'Final Post', message: 'This is the final post.', type: 'news', author: 'Tom Pink' }
    
]);

const newPost = ref({
    title: '',
    message: '',
    type: 'news', // Default type
    author: '' // This should be set to the user who created it
});

const firstName = ref('');
const lastName = ref('');
const editingPost = ref(null);
const loggedUserId = ref(null);

const postTypes = ['news', 'update', 'task']; // Define the allowed post types


const getAccessToken = () => {
    const cookieString = document.cookie;
    
    const cookies = cookieString.split(';');
    console.log('Cookies:', cookies);

    for(let i = 0; i < cookies.length; i++){
        const cookie = cookies[i].trim();
        if(cookie.startsWith(TOKEN_KEY + '=')){
            return cookie.substring((TOKEN_KEY).length +1);
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
                            Authorization: `Bearer ${token}`  // Important: Send token as Bearer
                        }
                    });

                  
                    if (response.data && response.data.data.first_name && response.data.data.last_name) {
                        firstName.value = response.data.data.first_name;
                        lastName.value = response.data.data.last_name;
                    } else {
                        console.error('Could not retrieve user data dumb');
                        console.log(response.data)

                        console.log('first name ' + response.data.first_name)
                        console.log('last name ' + response.data.last_name)

                    }
                }
            } catch (error) {
                console.error('Failed to fetch user data:', error); // Log the error object for details
            }
        };

const removeToken = () => {
    document.cookie = `${TOKEN_KEY}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`
}
const checkLoginStatus = () => {
    console.log('Checking login status...');
    const token = getAccessToken();
    console.log('Access token:', token);

    isLoggedIn.value = token !== null;
}




const goToLogin = () => {
    router.push('/login');
};

const goToRegister = () => {
    router.push('/register');
};

const goToCreatePost = () => {
    router.push('/create-post');
}

const logout = async () => {
    try{
        const token = getAccessToken();
        const response = await fetch('http://localhost:8000/api/auth/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if(response.ok){
            removeToken();
            isLoggedIn.value = false;
            router.push('/');
        }else{
            console.error('Logout failed:', response.statusText);
            alert('Logout failed: ' + response.statusText);
        }
    }catch(error){
        console.error('Logout failed:', error.message);
        alert('Logout failed: ' + error.message);
    }
    
};
const goToSettings = () => {
    router.push('/settings')
}

const fetchPosts = async () => {
    try{
        const response = await fetch('http://localhost:8000/api/posts');
        if(response.ok){
            const data = await response.json();
            posts.value = data;
            console.log('Posts:', posts.value);
        } else{
            console.error('Failed to fetch posts:', response.statusText);
        }
    }catch(error){
        console.error('Failed to fetch posts:', error.message);
    }
}


const updatePost = async () => {
    try{
        const token = getAccessToken();
        const response = await fetch(`http://localhost:8000/api/posts/${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(editingPost.value)
        });
        if(response.ok){
            fetchPosts();
            editingPost.value = null;
        }else{
            console.error('Failed to update post:', response.statusText);
        }
    }catch(error){
        console.error('Failed to update post:', error.message);
    }
}

const deletePost = async (id) => {
    try{
        const token = getAcessToken();
        const response = await fetch(`http://localhost:8000/api/posts/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        if(response.ok){
            fetchPosts();
        } else{
            console.log('Error deleting Post:', error);
        }
    }catch(error){
        console.error('Error deleting Post:', error.message);
    }
}

watchEffect(() => {
    checkLoginStatus();
})
</script>

<template>
    <div class="container">
      <header class="top">
        <p>Passafund software engineering assignment v1.1</p>
          <div class="top-left-buttons">
              <button v-if="!isLoggedIn" @click="goToLogin">Login</button>
              <button v-if="!isLoggedIn" @click="goToRegister">Register</button>
              <button v-if="isLoggedIn" @click="logout">Logout</button>
              <button v-if="isLoggedIn" @click="goToSettings">User Settings</button>
          </div>
      
      </header>
  
      <main class="home-container">
        <h1>{{ message }}</h1>
        <h2 v-if="isLoggedIn && firstName && lastName"> 
        {{ firstName + " " + lastName }}
      </h2> 
      </main>

     
    <div class="pre-posts"><h1>Posts</h1>
    <button v-if="isLoggedIn"  @click="goToCreatePost">Create Post</button>
    </div>
      <div class="post-grid">
        <div v-for="post in posts" :key="post.id" class="post-card">
            <h3>{{post.title}}</h3>
            <p>Message: {{ post.message }}</p>
            <p class="post-type">Type: {{post.type}}</p>
            <p class="post-author">Author: {{ post.user ? post.user.first_name + ' ' + post.user.last_name : 'Unknown' }}</p>

            <div v-if="isLoggedIn && post.user_id === loggedUserId">
                <button  @click="editingPost = { ...post}">Edit</button>
                <button @click="deletePost(post.id)">Delete</button>
            </div>
        </div>
      </div>



    </div>
  </template>
  
  <style scoped>

  .pre-posts{
    height: 100px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #007bff;

  }
  .pre-posts >*{
    margin: 20px;
  }
  .container {
      overflow-x: hidden;
      max-width: 100%;
      overflow: hidden;
      position: relative;
     
  }
  .top {
    height: 100px;
      margin: 0;
      color: #333;
      width: 100%;
      background-color: #007bff;
      padding: 20px; /* Add some padding */
      position: relative; /* Needed for absolute positioning of buttons */
  }
  
  .top p {
      color: white; /* Change text to white for visibility */
      font-size: 20px;
      text-align: center;
      margin: 0; /* Reset paragraph margins */
  }
  
  .top-left-buttons {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      gap: 10px;
      
  
  }
  .home-container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: calc(100vh - 200px); /* Use remaining height */
      background-color: #f0f0f0;
  
  }
  
  button {
      padding: 10px 20px;
      background-color: white;
      color: black;
      border: none;
      border-radius: 5px;
      cursor: pointer;
  }
  
  button:hover {
      background-color: #0056b3;
  }
  
  h1 {
      color: #333;
      margin: 0;
      font-size: 2em;
  }
  .post-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 0;
    padding: 20px;
    background-color: #555;


  
}
.post-card > * {
    text-align: center;
}

.post-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 300px;
    background: white;
    padding: 15px;
    border: 1px solid #f0f0f0;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

    margin: 40px;
}

  </style>