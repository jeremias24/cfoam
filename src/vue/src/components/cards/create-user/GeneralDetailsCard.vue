<template>
  <div class="card card-flush shadow-sm border m-5">
    <div class="card-header">
      <span class="fw-bolder card-title fs-5">General Details</span>
    </div>
    <div class="card-body pt-0">
      <!--begin::Col-->
      <div class="col-lg-6 mb-2">
        <!--begin::Label-->
        <label class="required fs-7 fw-semibold mb-2">User Id</label>
        <!--end::Label-->

        <Field
          type="text"
          name="id"
          v-model="initFormData.id"
          class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px mb-2"
          placeholder="User Id"
          rows="4"
        >
        </Field>

        <!--begin::ErrorMessage-->
        <ErrorMessage name="id" :data="initFormData.id" :error="initFormError">
        </ErrorMessage>
        <!--end::ErrorMessage-->
      </div>
      <!--end::Col-->

      <!--begin::Wrapper-->
      <div v-show="userLogged.user_class_id == 1">
        <div class="w-100">
          <!--begin::Heading-->
          <div class="pb-5 pb-lg-5">
            <!--begin::Title-->
            <h2
              class="required fw-bold d-flex align-items-center text-gray-900 fs-7"
            >
              User Class
            </h2>
            <!--end::Title-->

            <!--begin::Notice-->
            <div class="text-gray-500 fs-7">
              Choose the correct user class based on your request.
            </div>
            <!--end::Notice-->
          </div>
          <!--end::Heading-->

          <!--begin::Group button-->
          <GroupButtonStatic
            v-model:data="initFormData.userClassId"
            :buttons="initUserClassGroupButtons"
            @change="handleUserClassIdChange"
          >
          </GroupButtonStatic>
          <!--end::Group button-->

          <!--begin::ErrorMessage-->
          <ErrorMessage
            name="userClassId"
            :data="initFormData.userClassId"
            :error="initFormError"
          >
          </ErrorMessage>
          <!--end::ErrorMessage-->
        </div>
      </div>
      <!--end::Wrapper-->

      <!--begin::Input group-->
      <div class="d-flex flex-column fv-row">
        <!--begin::Row-->
        <div class="row">
          <div v-show="initFormData.userClassId == 1">
            <!--begin::Col-->
            <div class="col-lg-6 mb-2 pe-3">
              <!--begin::Label-->
              <label class="required fs-7 fw-semibold mb-2">Employee</label>
              <!--end::Label-->

              <!--begin::Select-->
              <Select2
                :data="initSelect2Data.employee"
                :value="initSelect2Value.employeeNo"
                :min-count-for-search="1"
                :custom-search-enabled="true"
                :keep-search-text="true"
                placeholder="Select a Employee"
                :allow-clear="true"
                @search="searchSelect2Value($event, `employee`)"
                @update="updateSelect2Value($event, `employeeNo`)"
              >
              </Select2>
              <!--end::Select-->
              <!--begin::ErrorMessage-->
              <ErrorMessage
                name="employeeId"
                :data="initFormData.employeeNo"
                :error="initFormError"
              >
              </ErrorMessage>
              <!--end::ErrorMessage-->
            </div>
            <!--end::Col-->
          </div>

          <!--begin::Col-->
          <div class="col-lg-6 mb-2 d-none">
            <!--begin::Label-->
            <label class="required fs-7 fw-semibold mb-2">Employee Id</label>
            <!--end::Label-->

            <Field
              type="text"
              name="employeeId"
              v-model="initFormData.employeeId"
              class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px mb-2"
              placeholder="Employee Id"
              rows="4"
            >
            </Field>

            <!--begin::ErrorMessage-->
            <ErrorMessage
              name="employeeId"
              :data="initFormData.employeeId"
              :error="initFormError"
            >
            </ErrorMessage>
            <!--end::ErrorMessage-->
          </div>
          <!--end::Col-->

          <div
            v-show="
              userLogged.user_class_id == 1 && initFormData.userClassId == 2
            "
          >
            <!--begin::Col-->
            <div class="col-lg-6 mb-2">
              <!--begin::Label-->
              <label
                :class="`${
                  userLogged.user_class_id == 2
                }  fs-7 fw-semibold mb-2`"
                >Customer</label
              >
              <!--end::Label-->

              <!--begin::Select-->
              <Select2
                :data="initSelect2Data.customer"
                :value="initSelect2Value.customerId"
                :min-count-for-search="1"
                :custom-search-enabled="true"
                :keep-search-text="true"
                placeholder="Select a Customer"
                :allow-clear="true"
                @search="searchSelect2Value($event, `customer`)"
                @update="updateSelect2Value($event, `customerId`)"
              >
              </Select2>
              <!--end::Select-->

              <!--begin::ErrorMessage-->
              <ErrorMessage
                name="customerId"
                :data="initFormData.customerId"
                :error="initFormError"
              >
              </ErrorMessage>
              <!--end::ErrorMessage-->
            </div>
            <!--end::Col-->
          </div>

          <div v-show="userLogged.user_class_id == 2">
            <!--begin::Col-->
            <div class="col-lg-6 mb-2">
              <!--begin::Label-->
              <label class="fs-7 fw-semibold mb-2">Customer</label>
              <!--end::Label-->

              <Field
                type="hidden"
                name="customerId"
                v-model="initFormData.customerId"
                class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px mb-2"
                placeholder="Customer Id"
                rows="4"
              >
              </Field>

              <Field
                type="text"
                name="customerName"
                v-model="initFormData.customerName"
                :readonly="true"
                class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px mb-2"
                placeholder="Customer Name"
                rows="4"
              >
              </Field>

              <!--begin::ErrorMessage-->
              <ErrorMessage
                name="customerId"
                :data="initFormData.customerId"
                :error="initFormError"
              >
              </ErrorMessage>
              <!--end::ErrorMessage-->
            </div>
            <!--end::Col-->
          </div>

          <div class="d-none">
            <!--begin::Col-->
            <div class="col-lg-6">
              <label class="required fs-7 fw-semibold mb-2">Party Number</label>
              <Field
                type="hidden"
                name="partyNumber"
                v-model="initFormData.partyNumber"
                class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px mb-2"
                placeholder="Party Number"
                rows="4"
              >
              </Field>
              <ErrorMessage
                name="partyNumber"
                class="fv-plugins-message-container invalid-feedback"
              />
            </div>
            <!--end::Col-->
          </div>

          <div class="d-none">
            <!--begin::Col-->
            <div class="col-lg-6">
              <label class="required fs-7 fw-semibold mb-2">Party Id</label>
              <Field
                type="hidden"
                name="partyId"
                v-model="initFormData.partyId"
                class="form-control form-control-solid form-sm border border-gray-300 fs-7 h-40px mb-2"
                placeholder="Party Id"
                rows="4"
              >
              </Field>
              <ErrorMessage
                name="partyId"
                class="fv-plugins-message-container invalid-feedback"
              />
            </div>
            <!--end::Col-->
          </div>

          <div
            v-show="
              initFormData.userClassId == 2 || userLogged.user_class_id == 2
            "
          >
            <div class="col-lg-6 mb-2">
              <!--begin::Label-->
              <label class="fs-7 fw-semibold mb-2">Satellite</label>
              <!--end::Label-->
              <!--begin::Select-->
              <Select2
                :data="initSelect2Data.satellite"
                :value="initSelect2Value.dealerSatelliteId"
                :min-count-for-search="1"
                :custom-search-enabled="true"
                :keep-search-text="true"
                placeholder="Select a Satellite"
                :allow-clear="true"
                @search="searchSelect2Value($event, `satellite`)"
                @update="updateSelect2Value($event, `dealerSatelliteId`)"
              >
              </Select2>
              <!--end::Select-->

              <!--begin::ErrorMessage-->
              <ErrorMessage
                name="dealerSatelliteId"
                :data="initFormData.dealerSatelliteId"
                :error="initFormError"
              >
              </ErrorMessage>
              <!--end::ErrorMessage-->
            </div>
          </div>

          <div v-show="initFormData.userClassId == 3">
            <div class="col-lg-6 pe-3">
              <!--begin::Label-->
              <label class="required fs-7 fw-semibold mb-2">Organization</label>
              <!--end::Label-->

              <!--begin::Select-->
              <Select2
                :data="initSelect2Data.organization"
                :value="initSelect2Value.organizationId"
                :min-count-for-search="1"
                :custom-search-enabled="true"
                :keep-search-text="true"
                placeholder="Select a Organization"
                :allow-clear="true"
                @search="searchSelect2Value($event, `organization`)"
                @update="updateSelect2Value($event, `organizationId`)"
              >
              </Select2>
              <!--end::Select-->

              <!--begin::ErrorMessage-->
              <ErrorMessage
                name="organizationId"
                :data="initFormData.organizationId"
                :error="initFormError"
              >
              </ErrorMessage>
              <!--end::ErrorMessage-->
            </div>
          </div>
        </div>
        <!--end::Row-->
      </div>
      <!--end::Input group-->
    </div>
  </div>
</template>

<script lang="ts">
import {
  computed,
  defineComponent,
  ref,
  type WritableComputedRef,
  onMounted,
  watch,
} from "vue";
import { Field } from "vee-validate";
import ApiService from "@/assets/template/core/services/ApiService";
import GroupButtonStatic from "@/assets/template/layouts/default-layout/components/extras/controls/button/GroupButtonStatic.vue";
import { Select2 } from "select2-vue-component";
import ErrorMessage from "@/assets/template/layouts/default-layout/components/extras/controls/ErrorMessage.vue";
import MixinService from "@/assets/template/core/services/MixinService";

export default defineComponent({
  name: "general-details-card",
  emits: [
    "update:formData",
    "update:userTypeDataChange",
    "update:accessDataChange",
  ],
  components: {
    GroupButtonStatic,
    Select2,
    ErrorMessage,
    Field,
  },
  props: {
    formData: { type: Object, required: true },
    userTypeDataChange: { type: Object, required: false },
    formValidationError: { type: Object, required: false },
    initUserTypeSelect2Data: { type: Object, required: false },
    userLogged: { type: Object, required: true, default: {} },
    readOnly: { type: Boolean, required: true },
  },
  setup(props, { emit }) {
    const initFormError = ref(props.formValidationError);
    const initUserTypeData = ref([]);
    const initEmployeeData = ref([]);
    const initUserTypeSelect2Data = ref(props.initUserTypeSelect2Data);
    const initCustomerData = ref([]);
    const initDealerData = ref([]);
    const initSatelliteData = ref([]);
    const initOrganizationData = ref([]);
    const userLogged = ref(props.userLogged);
    const initReadOnly = ref(props.readOnly);
    const initSelect2Data = ref({
      userType: MixinService.select2NoData(),
      employee: MixinService.select2NoData(),
      customer: MixinService.select2NoData(),
      satellite: MixinService.select2NoData(),
      organization: MixinService.select2NoData(),
    });

    const initSelect2Value = ref({
      userTypeId: null,
      employeeNo: null,
      customerId: null,
      dealerSatelliteId: null,
      organizationId: null,
    });

    const initUserClassGroupButtons = ref([
      {
        column: 4,
        name: "user-class",
        id: "system_owner",
        label: "Team Member",
        value: 1,
        info: "Direct Employee of Calamba Rice Growers Multipurpose Cooperative.",
        icon: "security-user",
        disabled: userLogged.value.user_class_id != 1 ? true : false,
      },
      {
        column: 4,
        name: "user-class",
        id: "dealer",
        label: "Dealer",
        value: 2,
        info: "Authorized to buy, sell, or trade products.",
        icon: "user-square",
        disabled: userLogged.value.user_class_id == 3 ? true : false,
      },
      {
        column: 4,
        name: "user-class",
        id: "others",
        label: "Others",
        value: 3,
        info: "Other person to perform tasks or services.",
        icon: "profile-circle",
        disabled: userLogged.value.user_class_id == 2 ? true : false,
      },
    ]);

    const fetchUserTypeData = () => {
      ApiService.get(`users/types/active_flag/1`)
        .then(({ data }) => {
          initUserTypeData.value = data.userTypes;
          emit(
            "update:userTypeDataChange",
            Object.assign(props.initUserTypeSelect2Data, {
              userType: MixinService.select2NoData(),
            })
          );
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const fetchEmployeeData = () => {
      return new Promise((resolve, reject) => {
        ApiService.get(`employees`)
          .then(({ data }) => {
            initEmployeeData.value = data.employees;
            resolve(); // Resolve the promise when data is fetched
          })
          .catch((error) => {
            console.log(error);
            reject(error); // Reject the promise if there's an error
          });
      });
    };

    const fetchCustomerData = () => {
      ApiService.get(`customers`)
        .then(({ data }) => {
          initCustomerData.value = data.customers;
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const fetchDealerData = () => {
      ApiService.get(`dealers`)
        .then(({ data }) => {
          initDealerData.value = data.dealers;
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const fetchSatelliteData = () => {
      ApiService.get(`dealers/satellites`)
        .then(({ data }) => {
          initSatelliteData.value = data.satellites;
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const fetchOrganizationData = () => {
      ApiService.get(`organizations`)
        .then(({ data }) => {
          initOrganizationData.value = data.organizations;
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const initDefaultDatas = () => {
      if (userLogged.value.user_class_id == 1) {
        initSelect2Data.value.employee =
          initEmployeeData.value.length > 0
            ? initEmployeeData.value.map((item) => ({
                label: item.fullName,
                value: item.employeeNo,
              }))
            : MixinService.select2NoData();

        initSelect2Data.value.customer =
          initCustomerData.value.length > 0
            ? initCustomerData.value.map((item) => ({
                label: item.partyName + " - " + item.accountName,
                value: item.custAccountId,
              }))
            : MixinService.select2NoData();
      } else if (userLogged.value.user_class_id == 2) {
        initFormData.value.customerId = userLogged.value.customer_id;
        initFormData.value.customerName = initCustomerData.value.filter(
          (value) => value.custAccountId == userLogged.value.customer_id
        )[0]["partyName"];
        initFormData.value.partyNumber = userLogged.value.party_number;
        initFormData.value.partyId = userLogged.value.party_id;
      }

      initSelect2Data.value.organization =
        initOrganizationData.value.length > 0
          ? initOrganizationData.value.map((item) => ({
              label: item.name,
              value: item.id,
            }))
          : MixinService.select2NoData();

      initSelect2Data.value.satellite =
        initSatelliteData.value.length > 0
          ? initSatelliteData.value.map((item) => ({
              label: item.accountName,
              value: item.id,
            }))
          : MixinService.select2NoData();
    };

    const initFormData: WritableComputedRef<object> = computed({
      get(): object {
        return props.formData;
      },
      set(value: object): void {
        emit("update:formData", value);
      },
    });

    const handleUserClassIdChange = () => {
      updateSelect2Value(null, `customerId`);
      updateSelect2Value(null, `dealerSatelliteId`);
      updateSelect2Value(null, `userTypeId`);
      updateSelect2Value(null, `employeeNo`);
      updateSelect2Value(null, `organizationId`);

      if (initFormData.value.userClassId == 1) {
        initSelect2Data.value.employee =
          initEmployeeData.value.length > 0
            ? initEmployeeData.value.map((item) => ({
                label: item.fullName,
                value: item.employeeNo,
              }))
            : MixinService.select2NoData();
      } else if (initFormData.value.userClassId == 2) {
        initSelect2Data.value.customer =
          initCustomerData.value.length > 0
            ? initCustomerData.value.map((item) => ({
                label: item.partyName + " - " + item.accountName,
                value: item.custAccountId,
              }))
            : MixinService.select2NoData();
      } else {
        initSelect2Data.value.employee = MixinService.select2NoData();
      }

      emit(
        "update:userTypeDataChange",
        Object.assign(props.initUserTypeSelect2Data, {
          userType: initSelect2Data.value.userType,
        })
      );
    };

    const searchSelect2Value = (text, id) => {
      switch (id) {
        case "userType":
          initSelect2Data.value.userType =
            initUserTypeData.value.filter(
              (value) =>
                value.name.toLowerCase().includes(text.toLowerCase()) &&
                value.userClassId == props.formData.userClassId
            ).length > 0
              ? initUserTypeData.value
                  .filter(
                    (value) =>
                      value.name.toLowerCase().includes(text.toLowerCase()) &&
                      value.userClassId == props.formData.userClassId
                  )
                  .map((item) => ({ label: item.name, value: item.id }))
              : MixinService.select2NoData();
          break;
        case "employee":
          initSelect2Data.value.employee =
            initEmployeeData.value.filter((value) =>
              value.fullName.toLowerCase().includes(text.toLowerCase())
            ).length > 0
              ? initEmployeeData.value
                  .filter((value) =>
                    value.fullName.toLowerCase().includes(text.toLowerCase())
                  )
                  .map((item) => ({
                    label: item.fullName,
                    value: item.employeeNo,
                  }))
              : MixinService.select2NoData();
          break;
        case "customer":
          initSelect2Data.value.customer =
            initCustomerData.value.filter((value) =>
              value.partyName.toLowerCase().includes(text.toLowerCase())
            ).length > 0
              ? initCustomerData.value
                  .filter((value) =>
                    value.partyName.toLowerCase().includes(text.toLowerCase())
                  )
                  .map((item) => ({
                    label: item.partyName + " - " + item.accountName,
                    value: item.custAccountId,
                  }))
              : MixinService.select2NoData();
          break;
        case "satellite":
          initSelect2Data.value.satellite =
            initSatelliteData.value.filter((value) =>
              value.accountName.toLowerCase().includes(text.toLowerCase())
            ).length > 0
              ? initSatelliteData.value
                  .filter((value) =>
                    value.accountName.toLowerCase().includes(text.toLowerCase())
                  )
                  .map((item) => ({ label: item.accountName, value: item.id }))
              : MixinService.select2NoData();
          break;
        case "organization":
          initSelect2Data.value.organization =
            initOrganizationData.value.filter((value) =>
              value.name.toLowerCase().includes(text.toLowerCase())
            ).length > 0
              ? initOrganizationData.value
                  .filter((value) =>
                    value.name.toLowerCase().includes(text.toLowerCase())
                  )
                  .map((item) => ({ label: item.name, value: item.id }))
              : MixinService.select2NoData();
          break;
        default:
      }
    };

    const updateSelect2Value = (value, attribute) => {
      initSelect2Value.value[attribute] = value;

      if (
        initFormData.value.userClassId == 1 &&
        attribute == "employeeNo" &&
        value !== null
      ) {
        const employee =
          initEmployeeData.value.find((item) => item.employeeNo == value) || {};

        const fields = [
          "firstName",
          "middleName",
          "lastName",
          "suffixName",
          "nickName",
          "email",
          "phoneNumber",
          "employeeNo",
          "position",
          "positionTitle",
          "division",
          "department",
          "section",
          "divisionId",
          "departmentId",
          "sectionId",
        ];

        initFormData.value.username = employee.employeeNo;
        initFormData.value.employeeId = employee.id;

        for (const field of fields) {
          initFormData.value[field] = employee[field] || "";
        }
      } else if ([2, 3].includes(initFormData.value.userClassId)) {
        if (
          !["customerId", "dealerSatelliteId", "organizationId"].includes(
            attribute
          )
        ) {
          const fields = [
            "firstName",
            "middleName",
            "lastName",
            "suffixName",
            "nickName",
            "employeeId",
            "email",
            "username",
            "position",
            "positionTitle",
            "division",
            "department",
            "section",
            "divisionId",
            "departmentId",
            "sectionId",
          ];

          for (const field of fields) {
            initFormData.value[field] = null;
          }
        }

        if (userLogged.value.user_class_id == 1 && attribute == "customerId") {
          if (initFormData.value.userClassId == 2 && value !== null) {
            const customerData = initCustomerData.value.filter(
              (item) => item.custAccountId == value
            )[0];
            initFormData.value.partyNumber = customerData
              ? customerData.partyNumber
              : null;
            initFormData.value.partyId = customerData
              ? customerData.partyId
              : null;
          } else {
            initFormData.value.partyNumber = null;
            initFormData.value.partyId = null;
          }
        }
      }

      emit(
        "update:formData",
        Object.assign(props.formData, { [attribute]: value })
      );
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

    onMounted(async () => {
      fetchUserTypeData();
      fetchCustomerData();
      fetchDealerData();
      fetchSatelliteData();
      fetchOrganizationData();
      await Promise.all([fetchEmployeeData()]);

      initDefaultDatas();
    });

    return {
      initUserClassGroupButtons,
      initFormData,
      initFormError,
      searchSelect2Value,
      handleUserClassIdChange,
      initUserTypeData,
      initEmployeeData,
      initSatelliteData,
      updateSelect2Value,
      initSelect2Data,
      initSelect2Value,
      initUserTypeSelect2Data,
      userLogged,
    };
  },
});
</script>