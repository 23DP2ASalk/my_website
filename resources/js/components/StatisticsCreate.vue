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

      <v-btn href="/statistics" variant="text" prepend-icon="mdi-chart-line" class="mr-2">
        My Statistics
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
            <template v-slot:prepend><v-icon>mdi-home</v-icon></template>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item>
          <v-list-item href="/statistics">
            <template v-slot:prepend><v-icon>mdi-chart-line</v-icon></template>
            <v-list-item-title>My Statistics</v-list-item-title>
          </v-list-item>
          <v-list-item @click="logout">
            <template v-slot:prepend><v-icon>mdi-logout</v-icon></template>
            <v-list-item-title>Iziet</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <!-- Navigation Drawer -->
    <v-navigation-drawer v-model="drawer" temporary>
      <v-list>
        <v-list-item prepend-icon="mdi-home" title="Dashboard" href="/dashboard"></v-list-item>
        <v-list-item prepend-icon="mdi-chart-line" title="My Statistics" href="/statistics"></v-list-item>
        <v-list-item prepend-icon="mdi-plus-circle" title="Add Statistics" href="/statistics/create"></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main>
      <!-- Hero Section -->
      <v-container fluid class="hero-section pa-8">
        <v-row justify="center">
          <v-col cols="12" md="10" class="text-center">
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 gradient-text">
              Pievienot Statistiku
            </h1>
            <p class="text-h6 text-medium-emphasis">
              Reģistrē savas spēles statistiku un seko progresam
            </p>
          </v-col>
        </v-row>
      </v-container>

      <!-- Form Section -->
      <v-container class="my-6">
        <v-row justify="center">
          <v-col cols="12" lg="8">
            <v-card elevation="8" class="form-card">
              <v-card-text class="pa-6">
                <v-form @submit.prevent="submit">
                  <!-- Sport Type -->
                  <v-select
                    v-model="form.sport_type"
                    :items="sportTypes"
                    label="Sporta veids *"
                    prepend-inner-icon="mdi-basketball"
                    variant="outlined"
                    :error-messages="errors.sport_type"
                    @update:modelValue="onSportChange"
                    class="mb-4"
                  ></v-select>

                  <v-row>
                    <!-- Game Date -->
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="form.game_date"
                        type="date"
                        label="Spēles datums *"
                        prepend-inner-icon="mdi-calendar"
                        variant="outlined"
                        :error-messages="errors.game_date"
                      ></v-text-field>
                    </v-col>

                    <!-- Minutes Played -->
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model.number="form.minutes_played"
                        type="number"
                        label="Spēlētās minūtes"
                        prepend-inner-icon="mdi-clock"
                        variant="outlined"
                        :error-messages="errors.minutes_played"
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-divider class="my-6"></v-divider>

                  <!-- Basketball Stats -->
                  <div v-if="form.sport_type === 'Basketball'" class="stats-section">
                    <h3 class="text-h5 mb-4 d-flex align-center">
                      <v-icon class="mr-2" color="primary">mdi-basketball</v-icon>
                      Basketbola Statistika
                    </h3>
                    <v-row>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.points" type="number" label="Punkti" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.assists" type="number" label="Piespēles" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.rebounds" type="number" label="Atlēkušās" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.steals" type="number" label="Pārtveršanas" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.blocks" type="number" label="Bloķi" variant="outlined" min="0"></v-text-field>
                      </v-col>
                    </v-row>
                  </div>

                  <!-- Football Stats -->
                  <div v-if="form.sport_type === 'Football'" class="stats-section">
                    <h3 class="text-h5 mb-4 d-flex align-center">
                      <v-icon class="mr-2" color="primary">mdi-soccer</v-icon>
                      Futbola Statistika
                    </h3>
                    <v-row>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.goals" type="number" label="Vārti" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.assists_football" type="number" label="Piespēles" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.shots" type="number" label="Sitieni" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.passes" type="number" label="Pases" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.tackles" type="number" label="Atņemšanas" variant="outlined" min="0"></v-text-field>
                      </v-col>
                    </v-row>
                  </div>

                  <!-- Volleyball Stats -->
                  <div v-if="form.sport_type === 'Volleyball'" class="stats-section">
                    <h3 class="text-h5 mb-4 d-flex align-center">
                      <v-icon class="mr-2" color="primary">mdi-volleyball</v-icon>
                      Volejbola Statistika
                    </h3>
                    <v-row>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.spikes" type="number" label="Uzbrukumi" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.blocks_volleyball" type="number" label="Bloķi" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.serves" type="number" label="Servēšana" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.digs" type="number" label="Uzkritieni" variant="outlined" min="0"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model.number="form.aces" type="number" label="Eisi" variant="outlined" min="0"></v-text-field>
                      </v-col>
                    </v-row>
                  </div>

                  <v-divider class="my-6"></v-divider>

                  <!-- Notes -->
                  <v-textarea
                    v-model="form.notes"
                    label="Piezīmes (neobligāti)"
                    prepend-inner-icon="mdi-note-text"
                    variant="outlined"
                    rows="3"
                    :error-messages="errors.notes"
                  ></v-textarea>

                  <!-- Actions -->
                  <v-card-actions class="px-0 pt-4">
                    <v-btn
                      type="submit"
                      color="primary"
                      size="large"
                      prepend-icon="mdi-content-save"
                      :loading="loading"
                    >
                      Saglabāt Statistiku
                    </v-btn>
                    <v-btn
                      href="/statistics"
                      variant="text"
                      size="large"
                    >
                      Atcelt
                    </v-btn>
                  </v-card-actions>
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>

          <!-- Quick Info -->
          <v-col cols="12" lg="4">
            <v-card elevation="4" color="info" class="info-card">
              <v-card-title class="text-white">
                <v-icon class="mr-2">mdi-information</v-icon>
                Ātrā Informācija
              </v-card-title>
              <v-card-text class="text-white">
                <v-list bg-color="transparent" class="text-white">
                  <v-list-item prepend-icon="mdi-check-circle">
                    <v-list-item-title>Ievadi spēles statistiku</v-list-item-title>
                  </v-list-item>
                  <v-list-item prepend-icon="mdi-check-circle">
                    <v-list-item-title>Seko savam progresam</v-list-item-title>
                  </v-list-item>
                  <v-list-item prepend-icon="mdi-check-circle">
                    <v-list-item-title>Salīdzini rezultātus</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

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
  sportTypes: Array,
  userName: String,
  userRole: String,
  csrfToken: String
});

