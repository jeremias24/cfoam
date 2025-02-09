<template>
  <!-- <div></div> -->
  <KTLoginAsUserModal
    :id="initModalId"
    ref="initModalComponentRef"
    :title="initModalTitle"
    :show-close-button="initModalCloseButton"
    :width="650"
    :ribbon="initModalRibbon"
    :ribbon-label="initModalRibbonLabel"
    :ribbon-color="initModalRibbonColor"
    :key="initModalId"
    :data="userLogged"
  />
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import KTLoginAsUserModal from "@/components/modals/LoginAsUserModal.vue";

export default defineComponent({
  name: "global-modals",
  components: {
    KTLoginAsUserModal,
  },
  props: {
    data: { type: Object, required: true, default: {} },
  },
  setup(props) {
    const initModalCloseButton = ref<boolean>(true);
    const initModalRibbon = ref<boolean>(false);
    const initModalRibbonLabel = ref<string>("");
    const initModalRibbonColor = ref<string>("");
    const initModalComponentRef = ref<null | HTMLElement>(null);
    const initModalId = ref<string>("kt_modal_login_as_user");
    const initModalTitle = ref<string>("Login as User");
    const userLogged = ref(props.data);

    return {
      initModalComponentRef,
      initModalId,
      initModalTitle,
      initModalCloseButton,
      initModalRibbon,
      initModalRibbonLabel,
      initModalRibbonColor,
      userLogged,
    };
  },
});
</script>