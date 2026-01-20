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
        <v-list-item prepend-icon="mdi-home" title="Sākums" @click="scrollTo('home')"></v-list-item>
        <v-list-item prepend-icon="mdi-information" title="Par mums"></v-list-item>
        
        <v-list-group value="Services">
          <template v-slot:activator="{ props }">
            <v-list-item v-bind="props" prepend-icon="mdi-cog" title="Pakalpojumi"></v-list-item>
          </template>
          <v-list-item prepend-icon="mdi-chart-line" title="Statistikas ievadīšana"></v-list-item>
          <v-list-item prepend-icon="mdi-chart-bar" title="Datu analīze"></v-list-item>
          <v-list-item prepend-icon="mdi-file-document" title="Atskaites"></v-list-item>
          <v-list-item prepend-icon="mdi-download" title="Datu eksports"></v-list-item>
        </v-list-group>

        <v-list-item prepend-icon="mdi-email" title="Kontakti"></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main>
      <!-- Hero Section -->
      <v-container fluid class="hero-section pa-8">
        <v-row justify="center">
          <v-col cols="12" md="8" class="text-center">
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 gradient-text">
              Universāls Sporta Statistikas Apkopotājs
            </h1>
            <p class="text-h6 text-medium-emphasis">
              Profesionāla platforma spēļu statistikas ievadīšanai, uzglabāšanai un analīzei.
            </p>
          </v-col>
        </v-row>
      </v-container>

      <!-- Search Section -->
      <v-container class="my-8">
        <v-row justify="center">
          <v-col cols="12" md="8">
            <v-text-field
              v-model="searchQuery"
              prepend-inner-icon="mdi-magnify"
              label="Meklēt sporta veidus, rakstus vai ziņas..."
              variant="outlined"
              rounded
              clearable
              hide-details
            ></v-text-field>
            
            <v-chip-group v-if="searchQuery && filteredResults > 0" class="mt-3">
              <v-chip color="primary" variant="flat">
                Atrasti {{ filteredResults }} rezultāti
              </v-chip>
            </v-chip-group>

            <v-alert
              v-if="searchQuery && filteredResults === 0"
              type="info"
              variant="tonal"
              class="mt-3"
            >
              <div class="d-flex align-center">
                <v-icon size="large" class="mr-3">mdi-magnify</v-icon>
                <div>
                  <div class="text-h6">Nav atrasti rezultāti</div>
                  <div>Mēģiniet izmantot citus meklēšanas vārdus</div>
                </div>
              </div>
            </v-alert>
          </v-col>
        </v-row>
      </v-container>

      <!-- Sports Cards -->
      <v-container>
        <v-row>
          <v-col
            v-for="sport in filteredSports"
            :key="sport.id"
            cols="12"
            sm="6"
            md="4"
          >
            <v-card
              elevation="4"
              hover
              class="sport-card"
              @click="openSportDialog(sport)"
            >
              <v-img
                :src="sport.image"
                height="200"
                cover
                gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
              >
                <v-card-title class="text-white">
                  {{ sport.icon }} {{ sport.title }}
                </v-card-title>
              </v-img>
              
              <v-card-text class="py-4">
                <p>{{ sport.description }}</p>
              </v-card-text>

              <v-card-actions>
                <v-btn
                  color="primary"
                  block
                  variant="flat"
                >
                  Uzzināt vairāk
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <!-- News Section -->
      <v-container class="my-8">
        <v-row>
          <v-col cols="12">
            <h2 class="text-h4 font-weight-bold text-primary mb-6 text-center">
              🏆 Sporta Ziņas
            </h2>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="8">
            <v-text-field
              v-model="newsSearchQuery"
              prepend-inner-icon="mdi-newspaper"
              label="Meklēt sporta ziņas... (angļu valodā)"
              variant="outlined"
              rounded
              @keyup.enter="searchNews"
              hide-details
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <v-btn
              color="primary"
              size="large"
              block
              @click="searchNews"
              :loading="newsLoading"
            >
              <v-icon start>mdi-magnify</v-icon>
              Meklēt
            </v-btn>
          </v-col>
        </v-row>

        <!-- Search History -->
        <v-row v-if="searchHistory.length > 0" class="mt-4">
          <v-col cols="12">
            <v-card variant="outlined">
              <v-card-title>
                <v-icon class="mr-2">mdi-history</v-icon>
                Meklēšanas vēsture
              </v-card-title>
              <v-card-text>
                <v-chip-group>
                  <v-chip
                    v-for="(term, index) in searchHistory"
                    :key="index"
                    @click="searchFromHistory(term)"
                    color="primary"
                    variant="outlined"
                    class="ma-1"
                  >
                    {{ term }}
                  </v-chip>
                </v-chip-group>
              </v-card-text>
              <v-card-actions>
                <v-btn color="error" variant="text" @click="clearHistory">
                  <v-icon start>mdi-delete</v-icon>
                  Notīrīt vēsturi
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>

        <!-- Loading -->
        <v-row v-if="newsLoading" justify="center" class="my-8">
          <v-col cols="12" class="text-center">
            <v-progress-circular
              indeterminate
              color="primary"
              size="64"
            ></v-progress-circular>
            <p class="mt-4 text-h6">Ielādē ziņas...</p>
          </v-col>
        </v-row>

        <!-- Error -->
        <v-alert
          v-if="newsError"
          type="error"
          variant="tonal"
          closable
          class="my-4"
          @click:close="newsError = ''"
        >
          {{ newsError }}
        </v-alert>

        <!-- News Cards -->
        <v-row v-if="!newsLoading && newsArticles.length > 0">
          <v-col
            v-for="(article, index) in newsArticles"
            :key="index"
            cols="12"
            sm="6"
            md="4"
          >
            <v-card elevation="4" hover class="h-100 d-flex flex-column">
              <v-img
                :src="article.urlToImage || 'https://via.placeholder.com/400x200?text=No+Image'"
                height="200"
                cover
              >
                <v-chip
                  class="ma-2"
                  color="primary"
                  size="small"
                >
                  {{ article.source.name }}
                </v-chip>
              </v-img>

              <v-card-title class="text-h6">
                {{ article.title }}
              </v-card-title>

              <v-card-text class="flex-grow-1">
                <p class="text-medium-emphasis mb-2">
                  {{ article.description || 'Nav apraksta.' }}
                </p>
                <p class="text-caption">
                  <v-icon size="small">mdi-calendar</v-icon>
                  {{ formatDate(article.publishedAt) }}
                </p>
              </v-card-text>

              <v-card-actions>
                <v-btn
                  :href="article.url"
                  target="_blank"
                  color="primary"
                  variant="flat"
                  block
                >
                  Lasīt vairāk
                  <v-icon end>mdi-open-in-new</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <!-- Articles -->
      <v-container v-if="filteredArticles.length > 0">
        <v-row>
          <v-col cols="12">
            <v-card>
              <v-card-title class="text-h5 text-primary">
                <v-icon class="mr-2">mdi-newspaper-variant</v-icon>
                Jaunākie raksti
              </v-card-title>
              <v-list>
                <v-list-item
                  v-for="(article, index) in filteredArticles"
                  :key="index"
                  :title="article"
                  prepend-icon="mdi-file-document-outline"
                ></v-list-item>
              </v-list>
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
            <p class="mb-1">&copy; 2025 Stats Tracker</p>
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
            <v-btn icon variant="text" href="#" class="mx-1">
              <v-icon color="white">mdi-linkedin</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-footer>

    <!-- Sport Dialog -->
    <v-dialog v-model="sportDialog" max-width="600">
      <v-card v-if="selectedSport">
        <v-img
          :src="selectedSport.image"
          height="300"
          cover
        ></v-img>
        
        <v-card-title class="text-h5">
          {{ selectedSport.icon }} {{ selectedSport.title }}
        </v-card-title>

        <v-card-text>
          <p class="mb-4">{{ selectedSport.detailedText }}</p>
          
          <p class="font-weight-bold mb-2">Šī platforma ļauj jums:</p>
          <v-list density="compact">
            <v-list-item prepend-icon="mdi-check-circle" title="Ievadīt detalizētu spēļu statistiku"></v-list-item>
            <v-list-item prepend-icon="mdi-check-circle" title="Uzglabāt datus drošās SQL datubāzēs"></v-list-item>
            <v-list-item prepend-icon="mdi-check-circle" title="Analizēt sniegumu ar vizualizācijām"></v-list-item>
            <v-list-item prepend-icon="mdi-check-circle" title="Eksportēt atskaites dažādos formātos"></v-list-item>
            <v-list-item prepend-icon="mdi-check-circle" title="Salīdzināt spēlētāju statistiku"></v-list-item>
          </v-list>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" variant="text" @click="sportDialog = false">
            Aizvērt
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-app>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useTheme } from 'vuetify';

