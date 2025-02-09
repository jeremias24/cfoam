<template>
    <div class="card card-flush shadow-sm border m-5">
        <div class="card-header">
            <span class="fw-bolder card-title fs-5">Signatories</span>
        </div>
        <div class="card-body pt-0">

            <!--begin::Input group-->
            <div class="d-flex flex-column fv-row">
                <!--begin::Row-->
                <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-6">
                        <!--begin::Label-->
                        <label class="required fs-7 fw-semibold mb-2">Approved By</label>
                        <!--end::Label-->

                        <!--begin::Select-->
                        <Select2 :data="initSelect2Data.signatory" :value="initSelect2Value.approvedBy"
                            :min-count-for-search="1" :custom-search-enabled="true" :keep-search-text="true"
                            placeholder="Select a System" @search="searchSelect2Value($event, `signatory`)"
                            @update="updateSelect2Value($event, `approvedBy`)">
                        </Select2>
                        <!--end::Select-->
                        <ErrorMessage name="approvedBy" :data="initFormData.approvedBy" :error="initFormError"
                            :class="`mb-0`"></ErrorMessage>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Input group-->

        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, defineExpose, ref, onMounted, watch, computed, WritableComputedRef } from "vue";
import { Select2 } from "select2-vue-component";
import ApiService from "@/assets/template/core/services/ApiService";
import MixinService from "@/assets/template/core/services/MixinService";

import ErrorMessage from "@/assets/template/layouts/default-layout/components/extras/controls/ErrorMessage.vue";

export default defineComponent({
    name: "card-signatories",
    components: {
        Select2,
        ErrorMessage,
    },
    props: {
        formData: { type: Object, required: true },
        formValidationError: { type: Object, required: false, default: {} },
    },
    emits: ["update:formData"],
    setup(props, { emit }) {
        const initFormError = ref(props.formValidationError);
        const initSignatoryData = ref([]);

        const initSelect2Data = ref({
            signatory: MixinService.select2NoData(),
        });
        const initSelect2Value = ref({
            approvedBy: null,
        });

        onMounted(async () => {
            await Promise.all([
                //fetchSignatoryData(),
            ]);
        });

        const fetchSignatoryData = () => {
            return new Promise((resolve, reject) => {
                ApiService.get("signatories/section_id/18")
                    .then(({ data }) => {
                        initSignatoryData.value = data.signatories;
                        initSelect2Data.value.signatory = data.signatories.filter(value => ['ash', 'sh', 'adh', 'dh', 'advh', 'dvh'].includes(value.positionSuffix)).map(item => ({ label: `${item.signatory} - ${item.position}`, value: item.id }));
                        resolve();
                    }).catch(error => {
                        console.log(error);
                        reject(error);
                    });
            });
        };

        const initFormData: WritableComputedRef<object> = computed({
            get(): object {
                return props.formData;
            },
            set(value: object): void {
                emit("update:formData", value);
            },
        });

        watch(
            () => props.formValidationError,
            (value) => {
                initFormError.value = value;
            },
        );

        const searchSelect2Value = (text, id) => {
            switch (id) {
                case "signatory":
                    initSelect2Data.value.signatory = initSignatoryData.value.filter(value => ['ash', 'sh', 'adh', 'dh', 'advh', 'dvh'].includes(value.positionSuffix) && value.signatory.toLowerCase().includes(text.toLowerCase())).length > 0 ?
                        initSignatoryData.value.filter(value => ['ash', 'sh', 'adh', 'dh', 'advh', 'dvh'].includes(value.positionSuffix) && value.signatory.toLowerCase().includes(text.toLowerCase())).map(item => ({ label: `${item.signatory} - ${item.position}`, value: item.id })) :
                        MixinService.select2NoData();
                    break;
            };
        };

        const updateSelect2Value = (value, attribute) => {
            initSelect2Value.value[attribute] = value;
            emit("update:formData", Object.assign(props.formData, { [attribute]: value }));
        };

        defineExpose({
            initSignatoryData,
        });

        return {
            initFormData,
            initFormError,
            initSignatoryData,
            initSelect2Data,
            initSelect2Value,
            searchSelect2Value,
            updateSelect2Value,
        };
    },
});
</script>
