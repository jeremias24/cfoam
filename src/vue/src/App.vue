<template>
  <RouterView />
</template>

<script lang="ts">
import { defineComponent, nextTick, onBeforeMount, onMounted } from "vue";
import { RouterView } from "vue-router";
import { useConfigStore } from "@/assets/template/stores/config";
import { useThemeStore } from "@/assets/template/stores/theme";
import { useBodyStore } from "@/assets/template/stores/body";
import { themeConfigValue } from "@/assets/template/layouts/default-layout/utils/helper";
import { initializeComponents } from "@/assets/template/core/plugins/keenthemes";

export default defineComponent({
  name: "app",
  components: {
    RouterView,
  },
  setup() {
    const configStore = useConfigStore();
    const themeStore = useThemeStore();
    const bodyStore = useBodyStore()

    onBeforeMount(() => {
      /**
       * Overrides the layout config using saved data from localStorage
       * remove this to use static config (@/layouts/default-layout/config/DefaultLayoutConfig.ts)
       */
      configStore.overrideLayoutConfig();

      /**
       *  Sets a mode from configuration
       */
      themeStore.setThemeMode(themeConfigValue.value);
    })

    onMounted(() => {
      nextTick(() => {
        initializeComponents();

        bodyStore.removeBodyClassName("page-loading");
      })
    })
  }
});
</script>

<style lang="scss">
@import "bootstrap-icons/font/bootstrap-icons.css";
@import "apexcharts/dist/apexcharts.css";
@import "quill/dist/quill.snow.css";
@import "animate.css";
@import "sweetalert2/dist/sweetalert2.css";
@import "nouislider/dist/nouislider.css";
@import "@fortawesome/fontawesome-free/css/all.min.css";
@import "socicon/css/socicon.css";
@import "line-awesome/dist/line-awesome/css/line-awesome.css";
@import "dropzone/dist/dropzone.css";
@import "@vueform/multiselect/themes/default.css";
@import "prism-themes/themes/prism-shades-of-purple.css";
@import "element-plus/dist/index.css";
@import "select2-component/dist/select2.min.css";
@import "@vuepic/vue-datepicker/dist/main.css";

@import "assets/template/css/keenicons/duotone/style.css";
@import "assets/template/css/keenicons/outline/style.css";
@import "assets/template/css/keenicons/solid/style.css";
@import "assets/template/sass/element-ui.dark";
@import "assets/template/sass/plugins";
@import "assets/template/sass/style";
@import "assets/template/css/override.css";

#app {
  display: contents;
}
</style>