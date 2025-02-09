import { createApp } from "vue";
import { createPinia } from "pinia";
import { Tooltip } from "bootstrap";

import App from "./App.vue";
import router from "./router";
import VueCookie from 'vue-cookie';

// import "./style.css"

import ElementPlus from "element-plus";
import Sticky from "vue3-sticky-directive";
import i18n from "@/assets/template/core/plugins/i18n";
import ApiService from "@/assets/template/core/services/ApiService";
import { initApexCharts } from "@/assets/template/core/plugins/apexcharts";
import { initInlineSvg } from "@/assets/template/core/plugins/inline-svg";
import { initVeeValidate } from "@/assets/template/core/plugins/vee-validate";
import { initKtIcon } from "@/assets/template/core/plugins/keenthemes";

import "@/assets/template/core/plugins/prismjs";

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(ElementPlus);

ApiService.init(app);
initApexCharts(app);
initInlineSvg(app);
initKtIcon(app);
initVeeValidate();

app.use(i18n);

app.directive("tooltip", (el) => {
  new Tooltip(el);
});

app.mount("#app");