const theme = useTheme();

const props = defineProps({
  userName: String,
  userRole: String,
  logoutRoute: String,
  newsApiKey: String,
});

// Data
const drawer = ref(false);
const searchQuery = ref('');
const newsSearchQuery = ref('sports');
const newsLoading = ref(false);
const newsError = ref('');
const newsArticles = ref([]);
const searchHistory = ref([]);
const sportDialog = ref(false);
const selectedSport = ref(null);

const sports = ref([
  {
    id: 1,
    title: 'Basketbols',
    icon: '🏀',
    description: 'Ievadiet un analizējiet basketbola spēļu statistiku: punkti, piespēles, atlēkušās bumbas, bloķējumi un vairāk.',
    image: 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400',
    detailedText: 'Basketbola statistikas moduļa ietvaros varat ievadīt un analizēt visus svarīgākos spēles rādītājus.',
  },
  {
    id: 2,
    title: 'Futbols',
    icon: '⚽',
    description: 'Uzglabājiet futbola spēļu datus: vārti, piespēles, precizitāte, aizsardzības darbības.',
    image: 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=400',
    detailedText: 'Futbola statistikas apkopošana ietver gan individuālo spēlētāju, gan komandas analīzi.',
  },
  {
    id: 3,
    title: 'Volejbols',
    icon: '🏐',
    description: 'Reģistrējiet volejbola statistiku: servēšana, uzbrukumi, bloķi, uzkritieni.',
    image: 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=400',
    detailedText: 'Volejbola statistikas modulis ļauj detalizēti uzglabāt servēšanas un uzbrukumu statistiku.',
  },
]);

