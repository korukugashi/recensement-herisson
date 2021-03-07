<template>
  <h2 class="title is-4 sohoma">Votre avis sur l'enquête</h2>
  <h3 class="title is-5">Comment avez-vous trouvé cette expérience de sciences participatives ?&nbsp;*</h3>
  <select-card :options="[
    {label: 'Très intéressante', img: 'satisfait10.svg'},
    {label: 'Intéressante', img: 'satisfait9.svg'},
    {label: 'Neutre', img: 'satisfait5.svg'},
    {label: 'Peu intéressante', img: 'satisfait1.svg'},
    {label: 'Pas du tout intéressante', img: 'satisfait0.svg'},
    {label: 'Ne se prononce pas', img: 'satisfaitnull.svg'},
  ]" :value="form.satisfaction" @change="val => form.satisfaction = val" max="1" />
  <div class="field">
    <label class="label" for="subscribe">Souhaitez-vous recevoir par email des informations sur cette action (résultats de l’étude, documentation sur le hérisson, renouvellement de l’enquête) ?&nbsp;*</label>
    <div class="control">
      <label class="radio"><input type="radio" id="subscribe" value="1" name="subscribe" v-model="form.subscribe" /> Oui</label>
      <label class="radio"><input type="radio" value="0" name="subscribe" v-model="form.subscribe" /> Non</label>
    </div>
  </div>
  <div class="field">
    <label class="label" for="remarks">Avez-vous des remarques/suggestions ?</label>
    <div class="control">
      <textarea class="textarea" id="remarks" v-model="form.remarks"></textarea>
    </div>
  </div>
  <div class="is-flex mt-5" style="justify-content: space-between; align-items: center">
    <router-link :to="{ name: 'coord' }" class="button">&#8249; Précédent</router-link>
    <router-link :to="{ name: 'confirm' }" class="button is-success is-large" :class="{'is-disabled': isNextDisabled}" @click="clickWard">Valider &#8250;</router-link>
  </div>
  <div class="notification is-warning mt-5" v-if="isNextDisabled">Veuillez renseigner tous les champs obligatoires (marqués d'une *)</div>
</template>

<script>
import { inject } from "vue";
import SelectCard from './SelectCard.vue';

export default {
  components: { SelectCard },
  setup() {
    const form = inject("form");
    return { form };
  },
  created() {
    if (
      !this.form.date || !this.form.heure || !this.form.lat || !this.form.lon
      || !this.form.paysage.length || !this.form.typeObs.length
      || (this.form.typeObs.indexOf("Directe") === 0 && (parseInt(this.form.nbAnimals) < 1 || parseInt(this.form.nbDead) < 1 || !this.form.alive.length || (this.form.alive.indexOf("Morts") === 0 && !this.form.deadCause.length)))
      || (this.form.typeObs.indexOf("Indices de présence") === 0 && !this.form.indices.length)
      || (this.form.photos.length && (this.form.share === null || (this.form.share === "1" && this.form.public === null)))
      || !this.form.name || !this.form.firstname || !this.form.email || !this.form.email.match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)
    ) {
      window.location.href = '/';
    }
  },
  computed: {
    isNextDisabled() {
      return this.form.subscribe === null;
    }
  },
  methods: {
    clickWard(e) {
      if (this.isNextDisabled) {
        e.preventDefault();
      }
    }
  }
}
</script>

<style>

</style>