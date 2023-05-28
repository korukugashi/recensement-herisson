<template>
  <div id="map-locator" class="box"></div>
</template>

<script>
import { inject } from "vue";
import "leaflet/dist/leaflet.css";
import "leaflet";
import { latLng } from 'leaflet';

const DEFAULT_ZOOM = 6;
const DEFAULT_LON = 2.1;
const DEFAULT_LAT = 47;

export default {
  setup() {
    const form = inject("form");
    return { form };
  },
  data() {
    return {
      map: null,
      marker: null,
    };
  },
  computed: {
    lat() { return this.form.lat; }
  },
  mounted() {
    const latLng = [DEFAULT_LAT, DEFAULT_LON];
    this.map = window.L.map("map-locator").setView(latLng, DEFAULT_ZOOM);
    window.L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      maxZoom: 19,
      attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(this.map);

    this.marker = window.L.marker(
      latLng,
      {
        draggable: true,
        icon: L.icon({
          iconUrl: '/icons/marker.svg',
          iconSize:     [38, 51], 
          iconAnchor:   [19, 51]
        }),
      }
    ).addTo(this.map)
      .on("dragend", () => {
        const latlng = this.marker.getLatLng();
        this.form.lon = latlng.lng;
        this.form.lat = latlng.lat;
      });
  },
  watch: {
    lat: function (newVal, oldVal) {
      if (newVal !== oldVal && newVal) {
        const latlng = [this.form.lat, this.form.lon];
        const zoom = this.map.getZoom();
        this.map.setView(latlng, zoom < 14 ? 14 : zoom);
        this.marker.setLatLng(latlng);
      }
    },
  },
}
</script>

<style scoped>
#map-locator {height: 350px}
</style>