const articles = ref([
  'Kā efektīvi analizēt basketbola statistiku',
  'SQL datubāzes optimizācija sporta datiem',
  'Top 10 statistikas rādītāji futbolā',
  'Kā izveidot profesionālas sporta atskaites',
  'Datu vizualizācija sporta analītikā',
]);

// Computed
const userInitial = computed(() => {
  return props.userName ? props.userName.charAt(0).toUpperCase() : 'U';
});

const isDark = computed(() => theme.global.current.value.dark);

const filteredSports = computed(() => {
  if (!searchQuery.value) return sports.value;
  const query = searchQuery.value.toLowerCase();
  return sports.value.filter(sport =>
    sport.title.toLowerCase().includes(query) ||
    sport.description.toLowerCase().includes(query)
  );
});

const filteredArticles = computed(() => {
  if (!searchQuery.value) return articles.value;
  const query = searchQuery.value.toLowerCase();
  return articles.value.filter(article =>
    article.toLowerCase().includes(query)
  );
});

const filteredResults = computed(() => {
  return filteredSports.value.length + filteredArticles.value.length;
});

// Methods
const toggleTheme = () => {
  theme.global.name.value = isDark.value ? 'light' : 'dark';
  localStorage.setItem('theme', theme.global.name.value);
};

const scrollTo = (id) => {
  drawer.value = false;
};

const logout = () => {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = props.logoutRoute;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = csrfToken;
  
  form.appendChild(csrfInput);
  document.body.appendChild(form);
  form.submit();
};

const openSportDialog = (sport) => {
  selectedSport.value = sport;
  sportDialog.value = true;
};

const searchNews = async () => {
  if (!newsSearchQuery.value.trim()) {
    newsError.value = 'Lūdzu, ievadiet meklēšanas vārdu!';
    return;
  }

  saveToHistory(newsSearchQuery.value);
  newsLoading.value = true;
  newsError.value = '';
  newsArticles.value = [];

  try {
    const response = await fetch(
      `https://newsapi.org/v2/everything?q=${encodeURIComponent(newsSearchQuery.value)}&apiKey=${props.newsApiKey}&language=en&pageSize=12&sortBy=publishedAt`
    );

    if (!response.ok) {
      throw new Error('Neizdevās ielādēt ziņas');
    }

    const data = await response.json();

    if (data.articles && data.articles.length > 0) {
      newsArticles.value = data.articles;
    } else {
      newsError.value = 'Dati nav pieejami. Mēģiniet citu meklēšanas vārdu.';
    }
  } catch (error) {
    newsError.value = 'Kļūda: ' + error.message;
  } finally {
    newsLoading.value = false;
  }
};

