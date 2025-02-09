<template>
  <div class="card">
    <div class="card-header border-0 py-0">
      <!--begin::Card title-->
      <div class="card-title">
        <div class="flex-column">
          <h2 class="fs-5">{{ initPageTitle }}</h2>
          <div class="fs-7 fw-semibold text-muted">{{ initPageSubtitle }}</div>
        </div>
      </div>
      <!--end::Card title-->
    </div>
    <div class="card-body py-0 mb-10">
      <!--begin::Col-->
      <div class="col-xl-12 mb-xl-10">
        <div class="row gx-6 gy-3">
          <div
            class="col-xl-3"
            v-for="(service, index) in initServiceData"  
            :key="index"
          >  
            <ServiceWidget
              widget-classes="card-xl-stretch mb-xl-10"
              :widget-color="getColor(index)"
              chart-height="100"
              :service-id="service.id"
              :service-name="service.name"
              icon-name="basket"
              icon-color="white"
            /> 
          </div>
        </div>
      </div>
      <!--end::Col-->
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, Component, computed } from "vue";
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { MenuComponent } from "@/assets/template/ts/components";
import type { IService } from "@/types/services";
import User from "@/components/forms/User.vue";
import ApiService from "@/assets/template/core/services/ApiService";
import ServiceWidget from "@/assets/template/layouts/default-layout/components/widgets/mixed/ServiceWidget.vue";
import ModalForm from "@/assets/template/layouts/default-layout/components/modal/ModalForm.vue";
import { useAuthStore } from "@/assets/template/stores/auth";
import MixinService from "@/assets/template/core/services/MixinService";

export default defineComponent({
  name: "dashboard",
  components: {
    ModalForm,
    User,
    ServiceWidget
  },
  setup() {
    /** Default Setup */
    let initData: Array<IService> = [];
    const authStore = useAuthStore();
    const initLoading = ref<boolean>(true);
    const initServiceData = ref<Array<IService>>([
      {
        id: 1, name: "Business One Stop Service"
      },
      {
        id: 2, name: "General Service"
      },
      {
        id: 3, name: "Housing Service"
      },
      {
        id: 4, name: "Justice Service"
      },
      {
        id: 5, name: "Community Service"
      },
      {
        id: 6, name: "Information Technology Service"
      },
      {
        id: 7, name: "Benefits Service"
      },
      {
        id: 8, name: "Police Clearance"
      },
    ]);
    const initSearch = ref<string>("");
    const initToolbarNav = ref<boolean>(false);
    const initModalCloseButton = ref<boolean>(true);
    const initModalRibbon = ref<boolean>(false);
    const initModalRibbonLabel = ref<string>("");
    const initModalRibbonColor = ref<string>("");
    const selectedDatasId = ref<Array<number>>([]);
    
    /** Dynamic Setup */
    const initPageTitle = ref<string>("Dashboard");
    const initPageSubtitle = ref<string>("Services");
    const initModalComponentRef = ref<null | HTMLElement>(null);
    const initModalId = ref<string>("kt_modal_service");
    const initModalTitle = ref<string>("New Service");
    const initModal = ref<Component>(User);

    const getUser = computed(() => {
      return authStore?.getUser();
    });

    const colors = [
      "#CBF0F4", // Baby blue
      "#B0E0E6", // Powder blue
      "#c9e6fc", // Periwinkle blue
      "#87CEEB", // Sky blue
      "#B3E1F2",
      "#B3EBF2"
    ] as const;
    
    onMounted(() => {
      initToolbarNav.value = true;
    });


    const getColor = (index: number): string => {
      return colors[index % colors.length];
    };

    return {
      initPageTitle,
      initPageSubtitle,
      initModal,
      initModalComponentRef,
      initModalId,
      initModalTitle,
      initModalCloseButton,
      initModalRibbon,
      initModalRibbonLabel,
      initModalRibbonColor,
      initServiceData,
      initToolbarNav,
      initLoading,
      selectedDatasId,
      getAssetPath,
      getUser,
      getColor
    };
  },
});
</script>