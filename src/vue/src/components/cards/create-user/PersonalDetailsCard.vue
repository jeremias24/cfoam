<template>
    <div class="card card-flush shadow-sm border mx-5 mb-5">
        <div class="card-header">
            <span class="fw-bolder card-title fs-5">Personal Details</span>
        </div>
        <div class="card-body pt-0">
            <div class="d-flex flex fv-row">

                <!--begin::Col-->
                <div class="col-lg-4">
                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3 me-2">
                        <label class="required fs-7 fw-semibold mb-2">First Name</label>
                        <Field type="text" name="firstname" v-model="initFormData.firstName" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="First name" rows="4">
                        </Field>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3 me-2">
                        <label class="fs-7 fw-semibold mb-2">Suffix Name</label>
                        <Field type="text" name="suffix_name" v-model="initFormData.suffixName" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Suffix Name (Jr., Sr., II,. etc.,)" rows="4">
                        </Field>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Col-->

                <!--begin::Col-->
                <div class="col-lg-4">
                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3 me-2">
                        <label class="fs-7 fw-semibold mb-2">Middle Name</label>
                        <Field type="text" name="middlename" v-model="initFormData.middleName" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px" a
                            placeholder="Middle Name" rows="4">
                        </Field>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3 me-2">
                        <label class="fs-7 fw-semibold mb-2">Nick Name</label>
                        <Field type="text" name="nickname" v-model="initFormData.nickName" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Nick Name" rows="4">
                        </Field>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Col-->

                <!--begin::Col-->
                <div class="col-lg-4">
                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3">
                        <label class="required fs-7 fw-semibold mb-2">Last Name</label>
                        <Field type="text" name="lastname" v-model="initFormData.lastName" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Last Name" rows="4">
                        </Field>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Col-->

            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {
    defineComponent,
    ref,
    watch
} from "vue";
import { ErrorMessage, Field } from "vee-validate";

export default defineComponent({
    name: "personal-details-card",
    components: {
        Field,
        ErrorMessage
    },
    props: {
        formData: { type: Object, required: true },
        formValidationError: { type: Object, required: false, default: {} },
        readOnly: { type: Boolean, required: true }
    },
    setup(props) {
        const initFormData = ref(props.formData);
        const initReadOnly = ref(props.readOnly);
        const initFormError = ref(props.formValidationError);

        watch(
            () => props.formValidationError,
            (val) => {
                initFormError.value = val;
            }
        );

        watch(
            () => props.readOnly,
            (val) => {
                initReadOnly.value = val;
            }
        );

        return {
            initFormData,
            initFormError,
            initReadOnly
        };
    },
});
</script>