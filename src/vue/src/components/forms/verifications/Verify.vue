import { reference } from '@popperjs/core';
<template>
  <!--begin::Form-->
  <Form id="kt_form_confirm" class="form w-100">
    <!--begin::Wrapper-->
    <div class="d-flex flex-column pb-5">
      <!--begin::Title-->
      <h1 class="mb-3 text-gray-900 text-center">
        <span class="fw-bolder">{{ getReferenceId }}</span> {{ status }}.
      </h1>
      <!--end::Title-->
    </div>
    <!--end::Wrapper-->

    <p
      v-show="status == 'Verified'"
      class="fs-7 mb-6"
      style="text-align: center; text-justify: inter-word"
    >
      Thank you for verifying the request.
    </p>
  </Form>
  <!--end::Form-->
</template>

<script lang="ts">
import { defineComponent, computed, ref } from "vue";
import ApiService from "@/assets/template/core/services/ApiService";
import MixinService from "@/assets/template/core/services/MixinService";

export default defineComponent({
  name: "form-verified",
  props: {
    data: { type: Object, required: true },
  },

  setup(props) {
    const status = ref<string>("");

    const getReferenceId = computed(() => {
      if (
        props.data?.referenceId != undefined ||
        props.data?.userRequestId != undefined
      )
        verifyEmail(props.data);

      return props.data?.referenceId;
    });

    const verifyEmail = (data) => {
      ApiService.put(
        `verify/${MixinService.encrypt(JSON.stringify({ action: "verify" }))}}`,
        MixinService.serialize({
          userRequestId: data?.userRequestId,
          referenceId: data?.referenceId,
          statusId: 42,
          statusCode: "VRFD",
        })
      )
        .then(({ data }) => {
          status.value = data.status;
        })
        .catch(({ error }) => {
          console.log(error);
        });
    };

    return {
      getReferenceId,
      status,
    };
  },
});
</script>
