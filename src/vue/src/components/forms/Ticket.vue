<template>
    <!--begin::Form-->
    <VForm
      id="kt_form_ticket"
      class="form"
    >
      <GeneralDetailsCard></GeneralDetailsCard>
      <ConcernDetailsCard></ConcernDetailsCard>
    </VForm>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { Form as VForm } from "vee-validate";
import GeneralDetailsCard from "@/components/cards/create-ticket/GeneralDetailsCard.vue";
import ConcernDetailsCard from "@/components/cards/create-ticket/ConcernDetailsCard.vue";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default defineComponent({
  name: "form-ticket",
  components: {
    VForm,
    GeneralDetailsCard,
    ConcernDetailsCard,
  },
  setup() {
    const formRef = ref<null | HTMLFormElement>(null);
    const loading = ref<boolean>(true);

    const initFormData = ref({
      name: "",
    });
    const initFormRules = ref({
      name: [
        {
          required: true,
          message: "Customer name is required",
          trigger: "change",
        },
      ],
    });
    const initModalFooterButton = ref([
      {
        type: "button",
        loading: false,
        loadingLabel: "Please wait...",
        buttonLabel: "Submit",
        buttonClass: "btn-primary",
        buttonIcon: "plus",
        onClickFunc: () => {
          Swal.fire({
            text: "Form has been successfully submitted!",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            heightAuto: false,
            customClass: {
              confirmButton: "btn btn-sm btn-primary",
            },
          });
        },
      },
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

    return {
      initFormData,
      initFormRules,
      initModalFooterButton,
      formRef,
      loading,
    };
  },
});
</script>
