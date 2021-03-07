<template>
  <div class="select-card">
    <option-card
      v-for="option in options"
      :option="option"
      :key="option.label"
      :is-selected="value.indexOf(option.label) > -1"
      @check="check"
      @uncheck="val => $emit('change', value.filter(item => item !== val))"
    />
  </div>
</template>

<script>
import OptionCard from "./OptionCard.vue";
export default {
  components: { OptionCard },
  props: ["options", "value", "max"],
  methods: {
    check: function (val) {
      const max = parseInt(this.max);

      if (max && max === this.value.length) {
        if (max === 1) {
          this.$emit('change', [val]);
        } else {
          window.alert(`Vous pouvez sélectionner au maximum ${max} éléments`);
        }
      } else {
        this.$emit('change', this.value.concat([val]));
      }
    }
  }
}
</script>