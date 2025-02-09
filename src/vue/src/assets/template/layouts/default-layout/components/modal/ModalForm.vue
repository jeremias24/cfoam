<template>
  <div class="modal fade" :id="initModalId" ref="initModalRef" :data-bs-backdrop="initModalBackdrop"
    :data-bs-keyboard="initModalCloseKeypress" data-bs-focus="false" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div :class="`modal-dialog modal-dialog-centered mw-${width}px`">
      <!--begin::Modal content-->
      <div class="modal-content">
        <!--begin::Modal header-->
        <div class="modal-header ribbon ribbon-end ribbon-clip pb-0" :id="`${initModalId}_header`">
          <!--begin::Modal title-->
          <h2 class="fw-bold">{{ initModalTitle.value }}</h2>
          <!--end::Modal title-->

          <!--begin::Close-->
          <div v-show="initModalCloseButton" :id="`${initModalId}_close`" data-bs-dismiss="modal"
            class="btn btn-icon btn-sm btn-active-icon-primary">
            <KTIcon icon-name="cross" icon-class="fs-1" />
          </div>
          <!--end::Close-->

          <!--begin::Close-->
          <div v-show="initModalRibbon.value" class="ribbon-label fw-bolder fs-2 px-5">
            {{ `${initModalRibbonLabel.value}` }}
            <span :class="`ribbon-inner bg-${initModalRibbonColor.value}`"></span>
          </div>
          <!--end::Close-->

        </div>
        <!--end::Modal header-->

        <slot name="modalContent"></slot>

      </div>
      <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
  </div>
</template>

<script lang="ts">
import { defineComponent, defineExpose, ref, computed } from "vue";

export default defineComponent({
  name: "layout-modal-form",
  props: {
    id: { type: String, required: true },
    ref: { type: HTMLElement, required: false },
    title: { type: String, required: true },
    showCloseButton: { type: Boolean, required: false, default: true },
    modalBackdrop: { type: String, required: false, default: "static" },
    closeKeypress: { type: Boolean, required: false, default: "false" },
    width: { type: Number, required: false, default: 1000 },
    ribbon: { type: Boolean, required: false, default: false },
    ribbonLabel: { type: String, required: false, default: "" },
    ribbonColor: { type: String, required: false, default: "" },
  },
  setup(props) {
    const initLoading = ref<boolean>(false);
    const initModalRef = ref<null | HTMLElement>();
    const initModalId = ref<string>(props.id);
    const initModalTitle = ref<string>(props.title);
    const initModalWidth = ref<number>(props.width);
    const initModalCloseButton = ref<boolean>(props.showCloseButton);
    const initModalRibbon = ref<boolean>(props.ribbon);
    const initModalRibbonLabel = ref<string>(props.ribbonLabel);
    const initModalRibbonColor = ref<string>(props.ribbonColor);
    const initModalBackdrop = ref<string>(props.modalBackdrop);
    const initModalCloseKeypress = ref<boolean>(props.closeKeypress);

    initModalTitle.value = computed(() => {
      return props.title;
    });

    initModalRibbon.value = computed(() => {
      return props.ribbon;
    });

    initModalRibbonLabel.value = computed(() => {
      return props.ribbonLabel;
    });

    initModalRibbonColor.value = computed(() => {
      return props.ribbonColor;
    });

    defineExpose({
      initModalRef,
    });

    return {
      initLoading,
      initModalRef,
      initModalId,
      initModalTitle,
      initModalWidth,
      initModalBackdrop,
      initModalCloseKeypress,
      initModalCloseButton,
      initModalRibbon,
      initModalRibbonLabel,
      initModalRibbonColor,
    };
  },
});
</script>
