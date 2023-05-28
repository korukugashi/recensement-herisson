<template>
  <h2 class="title is-4 sohoma">Votre observation</h2>
  <div class="columns mt-1">
    <div class="column is-4">
      <div class="field">
        <label class="label" for="date">Date&nbsp;*</label>
        <div class="control">
          <input class="input" type="date" id="date" placeholder="Date de l'observation (jj/mm/yyyy)" required v-model="form.date" />
        </div>
      </div>
      <div class="field">
        <label class="label" for="hour">Heure&nbsp;*</label>
        <div class="control">
          <input class="input" type="time" id="hour" placeholder="Heure de l'observation (HH:MM)" required v-model="form.heure" />
        </div>
      </div>
      <div class="field">
        <label class="label" for="address">Recherchez l'adresse&nbsp;<span v-if="!form.lat">*</span></label>
        <div class="control">
          <autocomplete
            id="address"
            initValue=""
            anchor="label"
            :debounce="300"
            placeholder="Rechercher une adresse"
            :classes="{ input: 'input', list: 'data-list', item: 'data-list-item' }"
            :on-select="selectAdresse"
            :onInput="clearAdresse"
            :onShouldGetData="getAdresse"
          />
          <div v-if="fetchAddressError" style="color: red; font-weight: bold; font-size: 0.8rem">Une erreur s'est produite lors de la recherche de l'adresse, veuillez réessayer plus tard ou nous contacter. Autrement, vous pouvez aussi placer directement sur la carte.</div>
        </div>
      </div>
      <div class="field" v-if="form.adresse || form.lat">
        <label class="checkbox" for="adresseInexacte">
          <input type="checkbox" id="adresseInexacte" true-value="1" false-value="0" v-model="form.adresseInexacte" />
          La localisation que j'ai saisie n'est pas précise <span class="is-size-7">(impossibilité de s'arrêter sur le bord de la route, chemin en forêt non localisable, etc.)</span>
        </label>
      </div>
    </div>
    <div class="column">
      <div class="field">
        <label class="label">Corrigez si nécessaire l'emplacement sur la carte</label>
        <div class="control">
          <map-locator />
        </div>
      </div>
    </div>
  </div>
  <h4>Quels éléments de paysage trouve-t-on autour de l'observation&nbsp;?&nbsp;*</h4>
  <select-card :options="[
    {label: 'Route', img: 'road.svg'},
    {label: 'Voie ferrée', img: 'rail.svg'},
    {label: 'Milieu urbanisé', img: 'city.svg'},
    {label: 'Jardin individuel', img: 'jardin.svg'},
    {label: 'Jardin partagé', img: 'cloture.svg'},
    {label: 'Parc', img: 'parc.svg'},
    {label: 'Verger', img: 'arbre-fruitier.svg'},
    {label: 'Prairie, pâture', img: 'prairie.svg'},
    {label: 'Champ cultivé', img: 'champ.svg'},
    {label: 'Haie', img: 'buisson.svg'},
    {label: 'Friche buissonnante', img: 'grass.svg'},
    {label: 'Lisière de forêt', img: 'lisiere.svg'},
    {label: 'Forêt ou bois', img: 'foret.svg'},
    {label: 'Zone humide ou cours d\'eau', img: 'riviere.svg'},
  ]" :value="form.paysage" @change="val => form.paysage = val" />
  <h4>Quel a été le type d’observation&nbsp;?&nbsp;*</h4>
  <select-card :options="[
    {label: 'Directe', img: 'hedgehog.svg'},
    {label: 'Indices de présence', img: 'detective.svg'},
  ]" :value="form.typeObs" @change="val => form.typeObs = val" max="1" />
  <template v-if="form.typeObs.indexOf('Directe') > -1">
    <h4>Les hérissons observés étaient&nbsp;:&nbsp;*</h4>
    <select-card :options="[
      {label: 'Vivants', img: 'vie.svg'},
      {label: 'Morts', img: 'mort.svg'},
    ]" :value="form.alive" @change="val => form.alive = val" max="2" />
    <template v-if="form.alive.indexOf('Vivants') > -1">
      <div class="field">
        <label class="label" for="nb-animals">Combien de hérissons <b>vivants</b> avez-vous vu&nbsp;?&nbsp;*</label>
        <div class="control">
          <div class="columns">
            <div class="column is-narrow">
              <label for="nb-animals" style="position: relative; top: 5px">Adultes :</label> <input class="input" type="number" id="nb-animals" v-model="form.nbAnimals" step="1" min="0" max="99" required style="max-width: 5rem;text-align:center" />
            </div>
            <div class="column">
              <label for="nb-animals-young" style="position: relative; top: 5px">Juvéniles :</label> <input class="input" type="number" id="nb-animals-young" v-model="form.nbAnimalsYoung" step="1" min="0" max="99" required style="max-width: 5rem;text-align:center" />
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-if="form.alive.indexOf('Morts') > -1">
      <div class="field">
        <label class="label" for="nb-dead">Combien de hérissons <b>morts</b> avez-vous vu&nbsp;?&nbsp;*</label>
        <div class="control">
          <div class="columns">
            <div class="column is-narrow">
              <label for="nb-dead" style="position: relative; top: 5px">Adultes :</label> <input class="input" type="number" id="nb-dead" v-model="form.nbDead" step="1" min="0" max="99" required style="max-width: 5rem;text-align:center" />
            </div>
            <div class="column">
              <label for="nb-dead-young" style="position: relative; top: 5px">Juvéniles :</label> <input class="input" type="number" id="nb-dead-young" v-model="form.nbDeadYoung" step="1" min="0" max="99" required style="max-width: 5rem;text-align:center" />
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-if="form.alive.indexOf('Morts') > -1">
      <h4>Quelle a été selon vous la cause du décès&nbsp;?&nbsp;*</h4>
      <select-card :options="[
        {label: 'Collision routière', img: 'accident.svg'},
        {label: 'Empoisonnement', img: 'pesticide.svg'},
        {label: 'Prédation', img: 'blaireau.svg'},
        {label: 'Parasitisme', img: 'parasite.svg'},
        {label: 'Accident (noyade, chute...)', img: 'trou.svg'},
        {label: 'Autre', img: 'question.svg'},
      ]" :value="form.deadCause" @change="val => form.deadCause = val" max="1" />
    </template>
  </template>
  <div class="columns is-vcentered" v-if="form.typeObs.indexOf('Indices de présence') > -1">
    <div class="column is-narrow">
      <h4>Les indices observés *</h4>
      <select-card :options="[
        {label: 'Empreintes', img: 'empreintes.svg'},
        {label: 'Crottes', img: 'crotte.svg'},
        {label: 'Nid', img: 'nid.svg'},
        {label: 'Bruit', img: 'sound.svg'},
      ]" :value="form.indices" @change="val => form.indices = val" />
    </div>
    <div class="column has-text-centered">
      <iframe width="300" height="170" src="https://www.youtube.com/embed/DkPEsJK0FNo" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
  </div>
  <div class="field">
    <label class="label" for="info">Complément d'informations</label>
    <textarea class="textarea" id="info" v-model="form.info"></textarea>
  </div>
  <div class="is-flex mt-5" style="justify-content: space-between; align-items: center">
    <router-link :to="{ name: 'home' }" class="button">&#8249; Précédent</router-link>
    <router-link :to="{ name: 'photo' }" class="button is-success is-large" :class="{'is-disabled': isNextDisabled}" @click="clickWard">Suivant &#8250;</router-link>
  </div>
  <div class="notification is-warning mt-5" v-if="isNextDisabled">Veuillez renseigner tous les champs obligatoires (marqués d'une *)</div>
</template>

<script>
import { inject } from "vue";
import axios from "axios";

import MapLocator from "./MapLocator.vue";
import Autocomplete from "./Autocomplete.vue";
import SelectCard from './SelectCard.vue';

export default {
  components: { MapLocator, Autocomplete, SelectCard },
  setup() {
    const form = inject("form");
    return { form };
  },
  data() {
    return {
      fetchAddressError: false,
    };
  },
  created() {
    if (navigator.geolocation && !this.form.lon) {
      navigator.geolocation.getCurrentPosition(position => {
        this.form.lat  = position.coords.latitude;
        this.form.lon = position.coords.longitude;
      }, () => {});
    }
  },
  computed: {
    isNextDisabled() {
      return !this.form.date || !this.form.heure || !this.form.lat || !this.form.lon
        || !this.form.paysage.length || !this.form.typeObs.length ||
        (this.form.typeObs.indexOf("Directe") === 0 && (parseInt(this.form.nbAnimals) + parseInt(this.form.nbAnimalsYoung) < 1 || parseInt(this.form.nbDead) + parseInt(this.form.nbDeadYoung) < 1 || !this.form.alive.length || (this.form.alive.indexOf("Morts") === 0 && !this.form.deadCause.length))) ||
        (this.form.typeObs.indexOf("Indices de présence") === 0 && !this.form.indices.length);
    }
  },
  methods: {
    selectAdresse: function (value) {
      this.form.adresse = value.label;
      this.form.codePostal = value.postcode;
      this.form.lat = value.lat;
      this.form.lon = value.lon;
    },
    clearAdresse:  function () {
      this.form.adresse = null;
      this.form.codePostal = null;
      this.form.lat = null;
      this.form.lon = null;
    },
    getAdresse: async function (val) {
      this.fetchAddressError = false;
      if (val.length < 2) return [];
      try {
        const apiResponse = await axios.get("https://api-adresse.data.gouv.fr/search", { params: { q: val } })
        return apiResponse.data && apiResponse.data.features.map(feature => feature.properties ? ({
          label: feature.properties.label,
          postcode: feature.properties.postcode,
          lon: feature.geometry.coordinates[0],
          lat: feature.geometry.coordinates[1],
        }) : { label: "Erreur", x: 0, y: 0 });
      } catch (error) {
        this.fetchAddressError = true;
        return [];
      }
    },
    clickWard(e) {
      if (this.isNextDisabled) {
        e.preventDefault();
      }
    }
  }
};
</script>

<style>

</style>