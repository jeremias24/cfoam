<template>
    <div class="card card-flush shadow-sm border m-5">
        <div class="card-header">
            <span class="fw-bolder card-title fs-5">Attachments</span>
        </div>
        <div class="card-body pt-0">
            
            <!--begin::Input group-->
            <div class="d-flex flex-column fv-row">
                <!--begin::Label-->
                <label class="fw-semibold fs-7 mb-2">Upload Documents</label>
                <!--end::Label-->

                <Upload
                    v-model:formData="initFormData.attachments"
                    form-key="attachments"
                    container-path="storage/attachments/ticket/"
                    button-label="Attach File/s"
                    button-icon="add-files"
                ></Upload>
                
            </div>
            <!--end::Input group-->

        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, type WritableComputedRef } from "vue";

import Upload from "@/assets/template/layouts/default-layout/components/extras/controls/upload/Upload.vue";

export default defineComponent({
    name: "card-attachments",
    components: {
        Upload,
    },
    props: {
        formData: { type: Object, required: true },
    },
    emits: ["update:formData"],
    setup(props, { emit }) {
        const initFormData: WritableComputedRef<object> = computed({
            get(): object {
                return props.formData;
            },
            set(value: object): void {
                emit("update:formData", value);
            },
        });

        return {
            initFormData,
        };
    },
});
</script>
