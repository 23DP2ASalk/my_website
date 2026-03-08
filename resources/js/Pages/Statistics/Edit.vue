<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h4 mb-4">Edit Statistics</h1>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="8">
        <v-card>
          <v-card-text>
            <v-form @submit.prevent="submit">
              <!-- Sport Type Selection -->
              <v-select
                v-model="form.sport_type"
                :items="sportTypes"
                label="Sport Type"
                :error-messages="errors.sport_type"
                required
              ></v-select>

              <!-- Game Date -->
              <v-text-field
                v-model="form.game_date"
                label="Game Date"
                type="date"
                :error-messages="errors.game_date"
                required
              ></v-text-field>

              <!-- Minutes Played -->
              <v-text-field
                v-model.number="form.minutes_played"
                label="Minutes Played"
                type="number"
                min="0"
                :error-messages="errors.minutes_played"
              ></v-text-field>

              <v-divider class="my-4"></v-divider>

              <!-- Basketball Stats -->
              <div v-if="form.sport_type === 'Basketball'">
                <h3 class="text-h6 mb-3">Basketball Statistics</h3>
                
                <v-row>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.points"
                      label="Points"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.assists"
                      label="Assists"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.rebounds"
                      label="Rebounds"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.steals"
                      label="Steals"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.blocks"
                      label="Blocks"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </div>

              <!-- Football Stats -->
              <div v-if="form.sport_type === 'Football'">
                <h3 class="text-h6 mb-3">Football Statistics</h3>
                
                <v-row>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.goals"
                      label="Goals"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.assists_football"
                      label="Assists"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.shots"
                      label="Shots"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.passes"
                      label="Passes"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.tackles"
                      label="Tackles"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </div>

              <!-- Volleyball Stats -->
              <div v-if="form.sport_type === 'Volleyball'">
                <h3 class="text-h6 mb-3">Volleyball Statistics</h3>
                
                <v-row>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.spikes"
                      label="Spikes"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.blocks_volleyball"
                      label="Blocks"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.serves"
                      label="Serves"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.digs"
                      label="Digs"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.aces"
                      label="Aces"
                      type="number"
                      min="0"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </div>

              <!-- Notes -->
              <v-divider class="my-4"></v-divider>

              <v-textarea
                v-model="form.notes"
                label="Notes (optional)"
                rows="3"
              ></v-textarea>

              <!-- Actions -->
              <v-card-actions>
                <v-btn
                  color="primary"
                  type="submit"
                  :loading="processing"
                  prepend-icon="mdi-content-save"
                >
                  Update Statistics
                </v-btn>
                <v-btn
                  variant="text"
                  :href="route('statistics.index')"
                >
                  Cancel
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  statistic: Object,
  sportTypes: Array
})

const form = reactive({
  sport_type: props.statistic.sport_type,
  game_date: props.statistic.game_date,
  minutes_played: props.statistic.minutes_played,
  notes: props.statistic.notes,
  // Basketball
  points: props.statistic.points,
  assists: props.statistic.assists,
  rebounds: props.statistic.rebounds,
  steals: props.statistic.steals,
  blocks: props.statistic.blocks,
  // Football
  goals: props.statistic.goals,
  assists_football: props.statistic.assists_football,
  shots: props.statistic.shots,
  passes: props.statistic.passes,
  tackles: props.statistic.tackles,
  // Volleyball
  spikes: props.statistic.spikes,
  blocks_volleyball: props.statistic.blocks_volleyball,
  serves: props.statistic.serves,
  digs: props.statistic.digs,
  aces: props.statistic.aces
})

const errors = ref({})
const processing = ref(false)

const submit = () => {
  processing.value = true
  
  router.put(route('statistics.update', props.statistic.id), form, {
    onError: (err) => {
      errors.value = err
      processing.value = false
    },
    onSuccess: () => {
      processing.value = false
    }
  })
}

const route = (name, params = null) => {
  let url = `/${name.replace('.', '/')}`
  if (params) {
    url += `/${params}`
  }
  return url
}
</script>
