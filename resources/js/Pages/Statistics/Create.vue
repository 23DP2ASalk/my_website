<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h4 mb-4">Add Statistics</h1>
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
                      :error-messages="errors.points"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.assists"
                      label="Assists"
                      type="number"
                      min="0"
                      :error-messages="errors.assists"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.rebounds"
                      label="Rebounds"
                      type="number"
                      min="0"
                      :error-messages="errors.rebounds"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.steals"
                      label="Steals"
                      type="number"
                      min="0"
                      :error-messages="errors.steals"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.blocks"
                      label="Blocks"
                      type="number"
                      min="0"
                      :error-messages="errors.blocks"
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
                      :error-messages="errors.goals"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.assists_football"
                      label="Assists"
                      type="number"
                      min="0"
                      :error-messages="errors.assists_football"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.shots"
                      label="Shots"
                      type="number"
                      min="0"
                      :error-messages="errors.shots"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.passes"
                      label="Passes"
                      type="number"
                      min="0"
                      :error-messages="errors.passes"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.tackles"
                      label="Tackles"
                      type="number"
                      min="0"
                      :error-messages="errors.tackles"
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
                      :error-messages="errors.spikes"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.blocks_volleyball"
                      label="Blocks"
                      type="number"
                      min="0"
                      :error-messages="errors.blocks_volleyball"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.serves"
                      label="Serves"
                      type="number"
                      min="0"
                      :error-messages="errors.serves"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.digs"
                      label="Digs"
                      type="number"
                      min="0"
                      :error-messages="errors.digs"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model.number="form.aces"
                      label="Aces"
                      type="number"
                      min="0"
                      :error-messages="errors.aces"
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
                :error-messages="errors.notes"
              ></v-textarea>

              <!-- Actions -->
              <v-card-actions>
                <v-btn
                  color="primary"
                  type="submit"
                  :loading="processing"
                  prepend-icon="mdi-content-save"
                >
                  Save Statistics
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

      <!-- Quick Stats Info -->
      <v-col cols="12" md="4">
        <v-card>
          <v-card-title>Quick Info</v-card-title>
          <v-card-text>
            <v-list density="compact">
              <v-list-item prepend-icon="mdi-information">
                <v-list-item-title>Fill in your game statistics</v-list-item-title>
              </v-list-item>
              <v-list-item prepend-icon="mdi-chart-line">
                <v-list-item-title>Track your progress over time</v-list-item-title>
              </v-list-item>
              <v-list-item prepend-icon="mdi-trophy">
                <v-list-item-title>Compare your performance</v-list-item-title>
              </v-list-item>
            </v-list>
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
  sportTypes: Array
})

const form = reactive({
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
})

const errors = ref({})
const processing = ref(false)

const submit = () => {
  processing.value = true
  
  router.post(route('statistics.store'), form, {
    onError: (err) => {
      errors.value = err
      processing.value = false
    },
    onSuccess: () => {
      processing.value = false
    }
  })
}

const route = (name) => {
  return `/${name.replace('.', '/')}`
}
</script>
