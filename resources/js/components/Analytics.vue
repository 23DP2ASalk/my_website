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
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 gradient-text">Datu Analīze</h1>
            <p class="text-h6 text-medium-emphasis">Analizē savu sniegumu un progresa tendences</p>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="my-6">
        <!-- Summary Cards -->
        <v-row>
          <v-col cols="12" md="4">
            <v-card elevation="4" class="stat-summary-card">
              <v-card-text class="text-center pa-6">
                <v-icon icon="mdi-gamepad-variant" size="48" color="primary" class="mb-3"></v-icon>
                <h2 class="text-h3 text-primary">{{ totalGames }}</h2>
                <p class="text-h6">Kopējās Spēles</p>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="4">
            <v-card elevation="4" class="stat-summary-card">
              <v-card-text class="text-center pa-6">
                <v-icon icon="mdi-trophy" size="48" color="success" class="mb-3"></v-icon>
                <h2 class="text-h3 text-success">{{ Object.keys(averageStats).length }}</h2>
                <p class="text-h6">Sporta Veidi</p>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="4">
            <v-card elevation="4" class="stat-summary-card">
              <v-card-text class="text-center pa-6">
                <v-icon icon="mdi-chart-line" size="48" color="info" class="mb-3"></v-icon>
                <h2 class="text-h3 text-info">{{ recentTrends.length }}</h2>
                <p class="text-h6">Jaunākās Spēles</p>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- Games by Sport -->
        <v-row class="mt-6">
          <v-col cols="12" md="6">
            <v-card elevation="4">
              <v-card-title class="bg-primary text-white">
                <v-icon class="mr-2">mdi-chart-pie</v-icon>
                Spēles pēc Sporta Veida
              </v-card-title>
              <v-card-text class="pa-6">
                <div v-for="sport in gamesBySport" :key="sport.sport_type" class="mb-4">
                  <div class="d-flex justify-space-between mb-2">
                    <span class="font-weight-bold">{{ sport.sport_type }}</span>
                    <span>{{ sport.count }} spēles</span>
                  </div>
                  <v-progress-linear
                    :model-value="(sport.count / totalGames) * 100"
                    :color="getSportColor(sport.sport_type)"
                    height="20"
                  >
                    <template v-slot:default>
                      <strong>{{ Math.round((sport.count / totalGames) * 100) }}%</strong>
                    </template>
                  </v-progress-linear>
                </div>
              </v-card-text>
            </v-card>
          </v-col>

          <!-- Average Statistics -->
          <v-col cols="12" md="6">
            <v-card elevation="4">
              <v-card-title class="bg-secondary text-white">
                <v-icon class="mr-2">mdi-calculator</v-icon>
                Vidējie Rādītāji
              </v-card-title>
              <v-card-text class="pa-6">
                <div v-for="(stats, sport) in averageStats" :key="sport" class="mb-4">
                  <h4 class="mb-2">{{ sport }}</h4>
                  <v-chip-group>
                    <v-chip v-for="(value, key) in stats" :key="key" v-if="key !== 'games'" color="primary" variant="outlined">
                      {{ formatStatLabel(key) }}: {{ value }}
                    </v-chip>
                  </v-chip-group>
                  <v-divider class="mt-3"></v-divider>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- Best Performances -->
        <v-row class="mt-6">
          <v-col cols="12">
            <v-card elevation="4">
              <v-card-title class="bg-success text-white">
                <v-icon class="mr-2">mdi-star</v-icon>
                Labākie Sniegumi
              </v-card-title>
              <v-card-text class="pa-6">
                <v-row>
                  <v-col cols="12" md="4" v-for="(perf, sport) in bestPerformances" :key="sport" v-if="perf">
                    <v-card variant="outlined">
                      <v-card-title class="text-capitalize">{{ sport }}</v-card-title>
                      <v-card-text>
                        <p><strong>Datums:</strong> {{ formatDate(perf.game_date) }}</p>
                        <p v-if="perf.points"><strong>Punkti:</strong> {{ perf.points }}</p>
                        <p v-if="perf.goals"><strong>Vārti:</strong> {{ perf.goals }}</p>
                        <p v-if="perf.spikes"><strong>Uzbrukumi:</strong> {{ perf.spikes }}</p>
                        <p v-if="perf.notes" class="text-caption mt-2">{{ perf.notes }}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- Recent Trends -->
        <v-row class="mt-6" v-if="recentTrends.length > 0">
          <v-col cols="12">
            <v-card elevation="4">
              <v-card-title class="bg-info text-white">
                <v-icon class="mr-2">mdi-trending-up</v-icon>
                Progresa Tendences (Pēdējās 10 Spēles)
              </v-card-title>
              <v-card-text class="pa-6">
                <v-simple-table>
                  <thead>
                    <tr>
                      <th>Datums</th>
                      <th>Sports</th>
                      <th>Galvenais Rādītājs</th>
                      <th>Minūtes</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="trend in recentTrends" :key="trend.id">
                      <td>{{ formatDate(trend.game_date) }}</td>
                      <td>{{ trend.sport_type }}</td>
                      <td>
                        <span v-if="trend.points">{{ trend.points }} punkti</span>
                        <span v-if="trend.goals">{{ trend.goals }} vārti</span>
                        <span v-if="trend.spikes">{{ trend.spikes }} uzbrukumi</span>
                      </td>
                      <td>{{ trend.minutes_played || '-' }}</td>
                    </tr>
                  </tbody>
                </v-simple-table>
              </v-card-text>
            </v-card>
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
  averageStats: Object,
  recentTrends: Array,
  bestPerformances: Object,
  userName: String,
  userRole: String,
  csrfToken: String
});

const drawer = ref(false);

const userInitial = computed(() => {
  return props.userName ? props.userName.charAt(0).toUpperCase() : 'U';
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

const formatStatLabel = (key) => {
  const labels = {
    'points': 'Punkti',
    'assists': 'Piespēles',
    'rebounds': 'Atlēkušās',
    'steals': 'Pārtveršanas',
    'blocks': 'Bloķi',
    'goals': 'Vārti',
    'shots': 'Sitieni',
    'passes': 'Pases',
    'tackles': 'Atņemšanas',
    'spikes': 'Uzbrukumi',
    'serves': 'Servēšana',
    'digs': 'Uzkritieni',
    'aces': 'Asi'
  };
  return labels[key] || key;
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('lv-LV');
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

.stat-summary-card {
  animation: fadeInUp 0.6s ease-in-out;
  animation-fill-mode: both;
  transition: transform 0.3s;
}

.stat-summary-card:hover {
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
