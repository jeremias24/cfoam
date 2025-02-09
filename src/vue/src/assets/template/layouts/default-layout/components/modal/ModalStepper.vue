<template>
  <div
    class="modal fade"
    :id="initModalId"
    ref="initModalRef"
    :data-bs-backdrop="initModalBackdrop"
    :data-bs-keyboard="initModalCloseKeypress"
    tabindex="-1"
    aria-hidden="true"
  >
    <!--begin::Modal dialog-->
    <div :class="`modal-dialog modal-dialog-centered mw-${width}px`">
      <!--begin::Modal content-->
      <div class="modal-content">
        <!--begin::Modal header-->
        <div class="modal-header ribbon ribbon-end ribbon-clip" :id="`${initModalId}_header`">
          <!--begin::Modal title-->
          <h2 class="fw-bold">{{ initModalTitle }}</h2>
          <!--end::Modal title-->

          <!--begin::Close-->
          <div
            v-show="initModalCloseButton"
            :id="`${initModalId}_close`"
            data-bs-dismiss="modal"
            class="btn btn-icon btn-sm btn-active-icon-primary"
          >
            <KTIcon icon-name="cross" icon-class="fs-1" />
          </div>
          <!--end::Close-->

          <!--begin::Close-->
          <div v-show="initModalRibbon" class="ribbon-label">
            {{ initModalRibbonLabel }}
            <span :class="`ribbon-inner bg-${initModalRibbonColor}`"></span>
          </div>
          <!--end::Close-->

        </div>
        <!--end::Modal header-->

        <slot name="modal_content"></slot>
      </div>
      <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
  </div>
</template>

<script lang="ts">
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { defineComponent, defineExpose, ref } from "vue";
// import { hideModal } from "@/assets/template/core/helpers/modal";
// import Swal from "sweetalert2/dist/sweetalert2.js";

export default defineComponent({
  name: "layout-modal",
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
    const loading = ref<boolean>(false);
    // const initFormRef = ref<null | HTMLFormElement>(null);
    const initModalRef = ref<null | HTMLElement>();
    const initModalId = ref<string>(props.id);
    const initModalTitle = ref<string>(props.title);
    const initModalWidth = ref<number>(props.width);
    const initModalCloseButton = ref<boolean>(props.showCloseButton);
    const initModalRibbon = ref<boolean>(props.ribbon);
    const initModalRibbonLabel = ref<string>(props.ribbonLabel);
    const initModalRibbonColor = ref<string>(props.ribbonColor);
    const initModalBackdrop = ref<string>(props.modalBackdrop);
    const initModalCloseKeypress= ref<boolean>(props.closeKeypress);

    // const formData = ref({
    //   name: "Sean Bean",
    //   email: "sean@dellito.com",
    //   description: "",
    //   addressLine: "101, Collins Street",
    //   addressLine2: "",
    //   town: "Melbourne",
    //   state: "Victoria",
    //   postCode: "3000",
    //   country: "US",
    // });

    // const rules = ref({
    //   name: [
    //     {
    //       required: true,
    //       message: "Customer name is required",
    //       trigger: "change",
    //     },
    //   ],
    //   email: [
    //     {
    //       required: true,
    //       message: "Customer email is required",
    //       trigger: "change",
    //     },
    //   ],
    //   addressLine: [
    //     {
    //       required: true,
    //       message: "Address 1 is required",
    //       trigger: "change",
    //     },
    //   ],
    //   town: [
    //     {
    //       required: true,
    //       message: "Town is required",
    //       trigger: "change",
    //     },
    //   ],
    //   state: [
    //     {
    //       required: true,
    //       message: "State is required",
    //       trigger: "change",
    //     },
    //   ],
    //   postCode: [
    //     {
    //       required: true,
    //       message: "Post code is required",
    //       trigger: "change",
    //     },
    //   ],
    // });

    // const submit = () => {
    //   if (!formRef.value) {
    //     return;
    //   }

    //   formRef.value.validate((valid: boolean) => {
    //     if (valid) {
    //       loading.value = true;

    //       setTimeout(() => {
    //         loading.value = false;

    //         Swal.fire({
    //           text: "Form has been successfully submitted!",
    //           icon: "success",
    //           buttonsStyling: false,
    //           confirmButtonText: "Ok, got it!",
    //           heightAuto: false,
    //           customClass: {
    //             confirmButton: "btn btn-primary",
    //           },
    //         }).then(() => {
    //           hideModal(addMemorandumModalRef.value);
    //         });
    //       }, 2000);
    //     } else {
    //       Swal.fire({
    //         text: "Sorry, looks like there are some errors detected, please try again.",
    //         icon: "error",
    //         buttonsStyling: false,
    //         confirmButtonText: "Ok, got it!",
    //         heightAuto: false,
    //         customClass: {
    //           confirmButton: "btn btn-primary",
    //         },
    //       });
    //       return false;
    //     }
    //   });
    // };

    defineExpose({
      initModalRef,
    });

    return {
      // formData,
      // rules,
      // submit,
      initModalId,
      initModalTitle,
      initModalWidth,
      initModalBackdrop,
      initModalCloseKeypress,
      initModalCloseButton,
      initModalRibbon,
      initModalRibbonLabel,
      initModalRibbonColor,

      // initFormRef,
      initModalRef,

      
      loading,

      getAssetPath,
    };
  },
});
</script>
