<template>
  <div class="card">
    <div class="card-header border-0 py-0">
      <!--begin::Card title-->
      <div class="card-title">
        <div class="flex-column">
          <h2 class="fs-5">{{ initPageTitle }}</h2>
          <div class="fs-7 fw-semibold text-muted">{{ initPageSubtitle }}</div>
        </div>
        <div v-if="selectedDatasId.length > 0" class="mx-10">
          <!--begin::Group actions-->
          <div
            class="d-flex justify-content-end align-items-center"
            data-kt-customer-table-toolbar="selected"
          >
            <div class="fw-bold fs-7 me-5">
              <span class="me-2">{{ selectedDatasId.length }}</span>
              Selected
            </div>
            <div v-for="(button, i) in initSelectedButton" :key="i">
              <button
                type="button"
                :class="`btn btn-sm hover-scale ${button.buttonClass}`"
                @click="button?.onClickFunc"
              >
                {{ button.buttonLabel }}
              </button>
            </div>
          </div>
          <!--end::Group actions-->
        </div>
      </div>
      <!--end::Card title-->
      <!--begin::Card toolbar-->
      <div class="card-toolbar">
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
            v-model="initSearch"
            @input="searchData()"
            class="form-control form-control-solid w-250px border border-gray-300 fs-7 ps-15"
            placeholder="Search"
          />
        </div>
        <!--end::Search-->
      </div>
      <!--end::Card toolbar-->
    </div>
    <div class="card-body py-0">
      <Datatable
        @on-sort="sortData"
        @on-items-select="onDataSelect"
        :data="initTableData"
        :header="initTableHeader"
        :enable-items-per-page-dropdown="true"
        :checkbox-enabled="true"
        :row-numbering-enabled="true"
        :loading="initTableLoading"
        checkbox-label="id"
        items-per-page="25"
      >
        <template v-slot:fullName="{ row: initData }">
          {{ initData.fullName }}
          <!-- <a href="#" class="text-gray-600 text-hover-primary mb-1"></a> -->
        </template>
        <template v-slot:section="{ row: initData }">
          {{ initData.section }}
        </template>
        <template v-slot:userClass="{ row: initData }">
          <!-- <img :src="customer.payment.icon" class="w-35px me-3" alt="" /> -->
          {{ initData.userClass }}
        </template>
        <template v-slot:status="{ row: initData }">
          <div
            :class="`badge badge-light-${getStatusColor(
              initData.statusCode
            )} fs-7 fw-bold`"
          >
            {{ initData.status }}
          </div>
        </template>
        <template v-slot:actions="{ row: initData }">
          <a
            class="btn btn-sm btn-light btn-active-light-primary"
            data-kt-menu-trigger="click"
            data-kt-menu-placement="bottom-end"
            data-kt-menu-flip="top-end"
          >
            Actions
            <KTIcon icon-name="down" icon-class="fs-5 m-0" />
          </a>
          <!--begin::Menu-->
          <div
            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
            data-kt-menu="true"
          >
            <!--begin::Menu item-->
            <div v-for="(action, x) in initTableAction" :key="x">
              <div v-for="(button, y) in action.buttons" :key="`${x}${y}`">
                <div
                  v-show="
                    button.showOnStatus.includes(initData.statusCode) &&
                    availableTo(initData, userAccessData, button)
                  "
                  class="menu-item px-3"
                >
                  <a
                    @click="button.buttonFunc(initData.userClassId)"
                    class="menu-link px-3"
                  >
                    <span class="menu-icon" data-kt-element="icon">
                      <KTIcon
                        :icon-name="button.buttonIcon"
                        :icon-class="`fs-2 ${button?.IconClass}`"
                      />
                    </span>
                    <span class="menu-title">{{ button.buttonName }}</span>
                  </a>
                </div>
                <div
                  v-show="
                    button?.separator
                      ? groupAction(initData, action.buttons, x)
                      : false
                  "
                  class="separator border-gray-300 my-1"
                ></div>
              </div>
            </div>
            <!--end::Menu item-->
          </div>
          <!--end::Menu-->
        </template>
      </Datatable>
      <div v-if="initToolbarNav">
        <Teleport to="#kt_toolbar_actions">
          <div v-for="(action, i) in initToolbarAction" :key="i">
            <!--begin::Action button-->
            <a
              v-if="action.showModalOnClick"
              :class="`btn btn-sm fw-bold hover-scale ${action.buttonClass}`"
              data-bs-toggle="modal"
              :data-bs-target="`#${action?.targetModal}`"
            >
              {{ action.buttonLabel }}
            </a>
            <a
              v-else
              :class="`btn btn-sm fw-bold ${action.buttonClass}`"
              @click="action?.onClickFunc"
            >
              {{ action.buttonLabel }}
            </a>
            <!--end::Action button-->
          </div>
        </Teleport>
      </div>
    </div>
  </div>

  <!--begin::Modal-->
  <ModalForm
    :id="initModalId"
    ref="initModalComponentRef"
    :title="initModalTitle"
    :show-close-button="initModalCloseButton"
    :width="1000"
    :ribbon="initModalRibbon"
    :ribbon-label="initModalRibbonLabel"
    :ribbon-color="initModalRibbonColor"
    :key="initModalId"
  >
    <template v-slot:modalContent>
      <component
        :is="initModal"
        :user-logged="getUser"
        :title="initModalTitle"
        :user-class-id="initUserClassId"
      >
      </component>
    </template>
  </ModalForm>
  <!--end::Modal-->
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, Component, computed } from "vue";
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { MenuComponent } from "@/assets/template/ts/components";
import { hideModal, showModal } from "@/assets/template/core/helpers/modal";
import type { Sort } from "@/assets/template/layouts/default-layout/components/datatable/table-partials/models";
import type { IRequest } from "@/types/requests";
import type { ISystem } from "@/types/systems";
import arraySort from "array-sort";
import User from "@/components/forms/User.vue";
import ApiService from "@/assets/template/core/services/ApiService";
import Datatable from "@/assets/template/layouts/default-layout/components/datatable/KTDataTable.vue";
import ModalForm from "@/assets/template/layouts/default-layout/components/modal/ModalForm.vue";
import ModalStepper from "@/assets/template/layouts/default-layout/components/modal/ModalStepper.vue";
import { useAuthStore } from "@/assets/template/stores/auth";
import MixinService from "@/assets/template/core/services/MixinService";

