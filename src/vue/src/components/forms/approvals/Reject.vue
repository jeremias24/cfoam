
import { reference } from '@popperjs/core';
<template>
  <!--begin::Form-->
  <Form id="kt_form_reject" class="form w-100">
    <!--begin::Wrapper-->
    <div class="d-flex flex-column pb-5">
      <!--begin::Title-->
      <h1 class="mb-3 text-gray-900 text-center">
        <span class="fw-bolder">{{ getReferenceId }}</span> for rejection.
      </h1>
      <!--end::Title-->
    </div>
    <!--end::Wrapper-->

    <!--begin::Input group-->
    <div class="d-flex flex-column mb-3">
      <label class="required fs-7 fw-semibold mb-2">Reason</label>
      <Field
        as="textarea"
        name="reason"
        v-model="initFormData.reason"
        class="form-control form-control-solid form-sm border border-gray-300 fs-7 min-h-40px"
        placeholder="Reason of Rejection"
        rows="10"
      ></Field>
      <ErrorMessage
        name="reason"
        :data="initFormData.reason"
        :error="initValidationError"
      ></ErrorMessage>
    </div>
    <!--end::Input group-->

    <!--begin::Wrapper-->
    <div class="d-flex flex-column flex-center align-end pt-5">
      <button
        type="button"
        class="btn btn-sm fw-bold hover-scale btn-danger"
        @click="rejectApproval"
      >
        <span class="indicator-label fs-7"> Reject </span>
      </button>
    </div>
    <!--end::Wrapper-->
  </Form>
  <!--end::Form-->
</template>

<script lang="ts">
import { defineComponent, ref, computed } from "vue";
import { useRouter } from "vue-router";
import { Form } from "vee-validate";
import { Field } from "vee-validate";
import ApiService from "@/assets/template/core/services/ApiService";
import MixinService from "@/assets/template/core/services/MixinService";

import ErrorMessage from "@/assets/template/layouts/default-layout/components/extras/controls/ErrorMessage.vue";
import Notification from "@/components/forms/Approvals/Notification.vue";

export default defineComponent({
  name: "form-approvals-reject",
  components: {
    Form,
    Field,
    ErrorMessage,
    Notification,
  },
  props: {
    data: { type: Object, required: true },
  },
  emits: ["updateForm"],
  setup(props, { emit }) {
    const router = useRouter();
    const initValidationError = ref({
      valid: false,
      id: "",
      error: "",
    });
    const initFormRules = ref({
      reason: [
        {
          required: true,
          message: "Reason field is required.",
        },
      ],
    });
    const initFormData = ref({
      reason: null,
    });

    const getReferenceId = computed(() => {
      return props.data?.referenceId;
    });

    const rejectApproval = () => {
      initValidationError.value = MixinService.validateForm(
        initFormData.value,
        initFormRules.value
      );

      if (initValidationError.value.valid) {
        MixinService.showConfirmAlert(
          "Are you sure you want to proceed to reject this approval?",
          "Reject",
          () => {
            ApiService.put(
              `approvals/${MixinService.encrypt(
                JSON.stringify({ action: "reject" })
              )}}`,
              MixinService.serialize({
                id: props.data?.approvalId,
                hierarchy: props.data?.hierarchy,
                userRequestId: props.data?.userRequestId,
                referenceId: props.data?.referenceId,
                approverId: props.data?.approverId,
                statusCode: "RJCTD",
                reason: initFormData.value.reason,
              })
            )
              .then(({ data }) => {
                console.log(data);

                if (data?.status == "Passed") {
                  router.push(
                    `/approvals/notification/${MixinService.encrypt(
                      JSON.stringify({
                        action: data?.response,
                        approvalId: props.data?.approvalId,
                        hierarchy: props.data?.hierarchy,
                        userRequestId: props.data?.userRequestId,
                        referenceId: props.data?.referenceId,
                        approverId: props.data?.approverId,
                      })
                    )}`
                  );
                  emit("updateForm", Notification);
                }
              })
              .catch(({ error }) => {
                console.log(error);
              });
          },
          "danger"
        );
      }
    };

    return {
      initFormData,
      initValidationError,
      getReferenceId,
      rejectApproval,
    };
  },
});
</script>
