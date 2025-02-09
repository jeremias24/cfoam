<template>
  <!-- begin::Scrolltop -->
  <div
    v-if="scrolltopDisplay"
    id="kt_scrolltop"
    ref="kt_scrolltop"
    class="scrolltop"
    data-kt-scrolltop="true"
  >
    <KTIcon icon-name="arrow-up" icon-class="fs-2x" />
  </div>
  <!-- end::Scrolltop -->
</template>

<script lang="ts">
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { defineComponent, onMounted } from "vue";
import { ScrollTopComponent } from "@/assets/template/ts/components/_ScrollTopComponent";
import { scrolltopDisplay } from "@/assets/template/layouts/default-layout/utils/helper";

export default defineComponent({
  name: "kt-scroll-top",
  components: {},
  setup() {
    onMounted(() => {
      ScrollTopComponent.reinitialization();
    });

    return {
      scrolltopDisplay,
      getAssetPath,
    };
  },
});
</script>