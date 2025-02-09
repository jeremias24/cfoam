<template>
  <!--begin::Content wrapper-->
  <div class="d-flex flex-column flex-center p-10">
    <!--begin::Card-->
    <div id="kt_card_approval" class="card card-flush w-md-650px pt-5">
      <!--begin::Header-->
      <div
        :style="`border-bottom: 5px solid ${
          themeMode == 'dark' ? '#fd0d1b' : '#000'
        };`"
        class="d-flex flex-stack align-items-center flex-wrap"
      >
        <!--begin::Logo-->
        <div class="ms-5 mt-0">
          <img
            v-if="themeMode == 'light' || themeMode == 'system'"
            alt="Logo"
            :src="getAssetPath('media/logos/crgmpc.png')"
            class="h-25px"
          />
          <img
            v-if="themeMode == 'dark'"
            alt="Logo"
            :src="getAssetPath('media/logos/crgmpc.png')"
            class="h-25px"
          />
        </div>
        <!--end::Logo-->
        <div class="me-5 mt-0 pt-6">
          <span class="w-100 fw-bolder text-end">
            <i class="text-gray-900">Life Is Nothing Without Rice</i>
          </span>
        </div>
      </div>
      <!--end::Header-->

      <!--begin::Body-->
      <div class="card-body py-15">
        <!--begin::Wrapper-->
        <div class="d-flex align-items-center p-5">
          <component
            :is="initForm"
            :data="initData"
            @update-form="updateForm"
          ></component>
        </div>
        <!--end::Wrapper-->
      </div>
      <!--begin::Body-->
    </div>
    <!--end::Card-->

    <div class="fw-bold text-center text-muted pt-10">
      You can visit
      <a
        href="#"
        :style="`color: ${
          themeMode == 'dark' ? '#fff' : '#000'
        }; text-decoration: none;`"
      >
        {{ themeName }}
      </a>
      for more information.
    </div>
  </div>
  <!--end::Content wrapper-->
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed, Component } from "vue";
import { useRouter, useRoute } from "vue-router";
import { themeMode } from "@/assets/template/layouts/default-layout/utils/helper";
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import ApiService from "@/assets/template/core/services/ApiService";
import MixinService from "@/assets/template/core/services/MixinService";

import Loading from "@/components/forms/approvals/Loading.vue";
import Confirm from "@/components/forms/approvals/Confirm.vue";
import Notification from "@/components/forms/approvals/Notification.vue";

export default defineComponent({
  name: "view-approval",
  components: {
    Loading,
    Confirm,
    Notification,
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const initForm = ref<Component>(Loading);
    const initData = ref(null);
    const initAction = ref("");

    onMounted(() => {
      try {
        initData.value = MixinService.decrypt(route.params.option);

        fetchApprovalData();
      } catch (error) {
        router.push("/error/404");
      }
    });

    const fetchApprovalData = () => {
      ApiService.get(
        `requests/${MixinService.encrypt(
          JSON.stringify({
            show: "filter",
            filter: {
              "ur.id": initData.value?.userRequestId,
              "ur.reference_id": initData.value?.referenceId,
            },
          })
        )}`
      )
        .then(({ data }) => {
          console.log(data);

          setTimeout(() => {
            if (data?.userRequests.length) {
              const approvals = data.userRequests[0].approvals.filter(
                (value) => value.userRequestId == initData.value?.userRequestId
              );

              if (initData.value?.action == "Wrong Approver") {
                initAction.value = initData.value?.action;
              } else if (data.userRequests[0]?.status == "Completed") {
                if (
                  approvals[approvals.length - 1]?.approverId ==
                  initData.value?.approverId
                ) {
                  initAction.value = "Completed";
                } else {
                  initAction.value = "Already Completed";
                }
              } else if (initData.value?.action == "Approved") {
                initAction.value = initData.value?.action;
              } else if (data.userRequests[0]?.status == "Rejected") {
                const rejectedApproval = approvals.filter(
                  (value) => value.statusCode == "RJCTD"
                );

                if (
                  rejectedApproval[0]?.approverId == initData.value?.approverId
                ) {
                  initAction.value = "Previously Rejected";
                } else {
                  initAction.value = "Already Rejected";
                }
              } else if (data.userRequests[0]?.status == "Cancelled") {
                initAction.value = "Already Cancelled";
              } else if (
                ["Confirm", "Reject"].includes(initData.value?.action) &&
                data.userRequests[0]?.status == "Submitted"
              ) {
                if (approvals.length) {
                  const pendingApprovals = approvals.filter(
                    (value) => value.statusCode == "PNDG"
                  );

                  if (pendingApprovals.length) {
                    if (
                      pendingApprovals[0]?.id == initData.value?.approvalId &&
                      pendingApprovals[0]?.approverId ==
                        initData.value?.approverId &&
                      pendingApprovals[0]?.hierarchy ==
                        initData.value?.hierarchy
                    ) {
                      if (
                        approvals[0]?.hierarchy == initData.value?.hierarchy
                      ) {
                        initAction.value = "Confirm";
                      } else {
                        if (
                          approvals[pendingApprovals[0]?.hierarchy - 1] ==
                          "Approved"
                        ) {
                          initAction.value = "Confirm";
                        } else {
                          initAction.value = "Wrong Approver";
                        }
                      }
                    } else {
                      initAction.value = "Wrong Approver";
                    }
                  }
                } else {
                  initAction.value = "Something Wrong";
                }
              } else if (
                initData.value?.action != "Confirm" &&
                data.userRequests[0]?.status == "Submitted"
              ) {
                initAction.value = "Confirm";
              }
            } else {
              initAction.value = "Approval Not Existing";
            }

            if (initAction.value == "Confirm") {
              router.push(
                `/approvals/confirm/${MixinService.encrypt(
                  JSON.stringify({
                    action: initAction.value,
                    approvalId: initData.value?.approvalId,
                    hierarchy: initData.value?.hierarchy,
                    userRequestId: initData.value?.userRequestId,
                    referenceId: initData.value?.referenceId,
                    approverId: initData.value?.approverId,
                  })
                )}`
              );
            } else {
              initForm.value = Notification;
            }
          }, 1000);
        })
        .catch((error) => {
          //error.code == ERR_BAD_REQUEST
          console.log(error);
        });
    };

    const updateForm = (form: Component) => {
      initForm.value = form;
    };

    const themeName = computed(() => {
      return import.meta.env.VITE_APP_NAME;
    });

    return {
      themeName,
      themeMode,
      getAssetPath,
      initForm,
      initData,
      initAction,
      updateForm,
    };
  },
});
</script>