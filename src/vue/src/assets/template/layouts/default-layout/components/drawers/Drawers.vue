<template>
  <KTChatbotDrawer :data="userLogged"/>
  <!-- <KTActivityDrawer />
  <KTToolButtons />
  <KTHelpDrawer /> -->
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import KTChatbotDrawer from "@/assets/template/layouts/default-layout/components/extras/ChatbotDrawer.vue";
import KTActivityDrawer from "@/assets/template/layouts/default-layout/components/drawers/ActivityDrawer.vue";
import KTHelpDrawer from "@/assets/template/layouts/default-layout/components/extras/HelpDrawer.vue";
import KTToolButtons from "@/assets/template/layouts/default-layout/components/extras/ToolButtons.vue";

export default defineComponent({
  name: "global-drawers",
  components: {
    KTChatbotDrawer,
    KTActivityDrawer,
    KTHelpDrawer,
    KTToolButtons,
  },
  props: {
    data: { type: Object, required: true, default: {} },
  },
  setup(props) {
    const userLogged = ref(props.data);
    return {
      userLogged,
    };
  },
});
</script>
