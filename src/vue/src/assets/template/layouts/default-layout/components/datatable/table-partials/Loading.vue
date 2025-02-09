<template>
  <div class="overlay-layer card-rounded bg-dark bg-opacity-5 pt-15">
    <div class="card flex flex-row">
      <div class="spinner-border text-primary m-2" role="status"></div>
      <span class="fs-7">Table Loading...</span>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "kt-loading",
  components: {},
});
</script>
