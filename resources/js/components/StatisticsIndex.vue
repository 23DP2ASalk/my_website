<template>
  <v-app>
    <!-- App Bar -->
    <v-app-bar color="primary" dark prominent>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      
      <v-toolbar-title class="text-h5 font-weight-bold">
        <v-icon class="mr-2">mdi-chart-box</v-icon>
        Stats Tracker
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
            <v-list-item-subtitle>{{ userRole }}</v-list-item-subtitle>
          </v-list-item>
          <v-divider></v-divider>
          <v-list-item href="/dashboard">
            <template v-slot:prepend>
              <v-icon>mdi-home</v-icon>
            </template>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item>
          <v-list-item href="/statistics">
            <template v-slot:prepend>
              <v-icon>mdi-chart-line</v-icon>
            </template>
            <v-list-item-title>My Statistics</v-list-item-title>
          </v-list-item>
          <v-list-item @click="logout">
            <template v-slot:prepend>
              <v-icon>mdi-logout</v-icon>
            </template>
            <v-list-item-title>Iziet</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <!-- Navigation Drawer -->
    <v-navigation-drawer v-model="drawer" temporary>
      <v-list>
        <v-list-item prepend-icon="mdi-home" title="Dashboard" href="/dashboard"></v-list-item>
        <v-list-item prepend-icon="mdi-chart-line" title="Manas Statistikas" href="/statistics"></v-list-item>
        <v-list-item prepend-icon="mdi-plus-circle" title="Pievienot Statistiku" href="/statistics/create"></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main>
      <!-- Hero Section -->
      <v-container fluid class="hero-section pa-8">
        <v-row justify="center">
          <v-col cols="12" md="10" class="text-center">
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 gradient-text">
              Manas Statistikas
            </h1>
            <p class="text-h6 text-medium-emphasis mb-6">
              Seko saviem sasniegumiem un analizē progresu
            </p>
            <v-btn
              href="/statistics/create"
              color="primary"
              size="x-large"
              elevation="8"
              class="pulse-animation"
              prepend-icon="mdi-plus-circle"
            >
              Pievienot Jaunu Statistiku
            </v-btn>
          </v-col>
        </v-row>
      </v-container>

      <!-- Filters Section -->
      <v-container class="my-6">
        <v-card elevation="4">
          <v-card-title class="text-h5 text-primary">
            <v-icon class="mr-2">mdi-filter</v-icon>
            Filtri
          </v-card-title>
          <v-card-text>
            <v-form @submit.prevent="applyFilters">
              <v-row>
                <v-col cols="12" md="3">
                  <v-select
                    v-model="localFilters.sport_type"
                    :items="sportTypes"
                    label="Sporta veids"
                    prepend-inner-icon="mdi-basketball"
                    variant="outlined"
                    clearable
                  ></v-select>
                </v-col>
                <v-col cols="12" md="3">
                  <v-text-field
                    v-model="localFilters.start_date"
                    type="date"
                    label="Sākuma datums"
                    prepend-inner-icon="mdi-calendar"
                    variant="outlined"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <v-text-field
                    v-model="localFilters.end_date"
                    type="date"
                    label="Beigu datums"
                    prepend-inner-icon="mdi-calendar"
                    variant="outlined"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <v-text-field
                    v-model="localFilters.search"
                    label="Meklēt piezīmēs"
                    prepend-inner-icon="mdi-magnify"
                    variant="outlined"
                    clearable
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <v-btn type="submit" color="primary" prepend-icon="mdi-filter">
                    Filtrēt
                  </v-btn>
                  <v-btn @click="clearFilters" variant="text" class="ml-2">
                    Notīrīt
                  </v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-card-text>
        </v-card>
      </v-container>

      <!-- Statistics Cards -->
      <v-container v-if="statistics.data && statistics.data.length > 0">
        <v-row>
          <v-col
            v-for="(stat, index) in statistics.data"
            :key="stat.id"
            cols="12"
            sm="6"
            md="4"
          >
            <v-card 
              elevation="4" 
              hover 
              class="stat-card h-100"
            >
              <v-card-title class="d-flex align-center bg-primary text-white">
                <v-icon :icon="getSportIcon(stat.sport_type)" size="large" class="mr-2"></v-icon>
                <span>{{ stat.sport_type }}</span>
                <v-spacer></v-spacer>
                <v-chip size="small" color="white" variant="flat">
                  {{ formatDate(stat.game_date) }}
                </v-chip>
              </v-card-title>

              <v-card-text class="py-4">
                <div class="stats-grid">
                  <div
                    v-for="(value, key) in getRelevantStats(stat)"
                    :key="key"
                    class="stat-item"
                  >
                    <div class="stat-label">{{ key }}</div>
                    <div class="stat-value">{{ value || 0 }}</div>
                  </div>
                </div>

                <div v-if="stat.minutes_played" class="mt-3">
                  <v-chip size="small" color="info" prepend-icon="mdi-clock">
                    {{ stat.minutes_played }} min
                  </v-chip>
                </div>

                <div v-if="stat.notes" class="mt-3">
                  <v-divider class="mb-2"></v-divider>
                  <p class="text-caption text-grey">{{ stat.notes }}</p>
                </div>
              </v-card-text>

              <v-card-actions>
                <v-btn
                  :href="`/statistics/${stat.id}/edit`"
                  size="small"
                  variant="text"
                  color="primary"
                  prepend-icon="mdi-pencil"
                >
                  Rediģēt
                </v-btn>
                <v-btn
                  size="small"
                  variant="text"
                  color="error"
                  prepend-icon="mdi-delete"
                  @click="confirmDelete(stat)"
                >
                  Dzēst
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>

        <!-- Pagination -->
        <v-row v-if="statistics.last_page > 1" class="mt-4">
          <v-col cols="12" class="text-center">
            <v-pagination
              :length="statistics.last_page"
              :model-value="statistics.current_page"
              @update:modelValue="changePage"
              total-visible="7"
            ></v-pagination>
          </v-col>
        </v-row>
      </v-container>

      <!-- Empty State -->
      <v-container v-else>
        <v-row justify="center">
          <v-col cols="12" md="8">
            <v-card class="text-center pa-8">
              <v-icon icon="mdi-chart-line" size="80" color="grey-lighten-1"></v-icon>
              <h3 class="mt-6 text-h5">Nav statistiku</h3>
              <p class="text-grey mt-2 mb-6">Sāc sekot saviem sasniegumiem!</p>
              <v-btn
                href="/statistics/create"
                color="primary"
                size="large"
                prepend-icon="mdi-plus-circle"
              >
                Pievienot Pirmo Statistiku
              </v-btn>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <!-- Delete Dialog -->
    <v-dialog v-model="deleteDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5">Apstiprināt dzēšanu</v-card-title>
        <v-card-text>
          Vai tiešām vēlaties dzēst <strong>{{ selectedStat?.sport_type }}</strong> 
          statistiku no <strong>{{ selectedStat ? formatDate(selectedStat.game_date) : '' }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="deleteDialog = false">Atcelt</v-btn>
          <v-btn color="error" @click="deleteStatistic">Dzēst</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Footer -->
    <v-footer color="primary" class="pa-6">
      <v-container>
        <v-row align="center">
          <v-col cols="12" md="6" class="text-center text-md-left white--text">
            <p class="mb-1">&copy; 2026 Stats Tracker</p>
            <p class="text-caption">Visi dati tiek drošī uzglabāti SQL datubāzēs</p>
          </v-col>
          <v-col cols="12" md="6" class="text-center text-md-right">
            <v-btn icon variant="text" href="#" class="mx-1">
              <v-icon color="white">mdi-facebook</v-icon>
            </v-btn>
            <v-btn icon variant="text" href="#" class="mx-1">
              <v-icon color="white">mdi-twitter</v-icon>
            </v-btn>
            <v-btn icon variant="text" href="#" class="mx-1">
              <v-icon color="white">mdi-instagram</v-icon>
            </v-btn>
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
  statistics: Object,
  filters: Object,
  userName: String,
  userRole: String,
  csrfToken: String
});

