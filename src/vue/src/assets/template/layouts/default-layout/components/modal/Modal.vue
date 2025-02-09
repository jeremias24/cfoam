<template>
  <div
    class="modal fade"
    id="kt_modal_add_memorandum"
    :ref="initModalRef()"
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
        <div class="modal-header ribbon ribbon-end ribbon-clip" id="kt_modal_add_customer_header">
          <!--begin::Modal title-->
          <h2 class="fw-bold">{{ initModalTitle }}</h2>
          <!--end::Modal title-->

          <!--begin::Close-->
          <div
            v-show="initModalCloseButton"
            id="kt_modal_add_memorandum_close"
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
        
        <!--begin::Modal body-->
        <div class="modal-body py-10 px-lg-17">
          <slot name="modal_content"></slot>
        </div>
        <!--end::Modal body-->

        <!--begin::Modal footer-->
        <div class="modal-footer flex-center">
          <!--begin::Button-->
          <button
            type="reset"
            id="kt_modal_add_customer_cancel"
            class="btn btn-light me-3"
          >
            Discard
          </button>
          <!--end::Button-->

          <!--begin::Button-->
          <button 
            v-for="(button, i) in initModalFooterButton"
            :key="i"
            :type="button.type"
            :data-kt-indicator="button.loading ? 'on' : null"
            :class="`btn btn-sm me-3 hover-scale ${button.buttonClass}`"
            @click="button?.onClickFunc"
          >
            <span v-if="!button.loading" class="indicator-label">
              <KTIcon v-if="button?.buttonIcon" :icon-name="button.buttonIcon" icon-class="fs-7 me-2" />
              {{ button.buttonLabel }}
            </span>
            <span v-if="button.loading" class="indicator-progress">
              <span class="spinner-border spinner-border-sm align-middle me-2"></span>
              {{ button?.loadingLabel }}
            </span>
          </button>
          <!--end::Button-->
        </div>
        <!--end::Modal footer-->
      </div>
      <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
  </div>
</template>

<script lang="ts">
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { defineComponent, ref } from "vue";
// import { hideModal } from "@/assets/template/core/helpers/modal";
// import Swal from "sweetalert2/dist/sweetalert2.js";

export default defineComponent({
  name: "layout-modal",
  props: {
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
    const formRef = ref<null | HTMLFormElement>(null);
    const ModalRef = ref<null | HTMLElement>(null);
    const loading = ref<boolean>(false);

    const initModalTitle = ref<string>(props.title);
    const initModalWidth = ref<number>(props.width);
    const initModalCloseButton = ref<boolean>(props.showCloseButton);
    const initModalRibbon = ref<boolean>(props.ribbon);
    const initModalRibbonLabel = ref<string>(props.ribbonLabel);
    const initModalRibbonColor = ref<string>(props.ribbonColor);
    const initModalBackdrop = ref<string>(props.modalBackdrop);
    const initModalCloseKeypress= ref<boolean>(props.closeKeypress);

    const initModalFooterButton = ref([
      {
        type: "submit",
        loading: false,
        loadingLabel: "Please wait...",
        buttonLabel: "Submit",
        buttonClass: "btn-primary",
        buttonIcon: "plus",
      },
      {
        type: "reset",
        loading: false,
        buttonLabel: "Discard",
        buttonClass: "btn-light-danger",
        onClickFunc: () => {
          alert();
        },
      },
    ]);

    const initModalRef = () => {
      return ""
    }

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

    return {
      // formData,
      // rules,
      // submit,
      initModalTitle,
      initModalWidth,
      initModalBackdrop,
      initModalCloseKeypress,
      initModalCloseButton,
      initModalRibbon,
      initModalRibbonLabel,
      initModalRibbonColor,
      initModalFooterButton,
      
      initModalRef,

      formRef,
      loading,
      ModalRef,
      getAssetPath,
    };
  },
});
</script>
