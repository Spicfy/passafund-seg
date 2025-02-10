<script>
/* eslint-disable */
import axios from 'axios';
import { ref, onMounted } from 'vue';
import {useRouter} from 'vue-router';

export default {
    setup() {
        const title = ref('');
        const message = ref('');
        const type = ref('news');
        const firstName = ref('');
        const lastName = ref('');

        const router = useRouter();
        const TOKEN_KEY = 'access_token'; // Define your actual token key

        const getAccessToken = () => {
            const cookieString = document.cookie; // Correct typo

            const cookies = cookieString.split(';');
            console.log('Cookies:', cookies);

            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith(TOKEN_KEY + '=')) {
                    return cookie.substring((TOKEN_KEY).length + 1);
                }
            }
            return null;
        };

        const fetchUserData = async () => {
            try {
                const token = getAccessToken();
                if (token) {
                    const response = await axios.get('http://localhost:8000/api/user', {
                        headers: {
                            Authorization: `Bearer ${token}`  // Important: Send token as Bearer
                        }
                    });

                    if (response.data && response.data.first_name && response.data.last_name) {
                        firstName.value = response.data.first_name;
                        lastName.value = response.data.last_name;
                    } else {
                        console.error('Could not retrieve user data');
                    }
                }
            } catch (error) {
                console.error('Failed to fetch user data:', error); // Log the error object for details
            }
        };

        const createPost = async () => {
            try {
                const token = getAccessToken();
                if(!token){
                    console.error('No token found');
                    return;
                }
                const response = await axios.post('http://localhost:8000/api/posts', {
                    title: title.value,
                    message: message.value,
                    type: type.value,
                    authorFirstName: firstName.value,  // Include first name
                    authorLastName: lastName.value    // Include last name

                }, {  // Add headers for authorization
                    headers: {
                        Authorization: `Bearer ${token}` // Send the token in the header
                    }
                });

                if (response.status === 201) {  // Check for 201 Created status
                   // fetchPosts(); // Assuming you have a fetchPosts function to refresh the list
                   title.value = '';
                   message.value = '';
                   type.value = 'news';
                    console.log('Post created successfully');
                    router.push('/'); // Redirect to home page
                } else {
                    console.error('Failed to create post:', response.status, response.data);
                }
            } catch (error) {
                console.error('Failed to create post:', error); // Log the error object
            }
        };

        onMounted(fetchUserData);  // Fetch user data on component mount

        return {
            title,
            message,
            type,
            firstName,
            lastName,
            createPost
        };
    }
};
</script>

<template>
    
        <form class="form-container" @submit.prevent="createPost">
            <div class="form_title">New post</div>
            <div class="form_field title">
                <label for="title">Title:</label>
                <input id="title" v-model="title" type="text" required />  <!-- Corrected type to text -->
            </div>
            <div class="form_field txt">
                <textarea id="message" v-model="message" required placeholder="enter a message" />
            </div>

            <div class="form_field type">
                <label for="type">Type:</label>
                <select id="type" v-model="type" required>
                    <option value="news">News</option>
                    <option value="update">Update</option>
                    <option value="task">task</option>
                </select>
            </div>
            <button class="form_submit" type="submit">Create post</button>

            <p class="to-login">
                return to home page
                <router-link to="/">home</router-link>
            </p>
        </form>
  
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

.form_title {
    font-size: 38px;
    color: #555;
    margin-bottom: 7px;
    margin-left: 120px;
}

.form_field {
    margin-bottom: 20px;
}

#title {
    width: 10rem;
}

.form_field > * {
    margin: 10px 0;
}

.title,
.txt,
.type {
    margin-left: 100px;
}

label {
    margin-bottom: 10px;
    font-size: 15px;
    font-weight: 600;
    color: #777;
}

.type > * {
    margin: 10px;
}

textarea {
    background-color: #dddddd;
    color: #666666;
    padding: 1em;
    border-radius: 10px;
    border: 2px solid transparent;
    outline: none;
    font-family: 'Heebo', sans-serif;
    font-weight: 500;
    height: auto; /* Allow textarea to adjust height */
    width: 100%; /* Make it fill the container */
    resize: vertical;
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