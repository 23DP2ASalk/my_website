<template>
  <v-app>
    <v-app-bar color="error" dark prominent>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      
      <v-toolbar-title class="text-h5 font-weight-bold">
        <v-icon class="mr-2">mdi-shield-crown</v-icon>
        Admin Panel
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn href="/dashboard" variant="text" prepend-icon="mdi-home" class="mr-2">
        Dashboard
      </v-btn>

      <v-btn icon @click="toggleTheme">
        <v-icon>{{ isDark ? 'mdi-weather-sunny' : 'mdi-weather-night' }}</v-icon>
      </v-btn>

      <v-menu>
        <template v-slot:activator="{ props }">
          <v-btn icon v-bind="props">
            <v-avatar size="40" color="secondary">
              <span class="text-h6">{{ userInitial }}</span>
            </v-avatar>
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title>{{ userName }}</v-list-item-title>
            <v-list-item-subtitle>Administrator</v-list-item-subtitle>
          </v-list-item>
          <v-divider></v-divider>
          <v-list-item href="/dashboard">
            <template v-slot:prepend><v-icon>mdi-home</v-icon></template>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item>
          <v-list-item href="/admin">
            <template v-slot:prepend><v-icon>mdi-shield-crown</v-icon></template>
            <v-list-item-title>Admin Panel</v-list-item-title>
          </v-list-item>
          <v-list-item @click="logout">
            <template v-slot:prepend><v-icon>mdi-logout</v-icon></template>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" temporary>
      <v-list>
        <v-list-item prepend-icon="mdi-view-dashboard" title="Admin Dashboard" href="/admin"></v-list-item>
        <v-list-item prepend-icon="mdi-account-group" title="Manage Users" href="/admin/users"></v-list-item>
        <v-divider></v-divider>
        <v-list-item prepend-icon="mdi-home" title="Back to Dashboard" href="/dashboard"></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-main>
      <v-container fluid class="hero-section pa-8">
        <v-row justify="center">
          <v-col cols="12" md="10" class="text-center">
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 gradient-text">
              Admin Dashboard
            </h1>
            <p class="text-h6 text-medium-emphasis">
              Sistema pārvaldības panelis
            </p>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="my-6">
        <!-- Stats Cards -->
        <v-row>
          <v-col cols="12" md="3">
            <v-card elevation="4" class="stat-card" color="primary">
              <v-card-text class="text-center pa-6 text-white">
                <v-icon icon="mdi-account-group" size="48" class="mb-3"></v-icon>
                <h2 class="text-h3">{{ totalUsers }}</h2>
                <p class="text-h6">Lietotāji</p>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card elevation="4" class="stat-card" color="error">
              <v-card-text class="text-center pa-6 text-white">
                <v-icon icon="mdi-shield-crown" size="48" class="mb-3"></v-icon>
                <h2 class="text-h3">{{ totalAdmins }}</h2>
                <p class="text-h6">Administratori</p>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card elevation="4" class="stat-card" color="success">
              <v-card-text class="text-center pa-6 text-white">
                <v-icon icon="mdi-chart-line" size="48" class="mb-3"></v-icon>
                <h2 class="text-h3">{{ totalStatistics }}</h2>
                <p class="text-h6">Statistikas</p>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card elevation="4" class="stat-card" color="info">
              <v-card-text class="text-center pa-6 text-white">
                <v-icon icon="mdi-chart-bar" size="48" class="mb-3"></v-icon>
                <h2 class="text-h3">{{ Math.round(totalStatistics / totalUsers) }}</h2>
                <p class="text-h6">Vidēji/Lietotājs</p>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- Recent Users -->
        <v-row class="mt-6">
          <v-col cols="12" md="6">
            <v-card elevation="4">
              <v-card-title class="bg-primary text-white">
                <v-icon class="mr-2">mdi-account-clock</v-icon>
                Jaunākie Lietotāji
              </v-card-title>
              <v-list>
                <v-list-item
                  v-for="user in recentUsers"
                  :key="user.id"
                >
                  <template v-slot:prepend>
                    <v-avatar color="primary">
                      <span class="text-white">{{ user.name.charAt(0) }}</span>
                    </v-avatar>
                  </template>
                  <v-list-item-title>{{ user.name }}</v-list-item-title>
                  <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
                  <template v-slot:append>
                    <v-chip v-if="user.is_admin" color="error" size="small">Admin</v-chip>
                    <v-chip v-else color="info" size="small">User</v-chip>
                  </template>
                </v-list-item>
              </v-list>
              <v-card-actions>
                <v-btn href="/admin/users" color="primary" block>
                  Skatīt Visus Lietotājus
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <!-- Statistics by Sport -->
          <v-col cols="12" md="6">
            <v-card elevation="4">
              <v-card-title class="bg-success text-white">
                <v-icon class="mr-2">mdi-chart-pie</v-icon>
                Statistikas pēc Sporta
              </v-card-title>
              <v-card-text class="pa-6">
                <div v-for="sport in statisticsBySport" :key="sport.sport_type" class="mb-4">
                  <div class="d-flex justify-space-between mb-2">
                    <span class="font-weight-bold">{{ sport.sport_type }}</span>
                    <span>{{ sport.count }} ieraksti</span>
                  </div>
                  <v-progress-linear
                    :model-value="(sport.count / totalStatistics) * 100"
                    :color="getSportColor(sport.sport_type)"
                    height="20"
                  >
                    <template v-slot:default>
                      <strong>{{ Math.round((sport.count / totalStatistics) * 100) }}%</strong>
                    </template>
                  </v-progress-linear>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- Top Users -->
        <v-row class="mt-6">
          <v-col cols="12">
            <v-card elevation="4">
              <v-card-title class="bg-info text-white">
                <v-icon class="mr-2">mdi-trophy</v-icon>
                Aktīvākie Lietotāji
              </v-card-title>
              <v-table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Vārds</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Statistikas Skaits</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(user, index) in usersWithStats" :key="user.id">
                    <td>{{ index + 1 }}</td>
                    <td>
                      <v-avatar size="32" color="primary" class="mr-2">
                        <span class="text-white text-caption">{{ user.name.charAt(0) }}</span>
                      </v-avatar>
                      {{ user.name }}
                    </td>
                    <td>{{ user.email }}</td>
                    <td>
                      <v-chip v-if="user.is_admin" color="error" size="small">Admin</v-chip>
                      <v-chip v-else color="info" size="small">User</v-chip>
                    </td>
                    <td>
                      <v-chip color="success">{{ user.statistics_count }}</v-chip>
                    </td>
                  </tr>
                </tbody>
              </v-table>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <v-footer color="error" class="pa-6">
      <v-container>
        <v-row align="center">
          <v-col cols="12" class="text-center text-white">
            <p class="mb-1">&copy; 2026 Stats Tracker - Admin Panel</p>
          </v-col>
        </v-row>
      </v-container>
    </v-footer>
  </v-app>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useTheme } from 'vuetify';

