<template>
  <v-app>
    <v-app-bar color="primary" dark prominent>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="text-h5 font-weight-bold">
        <v-icon class="mr-2">mdi-chart-box</v-icon>
        Stats Tracker
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn href="/dashboard" variant="text" prepend-icon="mdi-home" class="mr-2">Dashboard</v-btn>
      <v-btn href="/statistics" variant="text" prepend-icon="mdi-chart-line" class="mr-2">Statistics</v-btn>
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
            <v-list-item-subtitle>{{ userRole }}</v-list-item-subtitle>
          </v-list-item>
          <v-divider></v-divider>
          <v-list-item href="/dashboard"><template v-slot:prepend><v-icon>mdi-home</v-icon></template><v-list-item-title>Dashboard</v-list-item-title></v-list-item>
          <v-list-item href="/statistics"><template v-slot:prepend><v-icon>mdi-chart-line</v-icon></template><v-list-item-title>Statistics</v-list-item-title></v-list-item>
          <v-list-item @click="logout"><template v-slot:prepend><v-icon>mdi-logout</v-icon></template><v-list-item-title>Logout</v-list-item-title></v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" temporary>
      <v-list>
        <v-list-item prepend-icon="mdi-home" title="Dashboard" href="/dashboard"></v-list-item>
        <v-list-item prepend-icon="mdi-chart-line" title="Statistics" href="/statistics"></v-list-item>
        <v-list-item prepend-icon="mdi-chart-bar" title="Analytics" href="/analytics"></v-list-item>
        <v-list-item prepend-icon="mdi-download" title="Export" href="/exports"></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-main>
      <v-container fluid class="hero-section pa-8">
        <v-row justify="center">
          <v-col cols="12" md="10" class="text-center">
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 gradient-text">Datu Eksports</h1>
            <p class="text-h6 text-medium-emphasis">Eksportē savas statistikas dažādos formātos</p>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="my-6">
        <!-- Summary -->
        <v-row>
          <v-col cols="12">
            <v-card elevation="4" class="summary-card">
              <v-card-title class="bg-primary text-white">
                <v-icon class="mr-2">mdi-information</v-icon>
                Datu Kopsavilkums
              </v-card-title>
              <v-card-text class="pa-6">
                <v-row>
                  <v-col cols="12" md="6">
                    <div class="text-center">
                      <h2 class="text-h3 text-primary">{{ totalGames }}</h2>
                      <p class="text-h6">Kopējās Spēles</p>
                    </div>
                  </v-col>
                  <v-col cols="12" md="6">
                    <div v-for="sport in gamesBySport" :key="sport.sport_type" class="mb-2">
                      <strong>{{ sport.sport_type }}:</strong> {{ sport.count }} spēles
                    </div>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- Export Options -->
        <v-row class="mt-6">
          <v-col cols="12" md="6">
            <v-card elevation="4" class="export-card" hover>
              <v-card-title class="bg-success text-white">
                <v-icon class="mr-2" size="large">mdi-file-excel</v-icon>
                CSV Eksports
              </v-card-title>
              <v-card-text class="pa-6">
                <p class="mb-4">Eksportē savas statistikas CSV formātā, kas ir saderīgs ar Excel, Google Sheets un citām izklājlapu programmām.</p>
                
                <v-form @submit.prevent="exportCSV">
                  <v-select
                    v-model="csvFilters.sport_type"
                    :items="sportTypeOptions"
                    label="Sporta veids"
                    variant="outlined"
                    class="mb-3"
                  ></v-select>

                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="csvFilters.start_date"
                        type="date"
                        label="Sākuma datums"
                        variant="outlined"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="csvFilters.end_date"
                        type="date"
                        label="Beigu datums"
                        variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-btn
                    type="submit"
                    color="success"
                    size="large"
                    block
                    prepend-icon="mdi-download"
                  >
                    Lejupielādēt CSV
                  </v-btn>
                </v-form>

                <v-divider class="my-4"></v-divider>

                <v-list density="compact">
                  <v-list-item prepend-icon="mdi-check">Excel saderīgs</v-list-item>
                  <v-list-item prepend-icon="mdi-check">Universāls formāts</v-list-item>
                  <v-list-item prepend-icon="mdi-check">Viegli rediģējams</v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="6">
            <v-card elevation="4" class="export-card" hover>
              <v-card-title class="bg-info text-white">
                <v-icon class="mr-2" size="large">mdi-code-json</v-icon>
                JSON Eksports
              </v-card-title>
              <v-card-text class="pa-6">
                <p class="mb-4">Eksportē savas statistikas JSON formātā, kas ir ideāls datu analīzei un programmēšanai.</p>
                
                <v-form @submit.prevent="exportJSON">
                  <v-select
                    v-model="jsonFilters.sport_type"
                    :items="sportTypeOptions"
                    label="Sporta veids"
                    variant="outlined"
                    class="mb-3"
                  ></v-select>

                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="jsonFilters.start_date"
                        type="date"
                        label="Sākuma datums"
                        variant="outlined"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="jsonFilters.end_date"
                        type="date"
                        label="Beigu datums"
                        variant="outlined"
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-btn
                    type="submit"
                    color="info"
                    size="large"
                    block
                    prepend-icon="mdi-download"
                  >
                    Lejupielādēt JSON
                  </v-btn>
                </v-form>

                <v-divider class="my-4"></v-divider>

                <v-list density="compact">
                  <v-list-item prepend-icon="mdi-check">Strukturēti dati</v-list-item>
                  <v-list-item prepend-icon="mdi-check">API integrācijām</v-list-item>
                  <v-list-item prepend-icon="mdi-check">Programmēšanai draudzīgs</v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- Info -->
        <v-row class="mt-6">
          <v-col cols="12">
            <v-alert type="info" variant="tonal" prominent>
              <v-alert-title>
                <v-icon class="mr-2">mdi-information</v-icon>
                Eksporta Informācija
              </v-alert-title>
              <p class="mt-2">
                Visi eksportētie faili satur tavu personīgo statistiku. 
                Izmanto filtrus, lai izvēlētos konkrētu sporta veidu vai datumu diapazonu.
                Faili tiks lejupielādēti uz tavu ierīci un nebūs pieejami citiem lietotājiem.
              </p>
            </v-alert>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <v-footer color="primary" class="pa-6">
      <v-container>
        <v-row align="center">
          <v-col cols="12" md="6" class="text-center text-md-left white--text">
            <p class="mb-1">&copy; 2026 Stats Tracker</p>
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
  totalGames: Number,
  gamesBySport: Array,
  userName: String,
  userRole: String,
  csrfToken: String
});

