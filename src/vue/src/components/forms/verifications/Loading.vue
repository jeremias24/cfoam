import { reference } from '@popperjs/core';
<template>
    <!--begin::Form-->
    <Form id="kt_form_completed" class="form w-100">
        <!--begin::Wrapper-->
        <div class="d-flex flex-column pb-6" data-kt-indicator="on">
            <!--begin::Title-->
            <h1 class="mb-3 text-gray-900 text-center indicator-progress">
                <span class="spinner-border spinner-border-lg align-middle me-3 mb-2"></span>
                <span class="fw-bolder">{{ getReferenceId }}</span> Verification Loading
            </h1>
            <!--end::Title-->
            <p class="fs-7 mb-6" style="text-align: center; text-justify: inter-word;">
                Please wait a moment.
            </p>
        </div>
        <!--end::Wrapper-->
    </Form>
    <!--end::Form-->
</template>

<script lang="ts">
import { defineComponent, computed } from "vue";

export default defineComponent({
    name: "form-approvals-loading",
    props: {
        data: { type: Object, required: true },
    },
    setup(props) {
        const getReferenceId = computed(() => {
            return props.data?.referenceId;
        });

        return {
            getReferenceId,
        };
    },
});
</script>
