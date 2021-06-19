<template>
  <div class="columns is-vcentered">
    <div class="column">
      <div class="columns is-vcentered">
        <div class="column is-4">
          <img src="/images/hedgehog.jpg" alt="herisson" />
        </div>
        <div class="column">
          <p>Le Hérisson d’Europe (Erinaceus europaeus) est un petit mammifère familier de nos campagnes, notamment de nos jardins, mais aussi présent en forêt.</p>
        </div>
      </div>
      <p>En participant à cette étude, vous contribuez à <strong>améliorer les connaissances scientifiques</strong> sur la <strong>répartition</strong> de ce petit animal dans nos régions.</p>
      <div class="notification is-warning is-flex mt-5" style="align-items: center">
        <img src="/icons/warning.svg" alt="attention" class="mr-4" style="width: 2rem;" />
        <div>Remplissez un seul formulaire même si vous rencontrez plusieurs hérissons en même temps.<br />Une photo de l'animal ou d'indices de sa présence (empreintes, etc.) est souhaitée.</div>
      </div>
      <div class="notification is-info is-flex" style="align-items: center">
        <img src="/icons/chrono.svg" alt="chrono" class="mr-4" style="width: 2.5rem;" />
        <div>Temps moyen pour répondre à l'enquête : 5min</div>
      </div>
    </div>
    <div class="column">
      <div class="notification">
        <div class="columns is-vcentered">
          <div class="column is-narrow has-text-centered">
            <img src="/icons/observation.svg" alt="observation" class="mr-4" style="width: 6rem;" />
          </div>
          <div class="column">
            <h2 class="title is-4 sohoma">Observer le hérisson</h2>
            <p>Voici quelques conseils pour ne pas déranger et stresser votre ami du jardin&nbsp;:</p>
          </div>
        </div>
        <ul class="with-bullet">
          <li>Le hérisson est un animal <strong>protégé depuis 1981</strong> et est un <strong>animal sauvage</strong>. Ainsi, il n'est pas nécessaire de le nourrir régulièrement, <strong>au risque de le rendre domestique</strong> ! Un petit coup de pouce exceptionnel (notamment au début et à la fin de l'hiver) est envisageable. Vous pouvez lui laisser de l'eau à disposition.</li>
          <li><strong>Évitez au maximum de manipuler les hérissons.</strong> Si une manipulation est nécessaire (hérisson en danger), utilisez autant que possible des gants et vêtements de protection puis lavez-vous les mains.</li>
          <li>Pour l’observer, utilisez une <strong>lumière douce</strong> et n’utilisez pas le flash pour le photographier.</li>
          <li>Évitez les <strong>gestes brusques</strong> et le <strong>bruit</strong>.</li>
          <li>Laissez du temps et de l’espace au hérisson : <strong>ne vous éternisez pas trop</strong> lors de votre visite et <strong>ne le visitez pas trop souvent</strong> (une a deux fois par semaine).</li>
          <li>Si vous voyez le hérisson en plein jour, <strong>vérifiez son comportement</strong>. S’il vous paraît anormal (aucun mouvement à votre approche, respiration courte, etc.), le hérisson est en danger : donnez lui de l’eau et à manger (des fruits frais, du poulet cuit ou à défaut des croquettes). Si le hérisson ne semble pas rétabli, appelez un vétérinaire ou un centre de soin (<a href="http://www.hameaudesherissons.fr/index.php?id_page=centresoins.php">carte des centres de soin</a>).</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="has-text-right">
    <router-link :to="{ name: 'observation' }" class="button is-success is-large">Commencer &#8250;</router-link>
  </div>
  <h2 class="is-size-6 has-text-weight-bold mt-4">Données personnelles</h2>
  <div class="has-text-justified is-size-7 mt-1">Les informations recueillies sur ce formulaire sont enregistrées dans un fichier informatisé par FNE 25-90. Elles sont traitées dans le cadre de l'étude de FNE 25-90 sur le hérisson d’Europe (Erinaceus europaeus) et conservées afin de vous informer des résultats de cette étude, pendant une période de 5 ans. Les données personnelles ne sont pas communiquées à des tiers.</div>
  <div class="has-text-justified is-size-7 mt-1">Conformément à la Loi Informatique et Libertés, vous disposez d'un droit d'accès, modification et suppression de vos données personnelles. Pour exercer ce droit, contacter FNE 25-90 par voie postale au 7 Rue Voirin 25000 BESANCON ou <a href="javascript:window.location.href=atob('bWFpbHRvOmhlcmlzc29uQGZuZTI1OTAub3Jn')">par e-mail</a>.</div>
</template>