const drawer = ref(false);
const deleteDialog = ref(false);
const selectedStat = ref(null);

const sportTypes = [
  { title: 'Visi sporta veidi', value: null },
  { title: 'Basketbols', value: 'Basketball' },
  { title: 'Futbols', value: 'Football' },
  { title: 'Volejbols', value: 'Volleyball' }
];

const localFilters = ref({
  sport_type: props.filters?.sport_type || null,
  start_date: props.filters?.start_date || '',
  end_date: props.filters?.end_date || '',
  search: props.filters?.search || ''
});

const userInitial = computed(() => {
  return props.userName ? props.userName.charAt(0).toUpperCase() : 'U';
});

const isDark = computed(() => theme.global.current.value.dark);

const toggleTheme = () => {
  theme.global.name.value = isDark.value ? 'light' : 'dark';
  localStorage.setItem('theme', theme.global.name.value);
};

const getSportIcon = (sportType) => {
  const icons = {
    'Basketball': 'mdi-basketball',
    'Football': 'mdi-soccer',
    'Volleyball': 'mdi-volleyball'
  };
  return icons[sportType] || 'mdi-trophy';
};

const getRelevantStats = (stat) => {
  switch(stat.sport_type) {
    case 'Basketball':
      return {
        'Punkti': stat.points,
        'Piespēles': stat.assists,
        'Atlēkušās': stat.rebounds,
        'Pārtveršanas': stat.steals,
        'Bloķi': stat.blocks
      };
    case 'Football':
      return {
        'Vārti': stat.goals,
        'Piespēles': stat.assists_football,
        'Sitieni': stat.shots,
        'Pases': stat.passes,
        'Atņemšanas': stat.tackles
      };
    case 'Volleyball':
      return {
        'Uzbrukumi': stat.spikes,
        'Bloķi': stat.blocks_volleyball,
        'Servēšana': stat.serves,
        'Uzkritieni': stat.digs,
        'Asi': stat.aces
      };
    default:
      return {};
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('lv-LV', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

const applyFilters = () => {
  const params = new URLSearchParams();
  if (localFilters.value.sport_type) params.append('sport_type', localFilters.value.sport_type);
  if (localFilters.value.start_date) params.append('start_date', localFilters.value.start_date);
  if (localFilters.value.end_date) params.append('end_date', localFilters.value.end_date);
  if (localFilters.value.search) params.append('search', localFilters.value.search);
  
  window.location.href = `/statistics?${params.toString()}`;
};

const clearFilters = () => {
  window.location.href = '/statistics';
};

const changePage = (page) => {
  const params = new URLSearchParams(window.location.search);
  params.set('page', page);
  window.location.href = `/statistics?${params.toString()}`;
};

const confirmDelete = (stat) => {
  selectedStat.value = stat;
  deleteDialog.value = true;
};

const deleteStatistic = () => {
  if (!selectedStat.value) return;

  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `/statistics/${selectedStat.value.id}`;
  
  const methodInput = document.createElement('input');
  methodInput.type = 'hidden';
  methodInput.name = '_method';
  methodInput.value = 'DELETE';
  
  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = props.csrfToken;
  
  form.appendChild(methodInput);
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
  background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
  min-height: 300px;
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

.pulse-animation {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7); }
  70% { box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); }
  100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
}

.stat-card {
  transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
              box-shadow 0.4s ease;
  animation: fadeInUp 0.6s ease-in-out;
  animation-fill-mode: both;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }

.stat-card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
  gap: 12px;
  margin-top: 8px;
}

.stat-item {
  text-align: center;
  padding: 8px;
  background: rgba(0, 0, 0, 0.05);
  border-radius: 8px;
  transition: all 0.3s ease;
}

.stat-item:hover {
  background: rgba(37, 99, 235, 0.1);
  transform: scale(1.05);
}

.stat-label {
  font-size: 0.75rem;
  color: #666;
  margin-bottom: 4px;
}

.stat-value {
  font-size: 1.25rem;
  font-weight: bold;
  color: #2563eb;
}

.h-100 {
  height: 100%;
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