const drawer = ref(false);
const loading = ref(false);
const errors = ref({});

const form = ref({
  sport_type: '',
  game_date: new Date().toISOString().split('T')[0],
  minutes_played: null,
  notes: '',
  // Basketball
  points: null,
  assists: null,
  rebounds: null,
  steals: null,
  blocks: null,
  // Football
  goals: null,
  assists_football: null,
  shots: null,
  passes: null,
  tackles: null,
  // Volleyball
  spikes: null,
  blocks_volleyball: null,
  serves: null,
  digs: null,
  aces: null
});

const userInitial = computed(() => {
  return props.userName ? props.userName.charAt(0).toUpperCase() : 'U';
});

const isDark = computed(() => theme.global.current.value.dark);

const toggleTheme = () => {
  theme.global.name.value = isDark.value ? 'light' : 'dark';
  localStorage.setItem('theme', theme.global.name.value);
};

const onSportChange = () => {
  // Clear stats when sport changes
  form.value.points = null;
  form.value.assists = null;
  form.value.rebounds = null;
  form.value.steals = null;
  form.value.blocks = null;
  form.value.goals = null;
  form.value.assists_football = null;
  form.value.shots = null;
  form.value.passes = null;
  form.value.tackles = null;
  form.value.spikes = null;
  form.value.blocks_volleyball = null;
  form.value.serves = null;
  form.value.digs = null;
  form.value.aces = null;
};

const submit = () => {
  loading.value = true;
  errors.value = {};

  const formElement = document.createElement('form');
  formElement.method = 'POST';
  formElement.action = '/statistics';

  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = props.csrfToken;
  formElement.appendChild(csrfInput);

  // Add all form fields
  Object.keys(form.value).forEach(key => {
    if (form.value[key] !== null && form.value[key] !== '') {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = key;
      input.value = form.value[key];
      formElement.appendChild(input);
    }
  });

  document.body.appendChild(formElement);
  formElement.submit();
};

const logout = () => {
  const formElement = document.createElement('form');
  formElement.method = 'POST';
  formElement.action = '/logout';
  
  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = props.csrfToken;
  
  formElement.appendChild(csrfInput);
  document.body.appendChild(formElement);
  formElement.submit();
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

.form-card {
  animation: fadeInUp 0.6s ease-in-out;
}

.info-card {
  animation: fadeInRight 0.6s ease-in-out;
  animation-delay: 0.2s;
  animation-fill-mode: both;
}

.stats-section {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInRight {
  from { opacity: 0; transform: translateX(30px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes slideInDown {
  from { opacity: 0; transform: translateY(-50px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
