<template>
  <v-app>
    <v-app-bar color="error" dark prominent>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      
      <v-toolbar-title class="text-h5 font-weight-bold">
        <v-icon class="mr-2">mdi-shield-crown</v-icon>
        User Management
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn href="/admin" variant="text" prepend-icon="mdi-view-dashboard" class="mr-2">
        Admin Dashboard
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
          <v-list-item href="/dashboard"><template v-slot:prepend><v-icon>mdi-home</v-icon></template><v-list-item-title>Dashboard</v-list-item-title></v-list-item>
          <v-list-item href="/admin"><template v-slot:prepend><v-icon>mdi-shield-crown</v-icon></template><v-list-item-title>Admin Panel</v-list-item-title></v-list-item>
          <v-list-item @click="logout"><template v-slot:prepend><v-icon>mdi-logout</v-icon></template><v-list-item-title>Logout</v-list-item-title></v-list-item>
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
              Lietotāju Pārvaldība
            </h1>
            <p class="text-h6 text-medium-emphasis">
              Pārvaldīt lietotājus un admin tiesības
            </p>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="my-6">
        <v-row>
          <v-col cols="12">
            <v-card elevation="4">
              <v-card-title class="bg-error text-white d-flex align-center">
                <v-icon class="mr-2">mdi-account-group</v-icon>
                Visi Lietotāji ({{ users.total }})
              </v-card-title>
              
              <v-table>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Lietotājs</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Statistikas</th>
                    <th>Reģistrācijas Datums</th>
                    <th>Darbības</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>
                      <div class="d-flex align-center">
                        <v-avatar size="32" :color="user.is_admin ? 'error' : 'primary'" class="mr-2">
                          <span class="text-white text-caption">{{ user.name.charAt(0) }}</span>
                        </v-avatar>
                        {{ user.name }}
                      </div>
                    </td>
                    <td>{{ user.email }}</td>
                    <td>
                      <v-chip v-if="user.is_admin" color="error" size="small" prepend-icon="mdi-shield-crown">
                        Admin
                      </v-chip>
                      <v-chip v-else color="info" size="small" prepend-icon="mdi-account">
                        User
                      </v-chip>
                    </td>
                    <td>
                      <v-chip color="success" size="small">
                        {{ user.statistics_count }}
                      </v-chip>
                    </td>
                    <td>{{ formatDate(user.created_at) }}</td>
                    <td>
                      <v-btn
                        size="small"
                        :color="user.is_admin ? 'warning' : 'success'"
                        variant="text"
                        @click="confirmToggleAdmin(user)"
                      >
                        <v-icon start>{{ user.is_admin ? 'mdi-shield-off' : 'mdi-shield-check' }}</v-icon>
                        {{ user.is_admin ? 'Remove Admin' : 'Make Admin' }}
                      </v-btn>
                      
                      <v-btn
                        size="small"
                        color="error"
                        variant="text"
                        @click="confirmDelete(user)"
                        :disabled="user.id === currentUserId"
                      >
                        <v-icon start>mdi-delete</v-icon>
                        Delete
                      </v-btn>
                    </td>
                  </tr>
                </tbody>
              </v-table>

              <v-card-actions v-if="users.last_page > 1" class="justify-center">
                <v-pagination
                  :length="users.last_page"
                  :model-value="users.current_page"
                  @update:modelValue="changePage"
                  total-visible="7"
                ></v-pagination>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <!-- Toggle Admin Dialog -->
    <v-dialog v-model="toggleAdminDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5">
          {{ selectedUser?.is_admin ? 'Remove Admin Rights' : 'Grant Admin Rights' }}
        </v-card-title>
        <v-card-text>
          Are you sure you want to {{ selectedUser?.is_admin ? 'remove admin rights from' : 'grant admin rights to' }} 
          <strong>{{ selectedUser?.name }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="toggleAdminDialog = false">Cancel</v-btn>
          <v-btn :color="selectedUser?.is_admin ? 'warning' : 'success'" @click="toggleAdmin">Confirm</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Delete Dialog -->
    <v-dialog v-model="deleteDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5 text-error">Delete User</v-card-title>
        <v-card-text>
          Are you sure you want to permanently delete <strong>{{ selectedUser?.name }}</strong>? 
          This action cannot be undone and will also delete all their statistics ({{ selectedUser?.statistics_count }} records).
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="deleteDialog = false">Cancel</v-btn>
          <v-btn color="error" @click="deleteUser">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

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
  users: Object,
  userName: String,
  currentUserId: Number,
  csrfToken: String
});

const drawer = ref(false);
const toggleAdminDialog = ref(false);
const deleteDialog = ref(false);
const selectedUser = ref(null);

const userInitial = computed(() => {
  return props.userName ? props.userName.charAt(0).toUpperCase() : 'A';
});

const isDark = computed(() => theme.global.current.value.dark);

const toggleTheme = () => {
  theme.global.name.value = isDark.value ? 'light' : 'dark';
  localStorage.setItem('theme', theme.global.name.value);
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('lv-LV', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const changePage = (page) => {
  window.location.href = `/admin/users?page=${page}`;
};

const confirmToggleAdmin = (user) => {
  selectedUser.value = user;
  toggleAdminDialog.value = true;
};

const toggleAdmin = () => {
  if (!selectedUser.value) return;

  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `/admin/users/${selectedUser.value.id}/toggle-admin`;

  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = props.csrfToken;
  form.appendChild(csrfInput);

  document.body.appendChild(form);
  form.submit();
};

const confirmDelete = (user) => {
  selectedUser.value = user;
  deleteDialog.value = true;
};

const deleteUser = () => {
  if (!selectedUser.value) return;

  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `/admin/users/${selectedUser.value.id}`;

  const methodInput = document.createElement('input');
  methodInput.type = 'hidden';
  methodInput.name = '_method';
  methodInput.value = 'DELETE';
  form.appendChild(methodInput);

  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = props.csrfToken;
  form.appendChild(csrfInput);

  document.body.appendChild(form);
  form.submit();
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

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideInDown {
  from { opacity: 0; transform: translateY(-50px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
