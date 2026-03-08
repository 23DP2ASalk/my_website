<template>
  <v-container fluid>
    <!-- Header -->
    <v-row>
      <v-col cols="12">
        <div class="d-flex justify-space-between align-center mb-4">
          <h1 class="text-h4">My Statistics</h1>
          <v-btn
            color="primary"
            prepend-icon="mdi-plus"
            :href="route('statistics.create')"
          >
            Add Stats
          </v-btn>
        </div>
      </v-col>
    </v-row>

    <!-- Filters -->
    <v-row>
      <v-col cols="12" md="3">
        <v-select
          v-model="sportFilter"
          :items="sportTypes"
          label="Filter by Sport"
          clearable
          @update:modelValue="handleFilter"
        ></v-select>
      </v-col>

      <v-col cols="12" md="3">
        <v-text-field
          v-model="startDate"
          label="Start Date"
          type="date"
          @input="handleFilter"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="3">
        <v-text-field
          v-model="endDate"
          label="End Date"
          type="date"
          @input="handleFilter"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="3">
        <v-text-field
          v-model="search"
          label="Search notes"
          prepend-inner-icon="mdi-magnify"
          clearable
          @input="handleSearch"
        ></v-text-field>
      </v-col>
    </v-row>

    <!-- Statistics Cards -->
    <v-row v-if="statistics.data && statistics.data.length > 0">
      <v-col
        v-for="stat in statistics.data"
        :key="stat.id"
        cols="12"
        md="6"
        lg="4"
      >
        <v-card class="stat-card" hover>
          <v-card-title class="d-flex align-center">
            <v-icon :icon="getSportIcon(stat.sport_type)" size="large" class="mr-2"></v-icon>
            <span>{{ stat.sport_type }}</span>
            <v-spacer></v-spacer>
            <v-chip size="small" color="primary">
              {{ formatDate(stat.game_date) }}
            </v-chip>
          </v-card-title>

          <v-card-text>
            <!-- Display relevant stats based on sport type -->
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

            <div v-if="stat.minutes_played" class="mt-2">
              <v-chip size="small" prepend-icon="mdi-clock">
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
              size="small"
              variant="text"
              color="primary"
              :href="route('statistics.edit', stat.id)"
            >
              Edit
            </v-btn>
            <v-btn
              size="small"
              variant="text"
              color="error"
              @click="deleteStatistic(stat)"
            >
              Delete
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <!-- Empty State -->
    <v-row v-else>
      <v-col cols="12">
        <v-card class="text-center pa-8">
          <v-icon icon="mdi-chart-line" size="64" color="grey"></v-icon>
          <h3 class="mt-4 text-h6">No statistics yet</h3>
          <p class="text-grey mb-4">Start tracking your performance!</p>
          <v-btn
            color="primary"
            :href="route('statistics.create')"
          >
            Add Your First Stats
          </v-btn>
        </v-card>
      </v-col>
    </v-row>

    <!-- Pagination -->
    <v-row v-if="statistics.last_page > 1">
      <v-col cols="12" class="text-center">
        <v-pagination
          v-model="currentPage"
          :length="statistics.last_page"
          @update:modelValue="handlePageChange"
        ></v-pagination>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  statistics: Object,
  filters: Object
})

const sportTypes = [
  { title: 'All Sports', value: 'all' },
  { title: 'Basketball', value: 'Basketball' },
  { title: 'Football', value: 'Football' },
  { title: 'Volleyball', value: 'Volleyball' }
]

const sportFilter = ref(props.filters?.sport_type || 'all')
const startDate = ref(props.filters?.start_date || '')
const endDate = ref(props.filters?.end_date || '')
const search = ref(props.filters?.search || '')
const currentPage = ref(props.statistics.current_page)

const handleFilter = () => {
  router.get(route('statistics.index'), {
    sport_type: sportFilter.value,
    start_date: startDate.value,
    end_date: endDate.value,
    search: search.value
  }, { preserveState: true })
}

const handleSearch = () => {
  handleFilter()
}

const handlePageChange = (page) => {
  router.get(route('statistics.index', { page }))
}

const deleteStatistic = (stat) => {
  if (confirm(`Delete statistics for ${stat.sport_type} on ${formatDate(stat.game_date)}?`)) {
    router.delete(route('statistics.destroy', stat.id))
  }
}

const getSportIcon = (sportType) => {
  const icons = {
    'Basketball': 'mdi-basketball',
    'Football': 'mdi-soccer',
    'Volleyball': 'mdi-volleyball'
  }
  return icons[sportType] || 'mdi-trophy'
}

const getRelevantStats = (stat) => {
  switch(stat.sport_type) {
    case 'Basketball':
      return {
        'Points': stat.points,
        'Assists': stat.assists,
        'Rebounds': stat.rebounds,
        'Steals': stat.steals,
        'Blocks': stat.blocks
      }
    case 'Football':
      return {
        'Goals': stat.goals,
        'Assists': stat.assists_football,
        'Shots': stat.shots,
        'Passes': stat.passes,
        'Tackles': stat.tackles
      }
    case 'Volleyball':
      return {
        'Spikes': stat.spikes,
        'Blocks': stat.blocks_volleyball,
        'Serves': stat.serves,
        'Digs': stat.digs,
        'Aces': stat.aces
      }
    default:
      return {}
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const route = (name, params = null) => {
  let url = `/${name.replace('.', '/')}`
  if (params) {
    url += `/${params}`
  }
  return url
}
</script>

<style scoped>
.stat-card {
  height: 100%;
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
</style>
