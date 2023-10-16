<template>
  <div class="notification is-alert" v-if="error !== null">
    <p>Erreur lors de l'envoi du formulaire.</p>
    <p>Détails : <pre>{{error}}</pre></p>
    <p>Veuillez <a href="javascript:window.location.href=atob('bWFpbHRvOmhlcmlzc29uQGZuZTI1ZnI')">nous envoyer un email</a> pour nous signaler ce dysfonctionnement.</p>
  </div>
  <template v-else-if="step === 2">
    <div class="is-flex" style="align-items: center">
      <img src="/icons/checked.svg" alt="OK" style="width: 40px" />
      <h2 class="ml-3 title is-4 sohoma">Félicitations, votre contribution vient s'ajouter aux {{nbContrib}} autres observations que nous cumulons déjà cette année !</h2>
    </div>
    <p>Nous vous remercions d’avoir participé à notre recensement participatif :)</p>
    <p>Retrouvez les actions de FNE 25 auxquelles vous pouvez participer sur <a href="https://www.fne25.fr">www.fne25.fr</a>, ainsi que les actions organisées par le réseau FNE régional sur <a href="https://www.fne-bfc.fr">www.fne-bfc.fr</a> !</p>
    <p>Vous pouvez également nous suivre sur les réseaux sociaux : <a href="https://www.facebook.com/fne25000/">Facebook</a>, <a href="https://twitter.com/fne_doubs">Twitter</a>, <a href="https://mastodon.social/@fne2590">Mastodon</a> </p>
    <p>Au plaisir,<br/>L'équipe FNE 25</p>
    <div class="has-text-centered">
      <a href="/" class="button">Faire une nouvelle observation</a>
    </div>
  </template>
  <template v-else-if="step === 0">
    <h3 class="is-size-5 has-text-weight-bold">Envoi du formulaire, veuillez patienter...</h3>
  </template>
  <template v-else-if="step === 1">
    <h3 class="is-size-5 has-text-weight-bold">Envoi de vos photos, veuillez patienter...</h3>
    <div class="has-text-centered mt-5" v-if="this.form.photos.length > 1">Envoi du fichier {{ filesCount }}&nbsp;/ &nbsp;{{ this.form.photos.length }} <progress class="progress is-info is-large" :value="filesCount" :max="this.form.photos.length">{{ filesCount }}&nbsp;/ &nbsp;{{ this.form.photos.length }}</progress></div>
    <div class="has-text-centered mt-5">Progression <progress class="progress is-warning is-large" :value="progress" max="100">{{ progress }}%</progress></div>
  </template>
  <template v-else>
    Envoi de vos réponses au questionnaire
  </template>
</template>

<script>
import { inject } from "vue";

function uploadRequest(url, formData, onProgress) {
  return new Promise(function (resolve, reject) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);
    xhr.onreadystatechange = function () {
      if (this.readyState === 4) {
        if (this.status >= 200 && this.status < 300) {
          resolve(xhr.responseText);
        } else {
          reject(xhr.responseText);
        }
      }
    };
    xhr.upload.onprogress = onProgress;
    xhr.onerror = function () {
      reject("Un problème réseau est survenu (coupure de connexion, délai d'envoi dépassé, ...)");
    };
    xhr.send(formData);
  });
}

export default {
  setup() {
    const form = inject("form");
    return { form };
  },
  data() {
    return {
      step: 0,
      progress: 0,
      filesCount: 0,
      nbContrib: 1,
      error: null,
    };
  },
  async created() {
    if (
      !this.form.date || !this.form.heure || !this.form.lat || !this.form.lon
      || !this.form.paysage.length || !this.form.typeObs.length
      || (this.form.typeObs.indexOf("Directe") === 0 && (parseInt(this.form.nbAnimals) + parseInt(this.form.nbAnimalsYoung) < 1 || parseInt(this.form.nbDead) + parseInt(this.form.nbDeadYoung) < 1 || !this.form.alive.length || (this.form.alive.indexOf("Morts") === 0 && !this.form.deadCause.length)))
      || (this.form.typeObs.indexOf("Indices de présence") === 0 && !this.form.indices.length)
      || (this.form.photos.length && (this.form.share === null || (this.form.share === "1" && this.form.public === null)))
      || !this.form.name || !this.form.firstname || !this.form.email || !this.form.email.match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)
      || this.form.subscribe === null
    ) {
      window.location.href = '/';
    }

    if (this.step === 0) {
      try {
        const {photos: dataToIgnore, ...dataToSend} = this.form;
        const response = await fetch(
          import.meta.env.PROD ? "/post.php" : "http://localhost/post.php",
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body:  JSON.stringify(dataToSend)
          }
        );
        const responseText = await response.text();

        if (!response.ok) {
          throw Error(responseText);
        }

        if (!responseText) {
          throw Error("Missing request ID");
        }

        this.step = 1;
        let blob;

        for (let file of this.form.photos) {
          this.filesCount++;
          this.progress = 0;
          //blob = await fetch(file).then((r) => r.blob());
          blob = await this.resizeImg(file);
          const formData = new FormData();
          formData.append("id", responseText);
          formData.append("pubPhoto", this.form.share || "0");
          formData.append("pubNom", this.form.public || "0");
          formData.append("file", blob);
          formData.append("num", this.filesCount);
          formData.append("name", `${this.form.name}-${this.form.firstname}` || "");
          await uploadRequest(
            import.meta.env.PROD ? "/upload.php" : "http://localhost/upload.php",
            formData,
            e => { this.progress = Math.round((100 * e.loaded) / e.total) }
          );
          window.URL.revokeObjectURL(file);
        }

        this.nbContrib = parseInt(responseText) - 1000000 + 4329; // new IDs offset
        this.step = 2;
      } catch (err) {
        this.error = err;
      }
    }
  },
  methods: {
    resizeImg (url) {
      return new Promise((resolve, reject) => {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        const img = new Image();

        img.onload = function () {
          canvas.width = 800;
          canvas.height = canvas.width * (img.height / img.width);
          ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, canvas.width, canvas.height);
          canvas.toBlob(blob => resolve(blob), "image/jpeg", 0.8);
          // before resolve we could remove canvas, ctx, img to avoid memleak
        }
        img.onerror = reject;
        img.src = url;
      });
    }
  }
};
</script>

<style>
</style>