<template>
  <!--begin::Authentication Layout -->
  <div
    class="d-flex flex-column flex-lg-row flex-column-fluid bgi-size-cover bgi-position-center order-1 order-lg-2"
    :style="`background-image: url('${getAssetPath(
      'media/backgrounds/background.png'
    )}')`"
  >
    <!--begin::Body-->
    <div
      class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1"
    >
      <!--begin::Form-->
      <div class="d-flex flex-center flex-column flex-lg-row-fluid">
        <!--begin::Wrapper-->
        <div class="card" style="opacity: 0.95" data-bs-theme="dark">
          <div class="card-header" style="border-bottom: 5px solid #7CFC00">
            <!--begin::Content-->
            <div
              class="d-flex flex-column flex-center py-lg-5  px-md-15 w-100"
            >
              <!--begin::Logo-->
              <router-link to="/" class="mb-0 p-0">
                <img
                  alt="Logo"
                  :src="getAssetPath('media/logos/logo.png')"
                  class="h-100px"
                />
              </router-link>
              <!--end::Logo-->
            </div>
            <!--end::Content-->
          </div>
          <div class="w-lg-400px px-8 pb-8 pt-1">
            <router-view></router-view>
          </div>
        </div>
        <!--end::Wrapper-->
      </div>
      <!--end::Form-->
    </div>
    <!--end::Body-->

    <!--begin::Aside-->
    <div
      class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
    ></div>
    <!--end::Aside-->

    <!-- Version number -->
    <div class="version-number">{{ version }}</div>
  </div>
  <!--end::Authentication Layout -->
</template>

<script lang="ts">
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { defineComponent, onMounted } from "vue";
import LayoutService from "@/assets/template/core/services/LayoutService";
import { useBodyStore } from "@/assets/template/stores/body";

export default defineComponent({
  name: "auth-layout",
  components: {},
  setup() {
    const store = useBodyStore();

    onMounted(() => {
      LayoutService.emptyElementClassesAndAttributes(document.body);

      store.addBodyClassname("app-blank");
      store.addBodyClassname("bg-body");
    });

    return {
      getAssetPath,
    };
  },
});
</script>
