import { createI18n } from "vue-i18n";
import language from "@/config/lang";

const messages = language;

const i18n = createI18n({
  legacy: false,
  locale: "en",
  globalInjection: true,
  messages,
});

export default i18n;
