<template>
  <div class="card card-flush shadow-sm border mx-5 m-5 mb-5">
    <div class="card-header">
      <span class="fw-bolder card-title fs-5">Assign Accesses Details</span>
      <!--begin::Card toolbar-->
      <div class="card-toolbar">
        <!--begin::Disable-->
        <div v-if="userLogged.section_id == 18">
          <div
            class="d-flex align-items-center position-relative input-group-sm ms-3"
          >
            <div class="form-check pe-2 mb-2">
              <input
                v-model="initFormData.disableAllAccess"
                class="form-check-input"
                type="checkbox"
                value=""
              />
              <label class="form-check-label"> Disable All </label>
            </div>
          </div>
        </div>
        <!--end::Disable-->

        <!--begin::Search-->
        <div
          class="d-flex align-items-center position-relative input-group-sm ms-3"
        >
          <KTIcon
            icon-name="magnifier"
            icon-class="fs-1 position-absolute ms-6"
          />
          <input
            type="text"
            v-model="initAccessSearch"
            @input="searchAccessesData()"
            class="form-control form-control-solid w-250px border border-gray-300 fs-7 ps-15"
            placeholder="Search"
          />
        </div>
        <!--end::Search-->
      </div>
      <!--end::Card toolbar-->
    </div>
    <div class="card-body pt-0">
      <div class="d-flex flex fv-row">
        <!--begin::Col-->
        <div class="col-lg-12">
          <DatatableList
            @on-sort="sortData"
            :data="initTableAccessesData"
            :header="initTableHeader"
            :enable-items-per-page-dropdown="false"
            :checkbox-enabled="false"
            :row-numbering-enabled="true"
            checkbox-label="systemId"
            :responsive="false"
            items-per-page="10"
          >
            <template v-slot:system="{ row: initAccessesData }">
              {{ initAccessesData.system }}
            </template>
            <template v-slot:options="{ row: initAccessesData }">
              <Select2
                v-show="!initFormData.disableAllAccess"
                :value="
                  initEditAccessesData.filter(
                    (value) => value.systemId == initAccessesData.systemId
                  )[0]?.userTypeId
                "
                :data="
                  initAccessesData.options.filter(
                    (option) =>
                      option.userClassId == initFormData.userClassId ||
                      option.userClassId == 0
                  )
                "
                :min-count-for-search="1"
                :custom-search-enabled="true"
                @update="
                  updateAccesses(
                    $event,
                    `userTypeId`,
                    initAccessesData.systemId
                  )
                "
                placeholder="Select a User Type"
                class="pe-3"
              >
              </Select2>

              <Select2
                v-show="initFormData.disableAllAccess"
                :data="
                  initAccessesData.options.filter(
                    (option) => option.userClassId == 0
                  )
                "
                :value="1"
                :disabled="initFormData.disableAllAccess"
                class="pe-3"
              >
              </Select2>
            </template>
          </DatatableList>
        </div>
        <!--end::Col-->
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, watch } from "vue";
import ApiService from "@/assets/template/core/services/ApiService";
import { Field, ErrorMessage } from "vee-validate";
import { Select2 } from "select2-vue-component";
import type { ISystem } from "@/types/systems";
import { MenuComponent } from "@/assets/template/ts/components";
import DatatableList from "@/assets/template/layouts/default-layout/components/datatable/KTDataTableList.vue";
import type { Sort } from "@/assets/template/layouts/default-layout/components/datatable/table-partials/models";
import arraySort from "array-sort";

export default defineComponent({
  name: "accesses-details-card",
  components: {
    Field,
    ErrorMessage,
    MenuComponent,
    DatatableList,
    Select2,
  },
  props: {
    formData: { type: Object, required: true },
    formValidationError: { type: Object, required: false, default: {} },
    initUserTypeSelect2Data: { type: Object, required: false, default: {} },
    initUserAccessData: { type: Object, required: false, default: {} },
    userLogged: { type: Object, required: true, default: {} },
    editAccessesData: { type: Array, required: false, default: [] },
    title: { type: String, required: true, default: "" },
  },
  emits: ["update:userAccessDataChange", "update:approversDataChange"],
  setup(props, { emit }) {
    let initAccessesData: Array<ISystem> = [];
    const initFormData = ref(props.formData);
    const initFormError = ref(props.formValidationError);
    const initUserTypeSelect2Data = ref(props.initUserTypeSelect2Data);
    const initUserAccessData = ref(props.initUserAccessData);
    const userLogged = ref(props.userLogged);
    const initTableLoading = ref<boolean>(true);
    const initTableAccessesData = ref<Array<ISystem>>([]);
    const initEditAccessesData = ref<Array<ISystem>>([]);
    const initAccessSearch = ref<string>("");
    const title = ref(props.title);

    const initTableHeader = ref([
      {
        columnName: "System",
        columnLabel: "system",
        sortEnabled: true,
        columnWidth: 175,
        labelClass: "text-start",
      },
      {
        columnName: "Access",
        columnLabel: "options",
        sortEnabled: true,
        columnWidth: 175,
        labelClass: "text-start",
      },
    ]);

    const sortData = (sort: Sort) => {
      const reverse: boolean = sort.order === "asc";
      if (sort.label) {
        arraySort(initTableAccessesData.value, sort.label, { reverse });
      }
    };

    const searchAccessesData = () => {
      if (initAccessSearch.value !== "") {
        let results: Array<ISystem> = [];
        for (let j = 0; j < initAccessesData.length; j++) {
          if (searchingFunc(initAccessesData[j], initAccessSearch.value)) {
            results.push(initAccessesData[j]);
          }
        }
        initTableAccessesData.value = results;
      }
      MenuComponent.reinitialization();
    };

    const searchingFunc = (obj: any, value: string): boolean => {
      for (let key in obj) {
        if (!Number.isInteger(obj[key]) && !(typeof obj[key] === "object")) {
          if (obj[key].toLowerCase().indexOf(value.toLowerCase()) != -1) {
            return true;
          }
        }
      }
      return false;
    };

    const fetchAccessesData = () => {
      ApiService.get("/systems/active-accesses")
        .then(({ data }) => {
          initAccessesData = data.systems;
          initTableAccessesData.value = data.systems;
          initTableLoading.value = false;
        })
        .then(() => {
          MenuComponent.reinitialization();
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const updateAccesses = (value, attribute, systemId) => {
      const accessIndex = initUserAccessData.value.findIndex(
        (data) => data.systemId == systemId
      );

      if (accessIndex !== -1) {
        initUserAccessData.value[accessIndex][attribute] = value;
      } else {
        initUserAccessData.value.push({
          [attribute]: value,
          systemId: systemId,
          statusId: title.value == "New User" ? 31 : 25,
          statusCode: title.value == "New User" ? "PNDG" : "ACTV",
        });
      }

      emit("update:userAccessDataChange", initUserAccessData.value);
    };

    watch(
      () => props.formValidationError,
      (val) => {
        initFormError.value = val;
      }
    );

    watch(
      () => props.editAccessesData,
      (newValue, oldValue) => {
        initEditAccessesData.value = newValue;
      }
    );

    watch(
      () => props.title,
      (curr, prev) => {
        title.value = curr;
      }
    );

    onMounted(() => {
      fetchAccessesData();
    });

    return {
      initFormData,
      initFormError,
      initTableHeader,
      initTableAccessesData,
      sortData,
      initTableLoading,
      initAccessSearch,
      searchAccessesData,
      initAccessesData,
      initUserTypeSelect2Data,
      initUserAccessData,
      updateAccesses,
      userLogged,
      initEditAccessesData,
    };
  },
});
</script>