const drawer = ref(false);

const sportTypeOptions = [
  { title: 'Visi sporta veidi', value: 'all' },
  { title: 'Basketball', value: 'Basketball' },
  { title: 'Football', value: 'Football' },
  { title: 'Volleyball', value: 'Volleyball' }
];

const csvFilters = ref({
  sport_type: 'all',
  start_date: '',
  end_date: ''
});

const jsonFilters = ref({
  sport_type: 'all',
  start_date: '',
  end_date: ''
});

const userInitial = computed(() => {
  return props.userName ? props.userName.charAt(0).toUpperCase() : 'U';
});

const isDark = computed(() => theme.global.current.value.dark);

const toggleTheme = () => {
  theme.global.name.value = isDark.value ? 'light' : 'dark';
  localStorage.setItem('theme', theme.global.name.value);
};

const exportCSV = () => {
  const params = new URLSearchParams();
  if (csvFilters.value.sport_type !== 'all') {
    params.append('sport_type', csvFilters.value.sport_type);
  }
  if (csvFilters.value.start_date) {
    params.append('start_date', csvFilters.value.start_date);
  }
  if (csvFilters.value.end_date) {
    params.append('end_date', csvFilters.value.end_date);
  }

  window.location.href = `/exports/csv?${params.toString()}`;
};

const exportJSON = () => {
  const params = new URLSearchParams();
  if (jsonFilters.value.sport_type !== 'all') {
    params.append('sport_type', jsonFilters.value.sport_type);
  }
  if (jsonFilters.value.start_date) {
    params.append('start_date', jsonFilters.value.start_date);
  }
  if (jsonFilters.value.end_date) {
    params.append('end_date', jsonFilters.value.end_date);
  }

  window.location.href = `/exports/json?${params.toString()}`;
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
  background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
  min-height: 250px;
  display: flex;
  align-items: center;
  animation: fadeIn 0.8s ease-in-out;
}

.v-theme--dark .hero-section {
  background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
}

.gradient-text {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: slideInDown 0.8s ease-in-out;
}

.summary-card, .export-card {
  animation: fadeInUp 0.6s ease-in-out;
  animation-fill-mode: both;
}

.export-card {
  transition: transform 0.3s, box-shadow 0.3s;
}

.export-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
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
