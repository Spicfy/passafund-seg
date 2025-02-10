<template>
  <v-app>
    <v-app-bar color="primary" density="compact">
      <template v-slot:prepend>
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      </template>
      <v-app-bar-title>Dashboard</v-app-bar-title>
      <v-spacer></v-spacer>
      <v-btn icon="mdi-account-circle"></v-btn>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" temporary>
      <v-list density="compact" nav>
        <v-list-item
          v-for="item in navItems"
          :key="item.title"
          :value="item.value"
          :prepend-icon="item.icon"
          :title="item.title"
        ></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-main>
      <v-container class="py-8">
        <v-row>
          <v-col cols="12" md="8">
            <v-card class="pa-4 mb-4" elevation="2">
              <h2 class="text-h5 mb-4">Recent Activity</h2>
              <v-data-table
                :headers="headers"
                :items="items"
                hide-default-footer
                class="elevation-0"
              ></v-data-table>
            </v-card>
          </v-col>
          
          <v-col cols="12" md="4">
            <v-card class="pa-4" elevation="2">
              <h2 class="text-h5 mb-4">Quick Actions</h2>
              <div class="d-flex flex-column gap-3">
                <v-btn 
                  color="secondary" 
                  variant="tonal"
                  prepend-icon="mdi-plus"
                >
                  New Project
                </v-btn>
                <v-btn 
                  color="success" 
                  variant="tonal"
                  prepend-icon="mdi-chart-bar"
                >
                  View Analytics
                </v-btn>
                <v-btn 
                  color="info" 
                  variant="tonal"
                  prepend-icon="mdi-cog"
                >
                  Settings
                </v-btn>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from 'vue';

const drawer = ref(false);
const navItems = [
  { title: 'Home', icon: 'mdi-home', value: 'home' },
  { title: 'Projects', icon: 'mdi-folder', value: 'projects' },
  { title: 'Analytics', icon: 'mdi-chart-line', value: 'analytics' },
];

const headers = [
  { title: 'Date', key: 'date' },
  { title: 'Activity', key: 'activity' },
  { title: 'Status', key: 'status' },
];

const items = [
  { date: '2024-02-01', activity: 'Project Update', status: 'Completed' },
  { date: '2024-02-02', activity: 'Client Meeting', status: 'Pending' },
  { date: '2024-02-03', activity: 'Code Review', status: 'In Progress' },
];
</script> 