const saveToHistory = (query) => {
  let history = JSON.parse(localStorage.getItem('newsSearchHistory')) || [];
  history = history.filter(item => item !== query);
  history.unshift(query);
  history = history.slice(0, 5);
  localStorage.setItem('newsSearchHistory', JSON.stringify(history));
  searchHistory.value = history;
};

const searchFromHistory = (term) => {
  newsSearchQuery.value = term;
  searchNews();
};

const clearHistory = () => {
  localStorage.removeItem('newsSearchHistory');
  searchHistory.value = [];
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('lv-LV', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

// Lifecycle
onMounted(() => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    theme.global.name.value = savedTheme;
  }

  const history = JSON.parse(localStorage.getItem('newsSearchHistory')) || [];
  searchHistory.value = history;

  searchNews();
});
</script>

<style scoped>
/* Hero Section Animation */
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

/* Gradient Text Animation */
.gradient-text {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: slideInDown 0.8s ease-in-out;
}

/* Sport Cards Animations */
.sport-card {
  transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
              box-shadow 0.4s ease;
  cursor: pointer;
  animation: fadeInUp 0.6s ease-in-out;
  animation-fill-mode: both;
}

.sport-card:nth-child(1) { animation-delay: 0.1s; }
.sport-card:nth-child(2) { animation-delay: 0.2s; }
.sport-card:nth-child(3) { animation-delay: 0.3s; }

.sport-card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
}

.sport-card :deep(.v-img) {
  transition: transform 0.5s ease;
}

.sport-card:hover :deep(.v-img) {
  transform: scale(1.1);
}

/* News Cards Animation */
.v-card {
  animation: fadeInUp 0.6s ease-in-out;
  animation-fill-mode: both;
}

/* Staggered animation for news cards */
.v-col:nth-child(1) .v-card { animation-delay: 0.1s; }
.v-col:nth-child(2) .v-card { animation-delay: 0.2s; }
.v-col:nth-child(3) .v-card { animation-delay: 0.3s; }
.v-col:nth-child(4) .v-card { animation-delay: 0.4s; }
.v-col:nth-child(5) .v-card { animation-delay: 0.5s; }
.v-col:nth-child(6) .v-card { animation-delay: 0.6s; }

/* Button Hover Animation */
.v-btn {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.v-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
}

/* Search Field Animation */
.v-text-field {
  animation: slideInDown 0.6s ease-in-out;
  animation-delay: 0.3s;
  animation-fill-mode: both;
}

.v-text-field:focus-within {
  transform: scale(1.02);
  transition: transform 0.3s ease;
}

/* Chip Animation */
.v-chip {
  animation: bounceIn 0.5s ease;
  transition: all 0.3s ease;
}

.v-chip:hover {
  transform: scale(1.1);
}

/* Dialog Animation */
.v-dialog {
  animation: zoomIn 0.3s ease;
}

/* Loading Animation */
.v-progress-circular {
  animation: pulse 1.5s ease-in-out infinite;
}

/* Drawer Animation */
.v-navigation-drawer {
  animation: slideInLeft 0.3s ease;
}

/* Alert Animation */
.v-alert {
  animation: slideInDown 0.5s ease;
}

/* Footer Animation */
.v-footer {
  animation: fadeIn 1s ease-in-out;
  animation-delay: 0.8s;
  animation-fill-mode: both;
}

/* Keyframes */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translateY(-50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInLeft {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(0);
  }
}

@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes zoomIn {
  from {
    opacity: 0;
    transform: scale(0.5);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background: var(--v-theme-surface);
}

::-webkit-scrollbar-thumb {
  background: var(--v-theme-primary);
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--v-theme-secondary);
}

/* Height utilities */
.h-100 {
  height: 100%;
}

/* Hover effects for icons */
.v-icon {
  transition: all 0.3s ease;
}

.v-btn:hover .v-icon {
  transform: rotate(10deg) scale(1.1);
}

/* App Bar Animation */
.v-app-bar {
  animation: slideInDown 0.5s ease;
}

/* Card content smooth transitions */
.v-card-text, .v-card-title {
  transition: all 0.3s ease;
}

/* Image zoom on hover */
.v-img {
  overflow: hidden;
}

.v-card:hover .v-img {
  transform: scale(1.05);
}

/* List item hover */
.v-list-item {
  transition: all 0.3s ease;
}

.v-list-item:hover {
  background-color: rgba(37, 99, 235, 0.1);
  transform: translateX(5px);
}
</style>