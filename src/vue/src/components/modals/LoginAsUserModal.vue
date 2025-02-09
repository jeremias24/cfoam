<template>
  <!--begin::Modal - Login as User-->
  <div
    class="modal fade"
    :id="`${initModalId}`"
    ref="loginAsUserModalRef"
    tabindex="-1"
    aria-hidden="true"
  >
    <!--begin:Form-->
    <div
      :id="`${initModalId}_form`"
      :model="account"
      ref="loginAsUserRef"
      class="form"
    >
      <!--begin::Modal dialog-->
      <div :class="`modal-dialog modal-dialog-centered mw-${width}px`">
        <!--begin::Modal content-->
        <div class="modal-content">
          <!--begin::Modal header-->
          <div
            class="modal-header ribbon ribbon-end ribbon-clip pb-0"
            :id="`${initModalId}_header`"
          >
            <!--begin::Modal title-->
            <h2 class="fw-bold">{{ initModalTitle.value }}</h2>
            <!--end::Modal title-->

            <!--begin::Close-->
            <div
              v-show="initModalCloseButton"
              :id="`${initModalId}_close`"
              data-bs-dismiss="modal"
              class="btn btn-icon btn-sm btn-active-icon-primary"
            >
              <KTIcon icon-name="cross" icon-class="fs-1" />
            </div>

            <div
              v-show="initModalRibbon.value"
              class="ribbon-label fw-bolder fs-2 px-5"
            >
              {{ `${initModalRibbonLabel.value}` }}
              <span
                :class="`ribbon-inner bg-${initModalRibbonColor.value}`"
              ></span>
            </div>
            <!--end::Close-->
          </div>
          <!--end::Modal header-->
          <!--begin::Modal body-->
          <div class="modal-body">
            <!--begin::Input group-->
            <div class="d-flex flex-column fv-row">
              <!--begin::Row-->
              <div class="row">
                <!--begin::Col-->
                <div class="col-lg-12 mb-2 pe-3">
                  <!--begin::Label-->
                  <label class="required fs-7 fw-semibold mb-2">User</label>
                  <!--end::Label-->

                  <!--begin::Select-->
                  <Select2
                    :data="initSelect2Data.user"
                    :value="initSelect2Value.id"
                    :min-count-for-search="1"
                    :custom-search-enabled="true"
                    :keep-search-text="true"
                    placeholder="Select a User"
                    :allow-clear="true"
                    @search="searchSelect2Value($event, `user`)"
                    @update="updateSelect2Value($event, `id`)"
                  >
                  </Select2>
                  <!--end::Select-->

                  <!--begin::ErrorMessage-->
                  <ErrorMessage
                    name="id"
                    :data="account.id"
                    :error="initFormError"
                  >
                  </ErrorMessage>
                  <!--end::ErrorMessage-->
                </div>
                <!--end::Col-->
              </div>
            </div>
          </div>
          <!--end::Modal body-->

          <!--begin::Modal footer-->
          <div class="modal-footer flex-end">
            <!--begin::Button-->
            <button
              type="reset"
              v-show="initModalCloseButton"
              :id="`${initModalId}_close`"
              data-bs-dismiss="modal"
              class="btn btn-sm me-3 hover-scale btn-light"
            >
              Cancel
            </button>
            <button
              v-show="getCookieUserId == undefined"
              @click="startImpersonation"
              :data-kt-indicator="initLoading ? 'on' : null"
              class="btn btn-sm me-3 hover-scale btn-primary"
            >
              <span v-if="!initLoading" class="indicator-label">
                Start Impersonate
                <KTIcon icon-name="arrow-right" icon-class="fs-5 ms-2 me-0" />
              </span>
              <span v-if="initLoading" class="indicator-progress">
                Please wait...
                <span
                  class="spinner-border spinner-border-sm align-middle ms-2"
                >
                </span>
              </span>
            </button>

            <button
              v-show="getCookieUserId != undefined"
              @click="stopImpersonation"
              :data-kt-indicator="initLoading ? 'on' : null"
              class="btn btn-sm me-3 hover-scale btn-danger"
            >
              <span v-if="!initLoading" class="indicator-label">
                Stop Impersonate
                <KTIcon icon-name="arrow-left" icon-class="fs-5 ms-2 me-0" />
              </span>
              <span v-if="initLoading" class="indicator-progress">
                Please wait...
                <span
                  class="spinner-border spinner-border-sm align-middle ms-2"
                >
                </span>
              </span>
            </button>
            <!--end::Button-->
          </div>
          <!--end::Modal footer-->
        </div>
        <!--end::Modal content-->
      </div>

      <!--end::Modal dialog-->
    </div>
    <!--end:Form-->
  </div>
  <!--end::Modal - Login as User-->
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted } from "vue";
import { Select2 } from "select2-vue-component";
import { useAuthStore, type User } from "@/assets/template/stores/auth";
import MixinService from "@/assets/template/core/services/MixinService";
import { Form, ErrorMessage } from "vee-validate";
import ApiService from "@/assets/template/core/services/ApiService";
import { useRouter } from "vue-router";
import { useCookies } from "vue3-cookies";

interface Account {
  id: number;
  username: string;
  password: string;
}

