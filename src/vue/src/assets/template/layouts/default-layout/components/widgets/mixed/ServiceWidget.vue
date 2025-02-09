<template>
  <div
    :class="widgetClasses"
    class="card theme-dark-bg-body min-h-150px"
    :style="`background-color: ${widgetColor}`"
  >
    <div class="card-body d-flex flex-column">
      <div class="d-flex flex-column flex-grow-1 me-3">
        <a
          href="javascript:;"
          class="text-gray-900 text-hover-primary fw-bold fs-3"
          >{{ serviceName }}</a
        >
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref } from "vue";
import { useAuthStore } from "@/assets/template/stores/auth";
import MixinService from "@/assets/template/core/services/MixinService";
import ApiService from "@/assets/template/core/services/ApiService";

export default defineComponent({
  name: "service-widget",
  props: {
    widgetClasses: String,
    widgetColor: String,
    id: Number,
    serviceName: String
  },
  setup(props) {
    const authStore = useAuthStore();
    const serviceName = ref(props.serviceName);
    const initDefaultFormData = {
    };

    const initFormData = ref(Object.assign({}, initDefaultFormData));

    const getUser = computed(() => {
      return authStore?.getUser();
    });

    return {
      getUser,
      serviceName
    };
  },
});
</script>
