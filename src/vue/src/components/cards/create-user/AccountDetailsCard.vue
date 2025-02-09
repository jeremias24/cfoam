<template>
    <div class="card card-flush shadow-sm border mx-5 mb-5">
        <div class="card-header">
            <span class="fw-bolder card-title fs-5">Account Details</span>
        </div>
        <div class="card-body pt-0">
            <div class="d-flex flex-column fv-row">
                <!--begin::Col-->
                <div class="col-lg-6">

                    <label class="required fs-7 fw-semibold mb-2">Username</label>
                    <div v-if="initFormData.userClassId != 1">
                        <!--begin::Input group-->
                        <div class="input-group pe-2 mb-2">
                            <Field type="text" name="username" v-model="initFormData.username"
                                :rules="initFormData.userClassId !== 1 ? username : undefined"
                                @update:model-value="handleUsernameChange" autocomplete="off" :readonly="initReadOnly"
                                class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                                placeholder="Username" rows="4">
                            </Field>
                            <span @click="verifyUsername" class="input-group-text btn btn-flex btn-sm h-40px"
                                :class="[isLoadingUsername ? 'btn-primary' : (!isUsernameExist ? isUsernameVerified ? 'btn-success' : 'btn-light-info' : 'btn-danger'), { 'disabled': isDisabled }]">
                                <KTIcon
                                    :icon-name="`${isLoadingUsername ? 'loading' : (!isUsernameExist ? isUsernameVerified ? 'check' : 'magnifier' : 'information-5')}`"
                                    icon-class="fs-4" />
                                <span>{{ isLoadingUsername ? 'Checking...' : (!isUsernameExist ? isUsernameVerified ?
                        'Available' : 'Check' : 'Used') }}</span>
                            </span>
                        </div>
                        <div v-show="isUsernameExist" class="fv-plugins-message-container invalid-feedback">
                            Username is already taken
                        </div>
                        <ErrorMessage name="username" class="fv-plugins-message-container invalid-feedback" />
                        <!--end::Input group-->
                    </div>
                    <div v-else>
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-3 me-2">
                            <Field type="text" name="username" v-model="initFormData.username"
                                :rules="initFormData.userClassId !== 1 ? username : undefined" :readonly="initReadOnly"
                                class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                                placeholder="Username" rows="4">
                            </Field>
                        </div>
                        <!--end::Input group-->
                    </div>


                    <div v-show="initFormData.userClassId != 1">
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-3 me-2">
                            <label class="required fs-7 fw-semibold mb-2">Password</label>
                            <Field type="text" name="password" v-model="initFormData.password" :rules="password"
                                class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                                placeholder="Password" rows="4">
                            </Field>
                            <ErrorMessage name="password" class="fv-plugins-message-container invalid-feedback" />
                        </div>
                        <!--end::Input group-->
                    </div>

                </div>
                <!--end::Col-->
            </div>

            <div class="d-flex flex fv-row">
                <!--begin::Col-->
                <div class="col-lg-6">

                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3 me-2">
                        <label class="fs-7 fw-semibold mb-2">Position</label>
                        <Field type="text" name="position" v-model="initFormData.positionTitle" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Position" rows="4">
                        </Field>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3 me-2">
                        <label class="fs-7 fw-semibold mb-2">Section</label>
                        <Field type="text" name="section" v-model="initFormData.section" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Section" rows="4">
                        </Field>
                    </div>
                    <!--end::Input group-->

                </div>
                <!--end::Col-->

                <!--begin::Col-->
                <div class="col-lg-6">

                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3 me-2">
                        <label class="fs-7 fw-semibold mb-2">Division</label>
                        <Field type="text" name="division" v-model="initFormData.division" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Division" rows="4">
                        </Field>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-3 me-2">
                        <label class="fs-7 fw-semibold mb-2">Department</label>
                        <Field type="text" name="department" v-model="initFormData.department" :readonly="initReadOnly"
                            class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px"
                            placeholder="Department" rows="4">
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
import { Field, ErrorMessage } from "vee-validate";
import * as Yup from 'yup';

export default defineComponent({
    name: "account-details-card",
    components: {
        Field,
        ErrorMessage
    },
    props: {
        formData: { type: Object, required: true },
        readOnly: { type: Boolean, required: true },
        formValidationError: { type: Object, required: false, default: {} },
        usernameExist: { type: Boolean, required: true },
        loading: { type: Boolean, required: true },
    },
    emits: ["verifyAccount"],
    setup(props, { emit }) {
        const initFormData = ref(props.formData);
        const initFormError = ref(props.formValidationError);
        const initReadOnly = ref(props.readOnly);
        const isDisabled = ref<boolean>(true);
        const isUsernameExist = ref<boolean>(false);
        const isUsernameVerified = ref<boolean>(false);
        const isLoadingUsername = ref<boolean>(false);
        const username = Yup.string().min(6).required("Username is required.");
        const password = Yup.string().required("Password is required.")
            .min(12, "Password must be at least 12 characters long.")
            .matches(/(?=.*[a-z])/, "Password must contain at least one lowercase letter.")
            .matches(/(?=.*[A-Z])/, "Password must contain at least one uppercase letter.")
            .matches(/(?=.*\d)/, "Password must contain at least one digit.")
            .matches(/(?=.*[@$!%*?&_-])/, "Password must contain at least one special character (@$!%*?&_-).")
            .label("Password");

        const handleUsernameChange = () => {
            isUsernameVerified.value = false;
            isDisabled.value = initFormData.value.username.length >= 6 ? false : true;
            emit("verifyAccount", { usernameChange: isDisabled.value });
        };

        const verifyUsername = () => {
            isUsernameVerified.value = true;
            emit("verifyAccount", { username: initFormData.value.username });
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
            () => props.usernameExist,
            (val) => {
                isUsernameExist.value = val;
                isDisabled.value = isUsernameExist.value ? true : false;
            }
        );

        watch(
            () => props.loading,
            (val) => {
                isLoadingUsername.value = val;
            }
        );

        return {
            initFormData,
            initFormError,
            username,
            password,
            initReadOnly,
            isUsernameVerified,
            isUsernameExist,
            isLoadingUsername,
            isDisabled,
            verifyUsername,
            handleUsernameChange
        };
    },
});
</script>