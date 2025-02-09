<template>
    <div class="card card-flush shadow-sm border mx-5 mb-5">
        <div class="card-header">
            <span class="fw-bolder card-title fs-5">Concern Details</span>
        </div>
        <div class="card-body pt-0">

            <!--begin::Input group-->
            <div class="d-flex flex-column mb-3">
                <label class="required fs-7 fw-semibold mb-2">Subject</label>
                <Field
                    type="text"
                    name="kt_text_subject"
                    class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                    placeholder="Subject"
                    rows="4"
                ></Field>
            </div>
            <!--end::Input group-->
            
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-3">
                <label class="required fs-7 fw-semibold mb-2">Issues / Problems</label>
                <Field
                    as="textarea"
                    name="kt_areatext_issues_problems"
                    class="form-control form-control-solid form-sm border border-gray-300 fs-7 min-h-40px"
                    placeholder="Explain the issues, problem or task needed."
                    rows="10"
                ></Field>
            </div>
            <!--end::Input group-->

        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { ErrorMessage, Field } from "vee-validate";

export default defineComponent({
    name: "card-create-ticket-concern",
    components: {
        Field,
    },
    setup() {
    },
});
</script>
  