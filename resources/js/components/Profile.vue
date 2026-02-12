<template>
  <v-app>
    <v-app-bar color="primary" dark>
      <v-btn icon @click="$router.back()">
        <v-icon>mdi-arrow-left</v-icon>
      </v-btn>
      <v-toolbar-title>Mans Profils</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container class="my-8">
        <v-row justify="center">
          <v-col cols="12" md="8">
            <!-- Profile Card -->
            <v-card class="mb-6">
              <v-card-title class="text-h5">
                <v-icon class="mr-2">mdi-account-circle</v-icon>
                Profila Informācija
              </v-card-title>

              <v-divider></v-divider>

              <v-card-text class="pa-6">
                <v-form ref="form" @submit.prevent="updateProfile">
                  <!-- Success Alert -->
                  <v-alert
                    v-if="successMessage"
                    type="success"
                    variant="tonal"
                    closable
                    class="mb-4"
                  >
                    {{ successMessage }}
                  </v-alert>

                  <!-- Error Alert -->
                  <v-alert
                    v-if="errorMessage"
                    type="error"
                    variant="tonal"
                    closable
                    class="mb-4"
                  >
                    {{ errorMessage }}
                  </v-alert>

                  <!-- Avatar -->
                  <div class="text-center mb-6">
                    <v-avatar size="120" color="primary">
                      <span class="text-h2">{{ userInitial }}</span>
                    </v-avatar>
                  </div>

                  <!-- Name -->
                  <v-text-field
                    v-model="formData.name"
                    label="Vārds"
                    prepend-icon="mdi-account"
                    variant="outlined"
                    :rules="[rules.required]"
                  ></v-text-field>

                  <!-- Email -->
                  <v-text-field
                    v-model="formData.email"
                    label="E-pasts"
                    prepend-icon="mdi-email"
                    variant="outlined"
                    type="email"
                    :rules="[rules.required, rules.email]"
                  ></v-text-field>

                  <!-- Role (Read-only) -->
                  <v-text-field
                    :model-value="userRole"
                    label="Loma"
                    prepend-icon="mdi-shield-account"
                    variant="outlined"
                    readonly
                    disabled
                  ></v-text-field>

                  <v-divider class="my-6"></v-divider>

                  <h3 class="text-h6 mb-4">Mainīt Paroli</h3>

                  <!-- Current Password -->
                  <v-text-field
                    v-model="formData.current_password"
                    label="Pašreizējā Parole"
                    prepend-icon="mdi-lock"
                    variant="outlined"
                    type="password"
                    hint="Atstājiet tukšu, ja nevēlaties mainīt paroli"
                  ></v-text-field>

                  <!-- New Password -->
                  <v-text-field
                    v-model="formData.new_password"
                    label="Jaunā Parole"
                    prepend-icon="mdi-lock-reset"
                    variant="outlined"
                    type="password"
                    :rules="formData.current_password ? [rules.minLength] : []"
                  ></v-text-field>

                  <!-- Confirm Password -->
                  <v-text-field
                    v-model="formData.new_password_confirmation"
                    label="Apstiprināt Jauno Paroli"
                    prepend-icon="mdi-lock-check"
                    variant="outlined"
                    type="password"
                    :rules="formData.new_password ? [rules.passwordMatch] : []"
                  ></v-text-field>

                  <!-- Action Buttons -->
                  <v-card-actions class="px-0 pt-4">
                    <v-btn
                      color="primary"
                      size="large"
                      type="submit"
                      :loading="loading"
                      block
                    >
                      <v-icon start>mdi-content-save</v-icon>
                      Saglabāt Izmaiņas
                    </v-btn>
                  </v-card-actions>
                </v-form>
              </v-card-text>
            </v-card>

            <!-- Account Info Card -->
            <v-card>
              <v-card-title class="text-h6">
                <v-icon class="mr-2">mdi-information</v-icon>
                Konta Informācija
              </v-card-title>
              <v-divider></v-divider>
              <v-list>
                <v-list-item>
                  <template v-slot:prepend>
                    <v-icon>mdi-calendar</v-icon>
                  </template>
                  <v-list-item-title>Reģistrācijas Datums</v-list-item-title>
                  <v-list-item-subtitle>{{ createdAt }}</v-list-item-subtitle>
                </v-list-item>
              </v-list>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user: Object,
  errors: Object,
  success: String,
});

const formData = ref({
  name: props.user.name,
  email: props.user.email,
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
});

const loading = ref(false);
const successMessage = ref(props.success || '');
const errorMessage = ref('');

const userInitial = computed(() => {
  return props.user.name ? props.user.name.charAt(0).toUpperCase() : 'U';
});

const userRole = computed(() => {
  return props.user.role === 'admin' ? 'Administrator' : 'Lietotājs';
});

const createdAt = computed(() => {
  return new Date(props.user.created_at).toLocaleDateString('lv-LV', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
});

const rules = {
  required: value => !!value || 'Šis lauks ir obligāts',
  email: value => {
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(value) || 'Nederīgs e-pasta formāts';
  },
  minLength: value => {
    return !value || value.length >= 8 || 'Parolei jābūt vismaz 8 simbolus garai';
  },
  passwordMatch: value => {
    return value === formData.value.new_password || 'Paroles nesakrīt';
  },
};

const updateProfile = async () => {
  loading.value = true;
  successMessage.value = '';
  errorMessage.value = '';

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    const response = await fetch('/profile', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify(formData.value),
    });

    const data = await response.json();

    if (response.ok) {
      successMessage.value = 'Profils veiksmīgi atjaunināts!';
      formData.value.current_password = '';
      formData.value.new_password = '';
      formData.value.new_password_confirmation = '';
    } else {
      errorMessage.value = data.message || 'Kļūda atjauninot profilu';
    }
  } catch (error) {
    errorMessage.value = 'Radās kļūda. Lūdzu mēģiniet vēlreiz.';
  } finally {
    loading.value = false;
  }
};
</script>