import { reference } from '@popperjs/core';
<template>
  <!--begin::Form-->
  <Form id="kt_form_confirm" class="form w-100">
    <!--begin::Wrapper-->
    <div class="d-flex flex-column pb-5">
      <!--begin::Title-->
      <h1 class="mb-3 text-gray-900 text-center">
        <span class="fw-bolder">{{ getReferenceId }}</span> for approval.
      </h1>
      <!--end::Title-->
    </div>
    <!--end::Wrapper-->

    <p class="fs-7 mb-6" style="text-align: center; text-justify: inter-word">
      Are you sure you want to approve this request?
    </p>

    <!--begin::Wrapper-->
    <div class="d-flex flex-column flex-center pt-10">
      <button
        type="button"
        class="btn btn-sm fw-bold hover-scale btn-primary"
        @click="approveApproval"
      >
        <span class="indicator-label fs-7"> Approve </span>
      </button>
    </div>
    <!--end::Wrapper-->
  </Form>
  <!--end::Form-->
</template>

<script lang="ts">
import { defineComponent, computed } from "vue";
import { useRouter } from "vue-router";
import { Form } from "vee-validate";
import ApiService from "@/assets/template/core/services/ApiService";
import MixinService from "@/assets/template/core/services/MixinService";

import Notification from "@/components/forms/Approvals/Notification.vue";

export default defineComponent({
  name: "form-approvals-confirm",
  components: {
    Form,
    Notification,
  },
  props: {
    data: { type: Object, required: true },
  },
  emits: ["updateForm"],
  setup(props, { emit }) {
    const router = useRouter();

    const getReferenceId = computed(() => {
      return props.data?.referenceId;
    });

    const approveApproval = () => {
      MixinService.showConfirmAlert(
        "Are you sure you want to proceed to approve this approval?",
        "Approve",
        () => {
          ApiService.put(
            `approvals/${MixinService.encrypt(
              JSON.stringify({ action: "approve" })
            )}}`,
            MixinService.serialize({
              id: props.data?.approvalId,
              hierarchy: props.data?.hierarchy,
              userRequestId: props.data?.userRequestId,
              referenceId: props.data?.referenceId,
              approverId: props.data?.approverId,
              statusCode: "APRVD",
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
                      referenceId: props.data?.referenceId,
                      hierarchy: props.data?.hierarchy,
                      userRequestId: props.data?.userRequestId,
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
        }
      );
    };

    return {
      getReferenceId,
      approveApproval,
    };
  },
});
</script>
