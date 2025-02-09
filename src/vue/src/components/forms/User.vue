<template>
  <!--begin::Form-->
  <Form id="kt_form_user" class="form">
    <GeneralDetailsCard
      v-show="title == 'New User'"
      v-model:formData="initFormData"
      :form-validation-error="initValidationError"
      :user-logged="userLogged"
      @userTypeDataChange="handleUserClassIdChange"
      :read-only="initReadOnly"
      :init-user-type-select2-data="initUserTypeSelect2Data"
    >
    </GeneralDetailsCard>
    <PersonalDetailsCard
      v-show="title == 'New User'"
      v-model:formData="initFormData"
      :form-validation-error="initValidationError"
      :read-only="initReadOnly"
    >
    </PersonalDetailsCard>
    <ContactDetailsCard
      v-show="title == 'New User'"
      v-model:formData="initFormData"
      :form-validation-error="initValidationError"
      :email-exist="emailExist"
      :loading="isLoadingEmail"
      @verify-account="verifyAccount"
      :read-only="initReadOnly"
    >
    </ContactDetailsCard>
    <AccountDetailsCard
      v-show="title == 'New User'"
      v-model:formData="initFormData"
      :form-validation-error="initValidationError"
      :username-exist="usernameExist"
      :loading="isLoadingUsername"
      @verify-account="verifyAccount"
      :read-only="initReadOnly"
    >
    </AccountDetailsCard>
    <AccessesDetailsCard
      v-model:formData="initFormData"
      :form-validation-error="initValidationError"
      :user-logged="userLogged"
      @userAccessDataChange="getUserAccess"
      :init-user-access-data="initUserAccessData"
      @approversDataChange="getApprovers"
      :approvers-data="approversData"
      :init-user-type-select2-data="initUserTypeSelect2Data"
      :edit-accesses-data="editAccessesData"
      :title="title"
    >
    </AccessesDetailsCard>
    <!-- <SignatoriesCard ref="initSignatoriesCardRef" v-model:formData="initFormData"
            :form-validation-error="initValidationError">
        </SignatoriesCard> -->
    <NoteCard :title="title" />
  </Form>
  <!--end::Form-->

  <!--begin::Modal footer-->
  <div class="modal-footer flex-end">
    <!--begin::Button-->
    <button
      v-for="(button, i) in initModalFooterButton"
      :key="i"
      :type="button.type"
      :data-kt-indicator="button.loading ? 'on' : null"
      :class="`btn btn-sm me-3 hover-scale ${button.buttonClass} ${
        button.disabled ? 'disabled' : ''
      }`"
      @click="button?.onClickFunc"
    >
      <KTIcon
        v-if="button?.buttonIcon"
        :icon-name="button.buttonIcon"
        icon-class="fs-3 me-2"
      />
      <span v-if="!button.loading" class="indicator-label fs-7">
        {{ button.buttonLabel }}
      </span>
      <span v-if="button.loading" class="indicator-progress">
        <span class="spinner-border spinner-border-sm align-middle me-2"></span>
        {{ button?.loadingLabel }}
      </span>
    </button>
    <!--end::Button-->
  </div>
  <!--end::Modal footer-->
</template>

<script lang="ts">
import { defineComponent, ref, watch, computed } from "vue";
import MixinService from "@/assets/template/core/services/MixinService";
import { Form, ErrorMessage } from "vee-validate";
import GeneralDetailsCard from "@/components/cards/create-user/GeneralDetailsCard.vue";
import PersonalDetailsCard from "@/components/cards/create-user/PersonalDetailsCard.vue";
import ContactDetailsCard from "@/components/cards/create-user/ContactDetailsCard.vue";
import AccountDetailsCard from "@/components/cards/create-user/AccountDetailsCard.vue";
import AccessesDetailsCard from "@/components/cards/create-user/AccessesDetailsCard.vue";
import SignatoriesCard from "@/components/cards/create-user/SignatoriesCard.vue";
import NoteCard from "@/components/cards/create-user/NoteCard.vue";
import { IUser } from "@/types/users";
import type { ISystem } from "@/types/systems";
import { MenuComponent } from "@/assets/template/ts/components";
import ApiService from "@/assets/template/core/services/ApiService";

export default defineComponent({
  name: "form-user",
  data() {
    return {
      formData: {},
    };
  },
  components: {
    Form,
    ErrorMessage,
    GeneralDetailsCard,
    PersonalDetailsCard,
    ContactDetailsCard,
    AccountDetailsCard,
    AccessesDetailsCard,
    SignatoriesCard,
    NoteCard,
  },
  props: {
    userLogged: { type: Object, required: true, default: {} },
    title: { type: String, required: true, default: "" },
    id: { type: Number, required: true, default: null },
    userClassId: { type: Number, required: true, default: null },
    editAccessesData: { type: Array, required: false, default: [] },
  },
  setup(props) {
    const userLogged = ref(props.userLogged);
    const initValidationError = ref({});
    const initSignatoriesCardRef = ref<null | HTMLElement>(null);
    const title = ref(props.title);
    const initFormData = ref<IUser>({
      id: props.id != null ? props.id : null,
      employeeNo: null,
      firstName: null,
      middleName: null,
      lastName: null,
      suffixName: null,
      fullName: null,
      nickName: null,
      email: null,
      phoneNumber: null,
      customerId: null,
      customerName: null,
      dealerSatelliteId: null,
      username: null,
      password: null,
      position: null,
      positionTitle: null,
      sectionId: null,
      section: null,
      departmentId: null,
      department: null,
      divisionId: null,
      division: null,
      userClassId:
        userLogged.value.user_class_id == 1
          ? null
          : userLogged.value.user_class_id, // not assigning user class id here
      userClass: null,
      partyNumber: null,
      partyId: null,
      accesses: null,
      approvedBy: null,
      approvals: {},
    });

    const verifyData = ref<Object>({
      username: "",
      email: "",
    });

    const initReadOnly = ref<boolean>(
      userLogged.value.user_class_id == 1 ? true : false
    );
    const initUserTypeSelect2Data = ref<Object>({});
    const initUserAccessData = ref([]);
    const approversData = ref([]);
    const emailExist = ref<boolean>(false);
    const usernameExist = ref<boolean>(false);
    let emailTempVal = ref<boolean>(false);
    let usernameTempVal = ref<boolean>(false);
    const isLoadingEmail = ref<boolean>(false);
    const isLoadingUsername = ref<boolean>(false);
    const initFormDefaultRules = ref({
      userClassId: [
        {
          required: true,
          message: "User Class field is required.",
        },
      ],
    });

    const initModalFooterButton = ref([
      {
        type: "submit",
        loading: false,
        disabled: false,
        loadingLabel: "Please wait...",
        buttonLabel: "Submit",
        buttonClass: "btn-primary",
        buttonIcon: "plus",
        onClickFunc: () => {
          if (initFormData.value.id == null) submit("create");
          else submit("update");
        },
      },
      {
        type: "button",
        loading: false,
        buttonLabel: "Discard",
        buttonClass: "btn-light-danger",
        buttonIcon: "times",
        onClickFunc: () => {
          alert();
        },
      },
    ]);

    const initSignatoriesHierarchy = ref([
      {
        hierarchy: 1,
        approverId: null,
        key: "systemOwner",
        position: "System Owner",
        remarks: "System Owner",
      },
    ]);

    const handleUserClassIdChange = (data: Object) => {
      initUserTypeSelect2Data.value = data;
    };

    const getUserAccess = (data: Object) => {
      initUserAccessData.value = data;
    };

    const verifyAccount = (data: Object) => {
      Object.assign(verifyData.value, data);
      verifyData.value = data;
      const key = Object.keys(verifyData.value).join(", ");
      const value = Object.values(verifyData.value).join(", ");

      if (["username", "email"].includes(key)) {
        if (key == "username") isLoadingUsername.value = true;
        else isLoadingEmail.value = true;

        ApiService.get(`requests/username-emails/${key}/${value}`)
          .then(({ data }) => {
            if (key == "username") {
              if (data.accounts.length > 0) usernameExist.value = true;
              else usernameExist.value = false;
            } else {
              if (data.accounts.length > 0) emailExist.value = true;
              else emailExist.value = false;
            }

            if (!emailExist.value && !usernameExist.value) {
              if (
                emailTempVal.value == "false" &&
                usernameTempVal.value == "false"
              )
                initModalFooterButton.value.filter(
                  (value) => value.type == "submit"
                )[0].disabled = false;
            }
          })
          .then(() => {
            if (key == "username") isLoadingUsername.value = false;
            else isLoadingEmail.value = false;
          })
          .catch(({ error }) => {
            console.log(error);
          });
      } else if (["usernameChange", "emailChange"].includes(key)) {
        if (key == "usernameChange") usernameTempVal.value = value;
        else emailTempVal.value = value;

        initModalFooterButton.value.filter(
          (value) => value.type == "submit"
        )[0].disabled = true;
      }
    };

    const getApprovers = (data: Object) => {
      approversData.value = data;
    };

    watch(
      () => props.id,
      (newValue, oldValue) => {
        initFormData.value.id = newValue;
      }
    );

    watch(
      () => props.userClassId,
      (newValue, oldValue) => {
        initFormData.value.userClassId = newValue;
      }
    );

    watch(
      () => initFormData.value.userClassId,
      (newValue, oldValue) => {
        if (newValue == 1 || props.title == "Edit User") {
          initReadOnly.value = true;
          initModalFooterButton.value.filter(
            (value) => value.type == "submit"
          )[0].disabled = false;
        } else {
          initReadOnly.value = false;
          initModalFooterButton.value.filter(
            (value) => value.type == "submit"
          )[0].disabled = true;
        }
      }
    );

    watch(
      () => initFormData.value.email,
      () => {
        emailExist.value ? (emailExist.value = false) : null;
      }
    );

    watch(
      () => initFormData.value.username,
      () => {
        usernameExist.value ? (usernameExist.value = false) : null;
      }
    );

    watch(
      () => emailTempVal.value,
      (curr, prev) => {
        emailTempVal.value = curr;
      }
    );

    watch(
      () => props.title,
      (curr, prev) => {
        title.value = curr;

        if (curr == "New User") {
          initModalFooterButton.value.filter(
            (value) => value.type == "submit"
          )[0].disabled = true;
        } else {
          initModalFooterButton.value.filter(
            (value) => value.type == "submit"
          )[0].disabled = false;
        }
      }
    );

    const constructSignatories = (status) => {
      let approvals = [];

      initSignatoriesHierarchy.value.forEach((value) => {
        approvals.push({
          hierarchy: value.hierarchy,
          approverId: value.approverId,
          position: value.position,
          emailSentFlag: 0,
          sentDatetime: null,
          emailResendFlag: null,
          resendDatetime: null,
          notificationEmailSentFlag: null,
          notificationEmailSentDatetime: null,
          statusCode: status,
          statusDatetime: null,
          remarks: value.remarks,
        });
      });

      return approvals;
    };

    const submit = (type) => {
      initFormData.value.accesses = initUserAccessData.value;
      initFormData.value.approvers = approversData.value;

      if (initFormData.value.userClassId !== 1 && title.value == "New User") {
        initFormDefaultRules.value = {
          ...initFormDefaultRules.value,
          firstName: [
            {
              required: true,
              message: "Firstname is required",
            },
          ],
          lastName: [
            {
              required: true,
              message: "Lastname is required",
              trigger: "change",
            },
          ],
          email: [
            {
              required: true,
              message: "Email is required",
              trigger: "change",
            },
            {
              type: "email",
              message: emailExist.value
                ? "Email has already taken "
                : "Invalid email format",
              existing: emailExist.value,
            },
          ],
          username: [
            {
              required: true,
              message: "Username is required",
              trigger: "change",
            },
            {
              type: "username",
              message: "Username has already taken",
              existing: usernameExist.value,
            },
          ],
          password: [
            {
              required: true,
              message: "Password is required",
              trigger: "change",
            },
            {
              type: "password",
              message: "Invalid password",
            },
          ],
        };
      }

      if (initFormData.value.userClassId == 2 && title.value == "New User") {
        initFormDefaultRules.value = {
          ...initFormDefaultRules.value,
          dealerSatelliteId: [
            {
              required: true,
              message: "Customer is required",
            },
          ],
        };
      }

      if (initFormData.value.userClassId == 3 && title.value == "New User") {
        initFormDefaultRules.value = {
          ...initFormDefaultRules.value,
          organizationId: [
            {
              required: true,
              message: "Organization is required",
            },
          ],
        };
      }

      if (Object.keys(initFormData.value.accesses).length == 0) {
        initFormDefaultRules.value = {
          ...initFormDefaultRules.value,
          accesses: [
            {
              required: true,
              message: "Access is required",
            },
            {
              type: "accesses",
              message: "Access is required",
            },
          ],
        };
      }

      initValidationError.value = MixinService.validateForm(
        initFormData.value,
        initFormDefaultRules.value
      );

      if (initValidationError.value.valid) {
        initFormData.value.approvals = constructSignatories("PNDG");
        initFormData.value.statusCode = type == "create" ? "SBMTD" : "";
        initFormData.value.statusId = type == "create" ? 27 : 25;

        MixinService.showConfirmAlert(
          "Are you sure you want to submit this request?",
          "Submit",
          () => {
            if (type == "create") {
              ApiService.post(
                "requests",
                MixinService.serialize(initFormData.value)
              )
                .then(({ data }) => {
                  console.log(data);
                })
                .then(() => {
                  MenuComponent.reinitialization();
                })
                .catch(({ error }) => {
                  console.log(error);
                });
            } else {
              ApiService.put(
                `users/update-accesses/`,
                MixinService.serialize(initFormData.value)
              )
                .then(({ data }) => {
                  setTimeout(() => {
                    if (data.status == "Passed") {
                      MixinService.showAlert(
                        "Access has been updated",
                        "success"
                      );
                    } else {
                      MixinService.showAlert(data.error);
                    }
                  }, 1000);
                })
                .then(() => {
                  MenuComponent.reinitialization();
                })
                .catch(({ error }) => {
                  console.log(error);
                });
            }
          }
        );
      }
    };

    return {
      initFormData,
      initFormDefaultRules,
      initValidationError,
      initReadOnly,
      initModalFooterButton,
      handleUserClassIdChange,
      initUserTypeSelect2Data,
      initUserAccessData,
      getUserAccess,
      userLogged,
      approversData,
      getApprovers,
      verifyData,
      verifyAccount,
      emailExist,
      usernameExist,
      isLoadingUsername,
      isLoadingEmail,
      emailTempVal,
      initSignatoriesCardRef,
      title,
    };
  },
});
</script>
