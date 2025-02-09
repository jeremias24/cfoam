<template>
  <!--begin::Card-->
  <div class="card card-flush flex-row-fluid p-6 pb-5 mw-100">
    <!--begin::Body-->
    <div class="card-body text-center">
      <!--begin::Food img-->
      <img
        src=""
        class="rounded-3 mb-4 w-150px h-150px w-xxl-200px h-xxl-200px"
        alt=""
      />
      <!--end::Food img-->
      <!--begin::Info-->
      <div class="mb-2">
        <!--begin::Title-->
        <div class="text-center">
          <span
            class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1"
            >{{ name }}</span
          >
          <span class="text-gray-500 fw-semibold d-block fs-6 mt-n1">{{
            description
          }}</span>
        </div>
        <!--end::Title-->
      </div>
      <!--end::Info-->
      <!--begin::Total-->
      <span class="text-success text-end fw-bold fs-1">$16.50$</span>
      <!--end::Total-->
    </div>
    <!--end::Body-->
  </div>
  <!--end::Card-->
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "products-pos-card",
  components: {},
  props: {
    name: String,
    description: String,
  },
  setup(props) {
    return {};
  },
});
</script>
  

