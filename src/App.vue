<template>
  <div class="container">
    <header>
      <div class="columns">
        <div class="column">
          <router-link :to="{ name: 'home' }" class="is-flex" style="align-items: center; justify-content: center; cursor: pointer">
            <img alt="France Nature Environnement" src="/logo-fne.png" style="max-width:333px; max-height: 100px" />
          </router-link>
        </div>
        <div class="column" style="display:flex;align-items:center;justify-content:center">
          <h1 class="title is-3 saira ml-5" style="color: #c90c0f">Opération de recensement du hérisson</h1>
        </div>
      </div>
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
      adresseInexacte: 0,
      adresse: null,
      codePostal: null,
      lat: null,
      lon: null,
      paysage: [],
      typeObs: [],
      nbAnimals: 1,
      nbAnimalsYoung: 0,
      nbDead: 1,
      nbDeadYoung: 0,
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
      invivo: false,
      company: null,
      fromWork: false,
    });
    provide('form', form);
  },
}
</script>