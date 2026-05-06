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

      <v-btn href="/dashboard" variant="text" prepend-icon="mdi-home" class="mr-2">Dashboard</v-btn>
      <v-btn href="/statistics" variant="text" prepend-icon="mdi-chart-line" class="mr-2">My Statistics</v-btn>
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
      <v-container fluid class="hero-section pa-8">
        <v-row justify="center">
          <v-col cols="12" md="10" class="text-center">
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 gradient-text">
              Rediģēt Statistiku
            </h1>
            <p class="text-h6 text-medium-emphasis">
              Atjauno spēles statistiku
            </p>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="my-6">
        <v-row justify="center">
          <v-col cols="12" lg="8">
            <v-card elevation="8" class="form-card">
              <v-card-text class="pa-6">
                <v-form @submit.prevent="submit">
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
                        <v-text-field v-model.number="form.aces" type="number" label="Asi" variant="outlined" min="0"></v-text-field>
                      </v-col>
                    </v-row>
                  </div>

                  <v-divider class="my-6"></v-divider>

                  <v-textarea
                    v-model="form.notes"
                    label="Piezīmes (neobligāti)"
                    prepend-inner-icon="mdi-note-text"
                    variant="outlined"
                    rows="3"
                    :error-messages="errors.notes"
                  ></v-textarea>

                  <v-card-actions class="px-0 pt-4">
                    <v-btn
                      type="submit"
                      color="primary"
                      size="large"
                      prepend-icon="mdi-content-save"
                      :loading="loading"
                    >
                      Atjaunināt Statistiku
                    </v-btn>
                    <v-btn href="/statistics" variant="text" size="large">
                      Atcelt
                    </v-btn>
                  </v-card-actions>
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useTheme } from 'vuetify';

const theme = useTheme();

const props = defineProps({
  statistic: Object,
  sportTypes: Array,
  userName: String,
  userRole: String,
  csrfToken: String
});

const drawer = ref(false);
const loading = ref(false);
const errors = ref({});

const form = ref({
  sport_type: props.statistic.sport_type,
  game_date: props.statistic.game_date,
  minutes_played: props.statistic.minutes_played,
  notes: props.statistic.notes,
  points: props.statistic.points,
  assists: props.statistic.assists,
  rebounds: props.statistic.rebounds,
  steals: props.statistic.steals,
  blocks: props.statistic.blocks,
  goals: props.statistic.goals,
  assists_football: props.statistic.assists_football,
  shots: props.statistic.shots,
  passes: props.statistic.passes,
  tackles: props.statistic.tackles,
  spikes: props.statistic.spikes,
  blocks_volleyball: props.statistic.blocks_volleyball,
  serves: props.statistic.serves,
  digs: props.statistic.digs,
  aces: props.statistic.aces
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
  // Don't clear when editing
};

const submit = () => {
  loading.value = true;
  errors.value = {};

  const formElement = document.createElement('form');
  formElement.method = 'POST';
  formElement.action = `/statistics/${props.statistic.id}`;

  const methodInput = document.createElement('input');
  methodInput.type = 'hidden';
  methodInput.name = '_method';
  methodInput.value = 'PUT';
  formElement.appendChild(methodInput);

  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = props.csrfToken;
  formElement.appendChild(csrfInput);

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

@keyframes slideInDown {
  from { opacity: 0; transform: translateY(-50px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
