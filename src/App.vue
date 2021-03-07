<template>
  <div class="container">
    <header>
      <router-link :to="{ name: 'home' }" class="is-flex" style="align-items: center; justify-content: center; cursor: pointer">
        <img alt="FNE 25-90" src="/logo-fne-25-90.svg" style="width: 100px" />
        <h1 class="title is-3 saira ml-5" style="color: #c90c0f">Opération de recensement du hérisson</h1>
      </router-link>
    </header>
    <section class="section">
      <router-view />
    </section>
  </div>
</template>

<script>
import { provide, reactive } from 'vue'

export default {
  setup() {
    const date = new Date();
    const form = reactive({
      date: date.toISOString().substring(0, 10),
      heure: date.toLocaleTimeString().substring(0, 5),
      adresse: null,
      codePostal: null,
      lat: null,
      lon: null,
      paysage: [],
      typeObs: [],
      nbAnimals: 1,
      nbDead: 1,
      alive: [],
      deadCause: [],
      indices: [],
      photos: [],
      public: null,
      share: null,
      firstname: null,
      name: null,
      userZipCode: null,
      userCity: null,
      userDep: null,
      old: null,
      info: null,
      email: null,
      phone: null,
      satisfaction: [],
      subscribe: null,
      remarks: null,
    });
    provide('form', form);
  },
}
</script>