export default defineComponent({
  name: "view-requests",
  components: {
    Datatable,
    ModalForm,
    ModalStepper,
    User,
  },
  setup() {
    /** Default Setup */
    let initData: Array<IRequest> = [];
    const authStore = useAuthStore();
    const initTableLoading = ref<boolean>(true);
    const initTableData = ref<Array<IRequest>>([]);
    const initSearch = ref<string>("");
    const initToolbarNav = ref<boolean>(false);
    const initModalCloseButton = ref<boolean>(true);
    const initModalRibbon = ref<boolean>(false);
    const initModalRibbonLabel = ref<string>("");
    const initModalRibbonColor = ref<string>("");
    const selectedDatasId = ref<Array<number>>([]);
    const userAccessData = ref<Array<ISystem>>([]);
    /** Dynamic Setup */
    const initPageTitle = ref<string>("Requests List");
    const initPageSubtitle = ref<string>("All Requests");
    const initModalComponentRef = ref<null | HTMLElement>(null);
    const initModalId = ref<string>("kt_modal_user");
    const initModalTitle = ref<string>("New User");
    const initUserClassId = ref<null | number>(null);
    const initModal = ref<Component>(User);
    const initToolbarAction = ref([
      {
        buttonLabel: "Create New User",
        buttonClass: "btn-primary",
        showModalOnClick: false,
        // targetModal: "kt_modal_ticket",
        onClickFunc: () => {
          initUserClassId.value = [1, 2].includes(getUser.value.user_class_id)
            ? 2
            : 3;
          showModal(initModalComponentRef.value.initModalRef);
        },
      },
    ]);

    const initTableHeader = ref([
      {
        columnName: "Name",
        columnLabel: "fullName",
        sortEnabled: true,
        labelClass: "text-start",
      },
      {
        columnName: "Section",
        columnLabel: "section",
        columnTruncate: true,
        sortEnabled: true,
        columnWidth: 175,
        labelClass: "text-start",
      },
      {
        columnName: "User Class",
        columnLabel: "userClass",
        sortEnabled: true,
        columnWidth: 175,
        labelClass: "text-start",
      },
      {
        columnName: "Status",
        columnLabel: "status",
        sortEnabled: true,
        columnWidth: 175,
        labelClass: "text-start",
      },
      {
        columnName: "Actions",
        columnLabel: "actions",
        sortEnabled: false,
        columnWidth: 100,
        labelClass: "text-center",
      },
    ]);

    const initSelectedButton = ref([
      {
        buttonLabel: "Delete Selected",
        buttonClass: "btn-danger",
        onClickFunc: () => {
          alert();
        },
      },
      {
        buttonLabel: "View Selected",
        buttonClass: "ms-3 btn-primary",
        onClickFunc: () => {
          alert();
        },
      },
      {
        buttonLabel: "Record Selected",
        buttonClass: "ms-3 btn-success",
        onClickFunc: () => {
          alert();
        },
      },
    ]);

    const initTableAction = ref([
      {
        buttons: [
          {
            buttonName: "Edit",
            buttonIcon: "pencil",
            buttonFunc: (val) => {
              editData(val);
            },
            showOnStatus: ["RJCTD"],
          },
          {
            buttonName: "View",
            buttonIcon: "eye",
            IconClass: "text-info",
            buttonFunc: () => {
              editData();
            },
            showOnStatus: ["SBMTD"],
          },
          // {
          //     buttonName: "Delete",
          //     buttonIcon: "trash",
          //     IconClass: "text-danger",
          //     buttonFunc: () => { editData(); },
          //     showOnStatus: [1, 3, 5, 8],
          //     separator: true,
          // },
        ],
      },
    ]);

    const getUser = computed(() => {
      return authStore?.getUser();
    });

    const editData = (val) => {
      initUserClassId.value = val;
      initModalTitle.value = "Edit User";

      showModal(initModalComponentRef.value.initModalRef);
    };

    const fetchData = () => {
      ApiService.get(
        `requests/${MixinService.encrypt(JSON.stringify({ show: "all" }))}`
      )
        .then(({ data }) => {
          if (getUser.value.user_class_id == 1) {
            initData = data.userRequests;
            initTableData.value = data.userRequests;
          } else {
            initData = data.userRequests.filter(
              (value) =>
                value.userClassId == getUser.value.user_class_id &&
                value.customerId == getUser.value.customer_id
            );
            initTableData.value = data.userRequests.filter(
              (value) =>
                value.userClassId == getUser.value.user_class_id &&
                value.customerId == getUser.value.customer_id
            );
          }

          initTableLoading.value = false;
        })
        .then(() => {
          MenuComponent.reinitialization();
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const fetchAccessesData = () => {
      ApiService.get("/systems/active-accesses")
        .then(({ data }) => {
          userAccessData.value = data.systems;
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const sortData = (sort: Sort) => {
      const reverse: boolean = sort.order === "asc";
      if (sort.label) {
        arraySort(initTableData.value, sort.label, { reverse });
      }
    };

    const searchData = () => {
      if (initSearch.value !== "") {
        let results: Array<IUser> = [];
        for (let j = 0; j < initData.length; j++) {
          if (searchingFunc(initData[j], initSearch.value)) {
            results.push(initData[j]);
          }
        }
        initTableData.value = results;
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

    const onDataSelect = (selectedDatas: Array<number>) => {
      selectedDatasId.value = selectedDatas;
    };

    const deleteFewDatas = () => {
      selectedDatasId.value.forEach((item) => {
        deleteData(item);
      });
      selectedDatasId.value.length = 0;
    };

    const deleteData = (id: number) => {
      for (let i = 0; i < initData.length; i++) {
        if (initData[i].id === id) {
          initData.splice(i, 1);
        }
      }
    };

    const groupAction = (data: any, buttons: any, i: number) => {
      let showSeparator: boolean = false;
      let showedActionCount: number = 0;
      let nextShowedActionCount: number = 0;
      buttons.forEach((button) => {
        if (button?.showOnStatus.includes(data?.status_id)) {
          if (button?.availableOn && button?.availableOn.length > 0) {
            availableTo(data, userAccessData, button);
          } else {
            showedActionCount++;
          }
        }
      });
      if (showedActionCount > 0) {
        if (i + 1 < initTableAction.value.length) {
          for (let x = i + 1; x <= initTableAction.value.length; x++) {
            initTableAction.value[x - 1].buttons.forEach((button) => {
              if (button?.showOnStatus.includes(data?.status_id)) {
                nextShowedActionCount++;
              }
            });
            if (nextShowedActionCount > 0) {
              showSeparator = true;
              break;
            }
          }
        }
      }
      return showSeparator;
    };

    const availableTo = (data: any, accesses: any, button: any) => {
      if (accesses.length > 0) {
        let isTM: boolean = false;
        let isSystemOwner: boolean = false;
        let isDealer: boolean = false;
        let isDealerAdmin: boolean = false;

        isTM = getUser.value.user_class_id == 1 ? true : false;
        isSystemOwner =
          accesses.filter((access) => [2, 3].includes(access.userTypeId))
            .length > 0
            ? true
            : false;
        isDealer = getUser.value.user_class_id == 2 ? true : false;
        isDealerAdmin =
          accesses.filter((access) => [6].includes(access.userTypeId)).length >
          0
            ? true
            : false;

        if (isTM && isSystemOwner) {
          return true;
        } else if (isDealer && isDealerAdmin) {
          if ([2].includes(data.userClassId)) {
            return true;
          } else {
            return false;
          }
        }
      }
    };

    const getStatusColor = (value) => {
      return MixinService.getStatusColor(value);
    };

    onMounted(() => {
      fetchData();
      fetchAccessesData();
      initToolbarNav.value = true;
    });

    return {
      initPageTitle,
      initPageSubtitle,
      initModal,
      initModalComponentRef,
      initModalId,
      initModalTitle,
      initModalCloseButton,
      initModalRibbon,
      initModalRibbonLabel,
      initModalRibbonColor,
      initData,
      initTableData,
      initTableHeader,
      initToolbarAction,
      initToolbarNav,
      initSearch,
      initTableLoading,
      initTableAction,
      initSelectedButton,
      sortData,
      searchData,
      onDataSelect,
      selectedDatasId,
      groupAction,
      availableTo,
      getAssetPath,
      deleteData,
      deleteFewDatas,
      getUser,
      userAccessData,
      initUserClassId,
      getStatusColor,
    };
  },
});
</script>