const theme = useTheme();

const props = defineProps({
  totalUsers: Number,
  totalAdmins: Number,
  totalStatistics: Number,
  recentUsers: Array,
  statisticsBySport: Array,
  usersWithStats: Array,
  userName: String,
  csrfToken: String
});

const drawer = ref(false);

const userInitial = computed(() => {
  return props.userName ? props.userName.charAt(0).toUpperCase() : 'A';
});

const isDark = computed(() => theme.global.current.value.dark);

const toggleTheme = () => {
  theme.global.name.value = isDark.value ? 'light' : 'dark';
  localStorage.setItem('theme', theme.global.name.value);
};

const getSportColor = (sport) => {
  const colors = {
    'Basketball': 'orange',
    'Football': 'green',
    'Volleyball': 'blue'
  };
  return colors[sport] || 'primary';
};

const logout = () => {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '/logout';
  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = props.csrfToken;
  form.appendChild(csrfInput);
  document.body.appendChild(form);
  form.submit();
};

onMounted(() => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    theme.global.name.value = savedTheme;
  }
});
</script>

<style scoped>
.hero-section {
  background: linear-gradient(135deg, #fee 0%, #fdd 100%);
  min-height: 250px;
  display: flex;
  align-items: center;
  animation: fadeIn 0.8s ease-in-out;
}

.v-theme--dark .hero-section {
  background: linear-gradient(135deg, #5f1e1e 0%, #2a0f0f 100%);
}

.gradient-text {
  background: linear-gradient(135deg, #dc2626, #991b1b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: slideInDown 0.8s ease-in-out;
}

.stat-card {
  animation: fadeInUp 0.6s ease-in-out;
  animation-fill-mode: both;
  transition: transform 0.3s;
}

.stat-card:hover {
  transform: translateY(-8px);
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInDown {
  from { opacity: 0; transform: translateY(-50px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
