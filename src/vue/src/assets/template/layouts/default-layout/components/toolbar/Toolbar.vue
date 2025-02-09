<template>
  <!--begin::Toolbar-->
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-4">
    <!--begin::Toolbar container-->
    <div
      id="kt_app_toolbar_container"
      class="app-container d-flex flex-stack"
      :class="{
        'container-fluid': toolbarWidthFluid,
        'container-xxl': !toolbarWidthFluid,
      }"
    >
      <!-- <KTPageTitle /> -->
      <!--begin::Actions-->
      <div id="kt_toolbar_actions" class="d-flex align-items-center gap-2 gap-lg-3"></div>
      <!--end::Actions-->
    </div>
    <!--end::Toolbar container-->
  </div>
  <!--end::Toolbar-->
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { toolbarWidthFluid } from "@/assets/template/layouts/default-layout/utils/helper";
import KTPageTitle from "@/assets/template/layouts/default-layout/components/toolbar/PageTitle.vue";

export default defineComponent({
  name: "layout-toolbar",
  components: {
    KTPageTitle,
  },
  setup() {
    return {
      toolbarWidthFluid,
    };
  },
});
</script>