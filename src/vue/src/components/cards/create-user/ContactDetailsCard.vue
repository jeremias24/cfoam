<template>
    <div class="card card-flush shadow-sm border mx-5 mb-5">
        <div class="card-header">
            <span class="fw-bolder card-title fs-5">Contact Details</span>
        </div>
        <div class="card-body pt-0">

            <div class="d-flex flex fv-row">

                <!--begin::Col-->
                <div class="col-lg-6 pe-2">

                    <label class="required fs-7 fw-semibold mb-2">Email</label>
                    <div v-if="initFormData.userClassId != 1">
                        <!--begin::Input group-->
                        <div class="input-group">
                            <Field type="text" name="email" v-model="initFormData.email"
                                :rules="initFormData.userClassId !== 1 ? email : undefined"
                                @update:model-value="handleEmailChange" autocomplete="off" :readonly="initReadOnly"
                                class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                                placeholder="Email" rows="4">
                            </Field>
                            <span @click="verifyEmail" class="input-group-text btn btn-flex btn-sm h-40px"
                                :class="[isLoadingEmail ? 'btn-primary' : (!isEmailExist ? isEmailVerified ? 'btn-success' : 'btn-light-info' : 'btn-danger'), { 'disabled': isDisabled }]">
                                <KTIcon
                                    :icon-name="`${isLoadingEmail ? 'loading' : (!isEmailExist ? isEmailVerified ? 'check' : 'magnifier' : 'information-5')}`"
                                    icon-class="fs-4" />
                                <span>{{ isLoadingEmail ? 'Checking...' : (!isEmailExist ? isEmailVerified ? 'Available'
                        : 'Check' : 'Used') }}</span>
                            </span>
                        </div>
                        <div v-show="isEmailExist" class="fv-plugins-message-container invalid-feedback">
                            Email is already taken
                        </div>
                        <ErrorMessage name="email" class="fv-plugins-message-container invalid-feedback" />
                        <!--end::Input group-->
                    </div>
                    <div v-else>
                        <!--begin::Input group-->
                        <Field type="text" name="email" v-model="initFormData.email"
                            :rules="initFormData.userClassId !== 1 ? email : undefined" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Email" rows="4">
                        </Field>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--end::Col-->

                <!--begin::Col-->
                <div class="col-lg-6">
                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3">
                        <label class="fs-7 fw-semibold mb-2">Phone No.</label>
                        <Field type="text" name="phone_number" v-model="initFormData.phoneNumber"
                            :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Phone No." rows="4">
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
import { defineComponent, ref, watch } from "vue";
import { ErrorMessage, Field } from "vee-validate";
import * as Yup from 'yup';

export default defineComponent({
    name: "contact-details-card",
    components: {
        Field,
        ErrorMessage
    },
    props: {
        formData: { type: Object, required: true },
        formValidationError: { type: Object, required: false, default: {} },
        readOnly: { type: Boolean, required: true },
        verifyData: { type: Object, required: false, default: {} },
        emailExist: { type: Boolean, required: true },
        loading: { type: Boolean, required: true },
    },
    emits: ["verifyAccount"],
    setup(props, { emit }) {
        const initFormData = ref(props.formData);
        const initFormError = ref(props.formValidationError);
        const initReadOnly = ref(props.readOnly);
        const isDisabled = ref<boolean>(true);
        const isEmailExist = ref<boolean>(false);
        const isEmailVerified = ref<boolean>(false);
        const isLoadingEmail = ref<boolean>(false);
        const email = Yup.string().email("Email must be valid.").required("Email is required.");

        const verifyEmail = () => {
            isEmailVerified.value = true;
            emit("verifyAccount", { email: initFormData.value.email });
        };

        const handleEmailChange = () => {
            const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(initFormData.value.email);
            isDisabled.value = !isValidEmail;
            isEmailVerified.value = false;
            emit("verifyAccount", { emailChange: isDisabled.value });
        };

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

        watch(
            () => props.emailExist,
            (val) => {
                isEmailExist.value = val;
                isDisabled.value = isEmailExist.value ? true : false;
            }
        );

        watch(
            () => props.loading,
            (val) => {
                isLoadingEmail.value = val;
            }
        );

        return {
            initFormData,
            initFormError,
            initReadOnly,
            email,
            verifyEmail,
            isDisabled,
            handleEmailChange,
            isEmailExist,
            isEmailVerified,
            isLoadingEmail
        };
    },
});
</script>