export default defineComponent({
  name: "new-target-modal",
  components: {
    Select2,
    Form,
    ErrorMessage,
  },

  props: {
    id: { type: String, required: true },
    ref: { type: HTMLElement, required: false },
    title: { type: String, required: true },
    showCloseButton: { type: Boolean, required: false, default: true },
    modalBackdrop: { type: String, required: false, default: "static" },
    closeKeypress: { type: Boolean, required: false, default: false },
    width: { type: Number, required: false, default: 1000 },
    ribbon: { type: Boolean, required: false, default: false },
    ribbonLabel: { type: String, required: false, default: "" },
    ribbonColor: { type: String, required: false, default: "" },
    data: { type: Object, required: true, default: {} },
  },

  setup(props) {
    const store = useAuthStore();
    const router = useRouter();
    const initLoading = ref<boolean>(false);
    const initModalRef = ref<null | HTMLElement>();
    const initModalId = ref<string>(props.id);
    const initModalTitle = ref<string>(props.title);
    const initModalWidth = ref<number>(props.width);
    const initModalCloseButton = ref<boolean>(props.showCloseButton);
    const initModalRibbon = ref<boolean>(props.ribbon);
    const initModalRibbonLabel = ref<string>(props.ribbonLabel);
    const initModalRibbonColor = ref<string>(props.ribbonColor);
    const initModalBackdrop = ref<string>(props.modalBackdrop);
    const initModalCloseKeypress = ref<boolean>(props.closeKeypress);
    const userLogged = ref(props.data);
    const initValidationError = ref({});
    const initFormError = ref({});
    const { cookies } = useCookies();

    const initSelect2Data = ref({
      user: MixinService.select2NoData(),
    });

    const initSelect2Value = ref({
      id: null,
    });

    const loginAsUserRef = ref<null | HTMLFormElement>(null);
    const loginAsUserModalRef = ref<null | HTMLElement>(null);

    const account = ref<Account>({
      id: null,
      username: "",
      password: "",
    });

    const initFormDefaultRules = ref({
      id: [
        {
          required: true,
          message: "User field is required.",
        },
      ],
    });

    const initUserData: Array<Account> = [];

    initModalTitle.value = computed(() => {
      return props.title;
    });

    initModalRibbon.value = computed(() => {
      return props.ribbon;
    });

    initModalRibbonLabel.value = computed(() => {
      return props.ribbonLabel;
    });

    initModalRibbonColor.value = computed(() => {
      return props.ribbonColor;
    });

    const fetchUserData = () => {
      ApiService.get(
        `users/${MixinService.encrypt(JSON.stringify({ show: "all" }))}`
      )
        .then(({ data }) => {
          initUserData.value = data.users;
          initSelect2Data.value.user =
            initUserData.value.length > 0
              ? initUserData.value.map((item) => ({
                  label:
                    item.fullName +
                    " - " +
                    (item.userClass == null ? "" : item.userClass),
                  value: item.id,
                }))
              : MixinService.select2NoData();
        })
        .catch((error) => {
          console.log(error);
        });
    };

    const searchSelect2Value = (text, id) => {
      switch (id) {
        case "user":
          initSelect2Data.value.user =
            initUserData.value.filter((value) =>
              value.fullName.toLowerCase().includes(text.toLowerCase())
            ).length > 0
              ? initUserData.value
                  .filter((value) =>
                    value.fullName.toLowerCase().includes(text.toLowerCase())
                  )
                  .map((item) => ({
                    label:
                      item.fullName +
                      " - " +
                      (item.userClass == null ? "" : item.userClass),
                    value: item.id,
                  }))
              : MixinService.select2NoData();
          break;

        default:
      }
    };

    const updateSelect2Value = (value, attribute) => {
      initSelect2Value.value[attribute] = value;
      const user = initUserData.value.find((item) => item.id == value) || {};
      account.value.id = user.id;
    };

    const startImpersonation = async () => {
      initValidationError.value = MixinService.validateForm(
        account.value,
        initFormDefaultRules.value
      );

      if (initValidationError.value.valid) {
        const id = userLogged.value.id;
        const section_id = userLogged.value.section_id;
        if (cookies.get("userId") != "") {
          cookies.set("userId", id.toString());
          cookies.set("sectionId", section_id.toString());
        }
        store.logout();
        await store.impersonate({
          id: account.value.id,
          section_id: section_id,
        });

        const error = store.errors;
        const accesses = store.user.accesses;
        if (error.length === 0) {
          if (accesses !== undefined && accesses.length > 0) {
            router.push({ name: "User" });

            window.location.reload();
          } else {
            router.push({ name: "404" });
          }
        }
      }
    };

    const stopImpersonation = async () => {
      store.logout();
      const id = cookies.get("userId");
      const section_id = cookies.get("sectionId");

      cookies.remove("userId");
      cookies.remove("sectionId");

      await store.impersonate({ id: id, section_id: section_id });

      const error = store.errors;
      const accesses = store.user.accesses;

      if (error.length === 0) {
        if (accesses !== undefined && accesses.length > 0) {
          router.push({ name: "User" });
          window.location.reload();
        } else {
          router.push({ name: "404" });
        }
      }
    };

    const getCookieUserId = computed(() => {
      return cookies.get("userId");
    });

    onMounted(() => {
      if (userLogged.value.section_id == 18) {
        fetchUserData();
      }
    });

    return {
      account,
      startImpersonation,
      stopImpersonation,
      initLoading,
      loginAsUserRef,
      loginAsUserModalRef,
      initModalRef,
      initModalId,
      initModalTitle,
      initModalWidth,
      initModalBackdrop,
      initModalCloseKeypress,
      initModalCloseButton,
      initModalRibbon,
      initModalRibbonLabel,
      initModalRibbonColor,
      initSelect2Data,
      initSelect2Value,
      userLogged,
      initUserData,
      searchSelect2Value,
      updateSelect2Value,
      initFormDefaultRules,
      initFormError,
      cookies,
      getCookieUserId,
    };
  },
});
</script>


