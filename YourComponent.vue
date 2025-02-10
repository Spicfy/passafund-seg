<template>
    <div class="container">
      <header class="header">
        <div class="header-content">
          <div class="branding">
            <span class="version">Passafund v1.1</span>
            <h1 class="app-title">Project Hub</h1>
          </div>
          <div class="nav-buttons">
              <button v-if="!isLoggedIn" @click="goToLogin" class="btn secondary">Login</button>
              <button v-if="!isLoggedIn" @click="goToRegister" class="btn primary">Register</button>
              <button v-if="isLoggedIn" @click="logout" class="btn icon-btn">
                <svg class="icon" viewBox="0 0 24 24"><path d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 0 1 2 2v2h-2V4H5v16h9v-2h2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9z"/></svg>
                Logout
              </button>
              <button v-if="isLoggedIn" @click="goToSettings" class="btn icon-btn">
                <svg class="icon" viewBox="0 0 24 24"><path d="M12 15.5A3.5 3.5 0 0 1 8.5 12 3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97 0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1 0 .33.03.65.07.97l-2.11 1.66c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66z"/></svg>
                Settings
              </button>
          </div>
        </div>
      </header>
  
      <main class="content">
        <div class="hero-section">
          <h2 class="welcome-message">{{ message }}</h2>
          <div v-if="isLoggedIn && firstName && lastName" class="user-greeting">
            <div class="avatar">{{ firstName[0] }}{{ lastName[0] }}</div>
            <h3 class="user-name">Welcome back, {{ firstName }} {{ lastName }}</h3>
          </div>
        </div>

        <div class="posts-header">
          <h2 class="section-title">Latest Updates</h2>
          <button v-if="isLoggedIn" @click="goToCreatePost" class="btn primary create-btn">
            <svg class="icon" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            New Post
          </button>
        </div>
        
        <div class="post-grid" v-if="posts && posts.length > 0">
          <div v-for="post in posts" :key="post.id" class="post-card" :class="`type-${post.type}`">
            <template v-if="editingPost && editingPost.id === post.id"> 
              <div class="edit-form">
                <input type="text" v-model="editingPost.title" class="edit-input" placeholder="Post Title" />
                <textarea v-model="editingPost.message" class="edit-textarea" placeholder="Post Content"></textarea>
                <select v-model="editingPost.type" class="edit-select">
                  <option value="news">News</option>
                  <option value="update">Update</option>
                  <option value="task">Task</option>
                </select>
                <div class="form-actions">
                  <button @click="updatePost(post)" class="btn primary">Save Changes</button>
                  <button @click="cancelEdit" class="btn secondary">Cancel</button>
                </div>
              </div>
            </template>
            <template v-else>
              <div class="post-header">
                <div class="post-type">{{ post.type }}</div>
                <h3 class="post-title">{{ post.title }}</h3>
              </div>
              <p class="post-content">{{ post.message }}</p>
              <div class="post-footer">
                <div class="author-info">
                  <div class="author-avatar">{{ post.user ? post.user.first_name[0] + post.user.last_name[0] : '??' }}</div>
                  <span class="author-name">{{ post.user ? post.user.first_name + ' ' + post.user.last_name : 'Unknown' }}</span>
                </div>
                <div v-if="isLoggedIn && post.user_id === loggedUserId" class="post-actions">
                  <button class="icon-btn" @click="setEditingPost(post)" title="Edit">
                    <svg class="icon-sm" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                  </button>
                  <button class="icon-btn" @click="deletePost(post.id)" title="Delete">
                    <svg class="icon-sm" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                  </button>
                </div>
              </div>
            </template>
          </div>
        </div>
      </main>
    </div>
</template>

<style scoped>
:root {
  --primary-color: #2563eb;
  --secondary-color: #3b82f6;
  --success-color: #10b981;
  --warning-color: #f59e0b;
  --danger-color: #ef4444;
  --text-color: #1e293b;
  --background-color: #f8fafc;
  --card-bg: #ffffff;
  --border-radius: 8px;
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
  --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.container {
  min-height: 100vh;
  background-color: var(--background-color);
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  color: var(--text-color);
}

.header {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  box-shadow: var(--shadow-md);
  padding: 1rem 0;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 2rem;
}

.branding {
  display: flex;
  flex-direction: column;
}

.app-title {
  color: white;
  font-size: 1.5rem;
  font-weight: 600;
}

.version {
  color: rgba(255,255,255,0.8);
  font-size: 0.75rem;
  margin-bottom: 0.25rem;
}

.nav-buttons {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: var(--border-radius);
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s ease;
  border: 1px solid transparent;
  cursor: pointer;
}

.btn.primary {
  background-color: white;
  color: var(--primary-color);
}

.btn.secondary {
  background-color: transparent;
  color: white;
  border: 1px solid rgba(255,255,255,0.3);
}

.btn:hover {
  transform: translateY(-1px);
  box-shadow: var(--shadow-sm);
}

.icon-btn {
  background: none;
  padding: 0.5rem;
  border-radius: 50%;
}

.icon-btn:hover {
  background-color: rgba(255,255,255,0.1);
}

.icon {
  width: 1.25rem;
  height: 1.25rem;
  fill: currentColor;
}

.icon-sm {
  width: 1rem;
  height: 1rem;
}

.content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.hero-section {
  background: var(--card-bg);
  border-radius: var(--border-radius);
  padding: 3rem 2rem;
  margin: 2rem 0;
  text-align: center;
  box-shadow: var(--shadow-md);
}

.welcome-message {
  color: var(--text-color);
  font-size: 2rem;
  margin-bottom: 1.5rem;
}

.user-greeting {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
}

.avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: var(--primary-color);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}

.user-name {
  color: var(--text-color);
  font-size: 1.25rem;
  font-weight: 500;
}

.posts-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.section-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--text-color);
}

.create-btn {
  padding: 0.75rem 1.5rem;
}

.post-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.post-card {
  background: var(--card-bg);
  border-radius: var(--border-radius);
  padding: 1.5rem;
  box-shadow: var(--shadow-md);
  transition: transform 0.2s ease;
  border-left: 4px solid var(--primary-color);
}

.post-card:hover {
  transform: translateY(-2px);
}

.post-card.type-news {
  border-color: var(--primary-color);
}

.post-card.type-update {
  border-color: var(--warning-color);
}

.post-card.type-task {
  border-color: var(--success-color);
}

.post-header {
  margin-bottom: 1rem;
}

.post-type {
  font-size: 0.75rem;
  text-transform: uppercase;
  color: var(--primary-color);
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.post-title {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.post-content {
  color: var(--text-color);
  opacity: 0.9;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.post-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
}

.author-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.author-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #cbd5e1;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 500;
}

.author-name {
  font-size: 0.875rem;
  color: var(--text-color);
}

.post-actions {
  display: flex;
  gap: 0.5rem;
}

.edit-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  width: 100%;
}

.edit-input, .edit-textarea, .edit-select {
  width: 100%;
  padding: 0.75rem;
  color: var(--text-color);
  background-color: white;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-family: inherit;
  font-size: 0.875rem;
}

.edit-input:focus, .edit-textarea:focus, .edit-select:focus {
  outline: 2px solid var(--primary-color);
  outline-offset: 2px;
}

.edit-input::placeholder,
.edit-textarea::placeholder {
  color: #94a3b8;
  opacity: 0.8;
}

.form-actions {
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .post-grid {
    grid-template-columns: 1fr;
  }
  
  .content {
    padding: 1rem;
  }
}
</style> 