<template>
  <h2 class="title is-4 sohoma">Vos coordonnées</h2>
  <div class="mb-4">
    <label class="checkbox">
      <input type="checkbox" v-model="form.invivo" />
      Je contibue en tant que partenaire InVivo
    </label>
    <div class="columns mt-2" v-if="form.invivo">
      <div class="column">
        <div class="field">
          <label class="label" for="company">Société&nbsp;*</label>
          <div class="control">
            <input class="input" id="company" type="text" v-model="form.company" placeholder="Nom de votre entreprise" required />
          </div>
        </div>
      </div>
      <div class="column is-flex" style="align-items: flex-end;">
        <label class="checkbox mb-3">
          <input type="checkbox" v-model="form.fromWork" />
          L'observation est réalisée sur mon lieu de travail
        </label>
      </div>
    </div>
  </div>
  <div class="columns">
    <div class="column">
      <div class="field">
        <label class="label" for="firstname">Prénom&nbsp;*</label>
        <div class="control">
          <input class="input" id="firstname" type="text" v-model="form.firstname" placeholder="Votre prénom" required />
        </div>
      </div>
    </div>
    <div class="column">
      <div class="field">
        <label class="label" for="name">Nom&nbsp;*</label>
        <div class="control">
          <input class="input" id="name" type="text" v-model="form.name" placeholder="Votre nom" required />
        </div>
      </div>
    </div>
    <div class="column is-narrow">
      <div class="field">
        <label class="label" for="age">Tranche d'âge</label>
        <div class="control">
          <select class="select" id="age" v-model="form.old">
            <option></option>
            <option value="0-15">- de 15 ans</option>
            <option value="16-30">16 à 30 ans</option>
            <option value="31-45">31 à 45 ans</option>
            <option value="45-60">46 à 60 ans</option>
            <option value="61-75">61 à 75 ans</option>
            <option value="76-150">76 ans et plus</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="columns">
    <div class="column">
      <div class="field">
        <label class="label" for="user-address">Code postal / Commune</label>
        <div class="control">
          <autocomplete
            id="user-address"
            initValue=""
            anchor="label"
            :debounce="300"
            placeholder="Rechercher votre code postal ou commune"
            :classes="{ input: 'input', list: 'data-list', item: 'data-list-item' }"
            :on-select="selectAdresse"
            :onInput="clearAdresse"
            :onShouldGetData="getAdresse"
          />
        </div>
      </div>
    </div>
    <div class="column">
      <div class="field">
        <label class="label" for="email">Adresse email&nbsp;*</label>
        <div class="control">
          <input class="input" id="email" type="email" v-model="form.email" placeholder="Votre adresse email" />
        </div>
      </div>
    </div>
    <div class="column is-narrow">
      <div class="field">
        <label class="label" for="phone">Téléphone</label>
        <div class="control">
          <input class="input" id="phone" type="text" v-model="form.phone" placeholder="Votre numéro de téléphone" style="width: 10rem" />
        </div>
      </div>
    </div>
  </div>
  <div class="is-flex mt-5" style="justify-content: space-between; align-items: center">
    <router-link :to="{ name: 'photo' }" class="button">&#8249; Précédent</router-link>
    <router-link :to="{ name: 'avis' }" class="button is-success is-large" :class="{ 'is-disabled': isNextDisabled }" @click="clickWard"
      >Suivant &#8250;</router-link
    >
  </div>
  <div class="notification is-warning mt-5" v-if="isNextDisabled">Veuillez renseigner tous les champs obligatoires (marqués d'une *)</div>
</template>

<script>
import { inject } from "vue";
import axios from "axios";

import Autocomplete from "./Autocomplete.vue";

export default {
  components: { Autocomplete },
  setup() {
    const form = inject("form");
    return { form };
  },
  created() {
    if (
      !this.form.date ||
      !this.form.heure ||
      !this.form.lat ||
      !this.form.lon ||
      !this.form.paysage.length ||
      !this.form.typeObs.length ||
      (this.form.typeObs.indexOf("Directe") === 0 &&
        (parseInt(this.form.nbAnimals) + parseInt(this.form.nbAnimalsYoung) < 1 ||
          parseInt(this.form.nbDead) + parseInt(this.form.nbDeadYoung) < 1 ||
          !this.form.alive.length ||
          (this.form.alive.indexOf("Morts") === 0 && !this.form.deadCause.length))) ||
      (this.form.typeObs.indexOf("Indices de présence") === 0 && !this.form.indices.length) ||
      (this.form.photos.length && (this.form.share === null || (this.form.share === "1" && this.form.public === null)))
    ) {
      window.location.href = "/";
    }
  },
  computed: {
    isNextDisabled() {
      return (
        !this.form.name ||
        !this.form.firstname ||
        !this.form.email ||
        !this.form.email.match(
          /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        ) ||
        (this.form.invivo && !this.form.company)
      );
    },
  },
  methods: {
    selectAdresse: function (value) {
      this.form.userZipCode = value.postcode;
      this.form.userCity = value.city;
      this.form.userDep = value.dep;
    },
    clearAdresse: function () {
      this.form.userZipCode = null;
      this.form.userCity = null;
      this.form.userDep = null;
    },
    getAdresse: async function (val) {
      if (val.length < 2) return [];
      try {
        const apiResponse = await axios.get("https://api-adresse.data.gouv.fr/search", { params: { q: val, type: "municipality" } });
        return (
          apiResponse.data &&
          apiResponse.data.features.map((feature) =>
            feature.properties
              ? {
                  label: feature.properties.postcode + " " + feature.properties.label,
                  postcode: feature.properties.postcode,
                  city: feature.properties.label,
                  dep: feature.properties.context.split(", ")[0],
                }
              : { label: "Erreur" }
          )
        );
      } catch (error) {
        alert("Une erreur s'est produite lors de la recherche de l'adresse, veuillez réessayer plus tard ou nous contacter.");
        return [];
      }
    },
    clickWard(e) {
      if (this.isNextDisabled) {
        e.preventDefault();
      }
    },
  },
};
</script>

<style></style>
