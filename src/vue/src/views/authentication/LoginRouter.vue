<template>
  <!--begin::Content wrapper-->
  <div class="d-flex flex-column flex-center p-10">
    <!--begin::Card-->
    <div id="kt_card_verify" class="card card-flush w-md-650px pt-5">
      <!--begin::Header-->
      <div
        :style="`border-bottom: 5px solid ${
          themeMode == 'dark' ? '#1fd655' : '#0000FF'
        };`"
        class="d-flex flex-stack align-items-center flex-wrap"
      >
        <!--begin::Logo-->
        <div class="ms-5 mt-0">
          <img
            v-if="themeMode == 'light' || themeMode == 'system'"
            alt="Logo"
            :src="getAssetPath('media/logos/crgmpc.png')"
            class="h-50px"
          />
          <img
            v-if="themeMode == 'dark'"
            alt="Logo"
            :src="getAssetPath('media/logos/crgmpc.png')"
            class="h-50px"
          />
        </div>
        <!--end::Logo-->
        <div class="me-5 mt-0 pt-6">
          <span class="w-100 fw-bolder text-end">
            <strong class="text-gray-900"
              >Intelligent Learning Management System | CAMiVLE</strong
            >
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
import Loading from "@/views/authentication/Loading.vue";
import { useAuthStore, type User } from "@/assets/template/stores/auth";
import Notification from "@/components/forms/verifications/Notification.vue";

export default defineComponent({
  name: "view-approval",
  components: {
    Loading,
    Notification,
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const initForm = ref<Component>(Loading);
    const initData = ref({});
    const initAction = ref("");
    const store = useAuthStore();

    onMounted(() => {
      try {
        initData.value = MixinService.decrypt(route.params.username);
        verifyUserCredentials(initData.value);
      } catch (error) {
        console.log(error);

        //  router.push("/error/404");
      }
    });
    const verifyUserCredentials = async (data: any) => {
      ApiService.get(`login/${MixinService.encrypt(data)}`)
        .then(({ data }) => {
          setTimeout(() => {
            console.log(data.user);

            if (data.user) {
              store.setAuth(data.user);
              router.push({ name: "Dashboard" });
            } else {
              router.push({ name: "404" });
            }
          }, 1000);
        })
        .catch(({ error }) => {
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