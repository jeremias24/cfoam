<template>
  <!--begin::Footer-->
  <div v-if="footerDisplay" id="kt_app_footer" class="app-footer">
    <!--begin::Footer container-->
    <div
      class="app-container d-flex flex-column flex-md-row flex-center flex-md-stack py-3"
      :class="{
        'container-fluid': footerWidthFluid,
        'container-xxl': !footerWidthFluid,
      }"
    >
      <!--begin::Copyright-->
      <div class="text-gray-900 order-2 order-md-1">
        <span class="text-muted fw-semibold me-1">{{ currentYear }}©</span>
        <a href="#" target="_blank" class="text-gray-800 text-hover-primary"
          >Car Paint Forensic | CFOAM-AI</a
        >
      </div>
      <!--end::Copyright-->
      <!--begin::Menu-->
      <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
        <li class="menu-item">
          <a href="/" target="_blank" class="menu-link px-2">{{ themeName }}</a>
        </li>
      </ul>
      <!--end::Menu-->
    </div>
    <!--end::Footer container-->
  </div>
  <!--end::Footer-->
</template>

<script lang="ts">
import { computed, defineComponent } from "vue";
import {
  footerDisplay,
  footerWidthFluid,
} from "@/assets/template/layouts/default-layout/utils/helper";
import moment from "moment";

export default defineComponent({
  name: "theme-footer",
  components: {},
  setup() {
    const themeName = computed(() => {
      return (
        import.meta.env.VITE_APP_NAME + " v" + import.meta.env.VITE_APP_VERSION
      );
    });

    const currentYear = computed(() => {
      return moment().year();
    });

    return {
      footerWidthFluid,
      footerDisplay,
      currentYear,
      themeName,
    };
  },
});
</script>