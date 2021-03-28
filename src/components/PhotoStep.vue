<template>
  <h2 class="title is-4 sohoma">Vos photos</h2>
  <p>Veuillez joindre une ou plusieurs photographie(s) {{ form.typeObs === "Indices de présence" ? "des indices observés avec un élément d’échelle (bout de chaussure, main, doigt, etc.)" : (parseInt(form.nbAnimals) + parseInt(form.nbAnimalsYoung) > 1 ? "des hérissons observés" : "du hérisson observé") }}.</p>
  <div class="columns is-vcentered">
    <div class="column is-narrow">
      <div class="file is-boxed" v-if="form.photos.length < 6">
        <label class="file-label">
          <input class="file-input" type="file" ref="file" accept="image/jpeg" @change="selectFile">
          <span class="file-cta">
            <img src="/icons/upload.svg" alt="Ajouter une photo" style="width: 70px" />
            <span class="file-label mt-3 has-text-weight-bold">
              Ajouter une photo
            </span>
          </span>
        </label>
      </div>
      <div v-else class="is-italic">6 photos maximum</div>
    </div>
    <div class="column">
      <div class="is-flex is-flex-wrap-wrap">
        <figure class="image is-128x128 mr-3" v-for="photo in form.photos" :key="photo" style="position: relative">
          <img :src="photo" alt="" />
          <button class="delete" @click="removeFile(photo)" style="position: absolute; top: 5px; right: 5px"></button>
        </figure>
      </div>
    </div>
  </div>
  <div class="field mt-6" v-if="form.photos.length > 0">
    <label class="label" for="share">Nous autorisez-vous à utiliser et diffuser votre/vos photographie(s) dans le cadre de ce projet ou lors d’un autre projet ?&nbsp;*</label>
    <div class="control">
      <label class="radio"><input type="radio" id="share" value="1" name="share" v-model="form.share" /> Oui</label>
      <label class="radio"><input type="radio" value="0" name="share" v-model="form.share" /> Non</label>
    </div>
  </div>
  <template v-if="form.photos.length > 0 && form.share === '1'">
    <div class="field">
      <label class="label" for="public">Souhaitez-vous que votre nom apparaisse sur la photographie ?&nbsp;*</label>
      <div class="control">
        <label class="radio"><input type="radio" id="public" value="1" name="public" v-model="form.public" /> Oui</label>
        <label class="radio"><input type="radio" value="0" name="public" v-model="form.public" /> Non</label>
      </div>
    </div>
  </template>
  <div class="is-flex mt-5" style="justify-content: space-between; align-items: center">
    <router-link :to="{ name: 'observation' }" class="button">&#8249; Précédent</router-link>
    <router-link :to="{ name: 'coord' }" class="button is-success is-large" :class="{'is-disabled': isNextDisabled}" @click="clickWard">Suivant &#8250;</router-link>
  </div>
  <div class="notification is-warning mt-5" v-if="isNextDisabled">Veuillez renseigner tous les champs obligatoires (marqués d'une *)</div>
</template>

<script>
import { inject } from "vue";

export default {
  setup() {
    const form = inject("form");
    return { form };
  },
  created() {
    if (!this.form.date || !this.form.heure || !this.form.lat || !this.form.lon
        || !this.form.paysage.length || !this.form.typeObs.length ||
        (this.form.typeObs.indexOf("Directe") === 0 && (parseInt(this.form.nbAnimals) + parseInt(this.form.nbAnimalsYoung) < 1 || parseInt(this.form.nbDead) + parseInt(this.form.nbDeadYoung) < 1 || !this.form.alive.length || (this.form.alive.indexOf("Morts") === 0 && !this.form.deadCause.length))) ||
        (this.form.typeObs.indexOf("Indices de présence") === 0 && !this.form.indices.length)) {
      window.location.href = '/';
    }
  },
  computed: {
    isNextDisabled() {
      return this.form.photos.length && 
        (this.form.share === null || (this.form.share === "1" && this.form.public === null));
    }
  },
  methods: {
    selectFile() {
      const url = window.URL.createObjectURL(this.$refs.file.files.item(0));
      this.form.photos = this.form.photos.concat([url]);
    },
    removeFile(file) {
      this.form.photos = this.form.photos.filter(item => item !== file);
      window.URL.revokeObjectURL(file);
    },
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