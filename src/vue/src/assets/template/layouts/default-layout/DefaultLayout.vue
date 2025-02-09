<template>
  <!--begin::App-->
  <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
      <KTHeader :data="getUser" />
      <!--begin::Wrapper-->
      <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
        <KTSidebar />
        <!--begin::Main-->
        <div
          class="app-main flex-column flex-row-fluid bg-secondary"
          id="kt_app_main"
        >
          <!--begin::Content wrapper-->
          <div class="d-flex flex-column flex-column-fluid">
            <KTToolbar />
            <div class="app-content flex-column-fluid" id="kt_app_content">
              <KTContent></KTContent>
            </div>
          </div>
          <!--end::Content wrapper-->
          <KTFooter />
        </div>
        <!--end:::Main-->
      </div>
      <!--end::Wrapper-->
    </div>
    <!--end::Page-->
  </div>
  <!--end::App-->

  <KTDrawers :data="getUser"/>
  <KTScrollTop />
  <KTModals :data="getUser" />
  <KTCustomize />
</template>

<script lang="ts">
import {
  defineComponent,
  nextTick,
  onBeforeMount,
  onMounted,
  watch,
  computed,
} from "vue";
import KTHeader from "@/assets/template/layouts/default-layout/components/header/Header.vue";
import KTSidebar from "@/assets/template/layouts/default-layout/components/sidebar/Sidebar.vue";
import KTContent from "@/assets/template/layouts/default-layout/components/content/Content.vue";
import KTToolbar from "@/assets/template/layouts/default-layout/components/toolbar/Toolbar.vue";
import KTFooter from "@/assets/template/layouts/default-layout/components/footer/Footer.vue";
import KTDrawers from "@/assets/template/layouts/default-layout/components/drawers/Drawers.vue";
import KTModals from "@/assets/template/layouts/default-layout/components/modal/global/Modals.vue";
import KTScrollTop from "@/assets/template/layouts/default-layout/components/extras/ScrollTop.vue";
// import KTCustomize from "@/layouts/default-layout/components/extras/Customize.vue";
import { useRoute } from "vue-router";
import { reinitializeComponents } from "@/assets/template/core/plugins/keenthemes";
import LayoutService from "@/assets/template/core/services/LayoutService";
import { useAuthStore } from "@/assets/template/stores/auth";
export default defineComponent({
  name: "default-layout",
  components: {
    KTHeader,
    KTSidebar,
    KTContent,
    KTToolbar,
    KTFooter,
    KTDrawers,
    KTScrollTop,
    KTModals,
    // KTCustomize,
  },
  setup() {
    const route = useRoute();

    const authStore = useAuthStore();
    const getUser = computed(() => {
      return authStore?.userLoggedData;
    });

    onBeforeMount(() => {
      LayoutService.init();
    });

    onMounted(() => {
      nextTick(() => {
        reinitializeComponents();
      });
    });

    watch(
      () => route.path,
      () => {
        nextTick(() => {
          reinitializeComponents();
        });
      }
    );

    return {
      getUser,
    };
  },
});
</script>
