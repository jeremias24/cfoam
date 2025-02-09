<template>
  <!--begin::Navbar-->
  <div class="app-navbar flex-shrink-0">
    <!--begin::Search-->
    <!-- <div class="app-navbar-item align-items-stretch ms-1 ms-md-4">
      <KTSearch />
    </div> -->
    <!--end::Search-->
    <!--begin::Activities-->
    <div class="app-navbar-item ms-1 ms-md-4 d-none">
      <!--begin::Drawer toggle-->
      <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
        id="kt_activities_toggle">
        <KTIcon icon-name="messages" icon-class="fs-2" />
      </div>
      <!--end::Drawer toggle-->
    </div>
    <!--end::Activities-->
    <!--begin::Notifications-->
    <div class="app-navbar-item ms-1 ms-md-4 d-none">
      <!--begin::Menu- wrapper-->
      <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
        <KTIcon icon-name="notification-status" icon-class="fs-2" />
      </div>
      <KTNotificationMenu />
      <!--end::Menu wrapper-->
    </div>
    <!--end::Notifications-->
    <!--begin::Chat-->
    <div class="app-navbar-item ms-1 ms-md-4">
      <!--begin::Menu wrapper-->
      <div
        class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative"
        id="kt_drawer_chat_toggle">
        <KTIcon icon-name="message-text-2" icon-class="fs-2" />
        <span
          class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
      </div>
      <!--end::Menu wrapper-->
    </div>
    <!--end::Chat-->
    <!--begin::Theme mode-->
    <div class="app-navbar-item ms-1 ms-md-3 d-none">
      <!--begin::Menu toggle-->
      <a href="#"
        class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
        data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end">
        <KTIcon v-if="themeMode === 'light'" icon-name="night-day" icon-class="fs-2" />
        <KTIcon v-else icon-name="moon" icon-class="fs-2" />
      </a>
      <!--begin::Menu toggle-->
      <KTThemeModeSwitcher />
    </div>
    <!--end::Theme mode-->
    <!--begin::User menu-->
    <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
      <!--begin::Menu wrapper-->
      <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
        data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        <img :src="getAssetPath('media/images/blank.png')"  class="rounded-3" alt="user" />
      </div>
      <KTUserMenu :data="userLogged" />
      <!--end::Menu wrapper-->
    </div>
    <!--end::User menu-->
    <!--begin::Header menu toggle-->
    <div class="app-navbar-item d-lg-none ms-2 me-n2" v-tooltip title="Show header menu">
      <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
        <KTIcon icon-name="element-4" icon-class="fs-2" />
      </div>
    </div>
    <!--end::Header menu toggle-->
  </div>
  <!--end::Navbar-->
</template>

<script lang="ts">
import { getAssetPath } from "@/assets/template/core/helpers/assets"
import { computed, defineComponent, ref } from "vue"
// import KTSearch from "@/assets/template/layouts/default-layout/components/search/Search.vue"
import KTNotificationMenu from "@/assets/template/layouts/default-layout/components/header/navbar/NotificationsMenu.vue"
import KTUserMenu from "@/assets/template/layouts/default-layout/components/header/navbar/UserAccountMenu.vue"
import KTThemeModeSwitcher from "@/assets/template/layouts/default-layout/components/header/navbar/ThemeModeSwitcher.vue"
import { ThemeModeComponent } from "@/assets/template/ts/layout"
import { useThemeStore } from "@/assets/template/stores/theme"

export default defineComponent({
  name: "header-navbar",
  components: {
    // KTSearch,
    KTNotificationMenu,
    KTUserMenu,
    KTThemeModeSwitcher,
  },
  props: {
    data: { type: Object, required: true, default: {} },
  },
  setup(props) {
    const store = useThemeStore()
    const userLogged = ref(props.data);

    const themeMode = computed(() => {
      if (store.mode === "system") {
        return ThemeModeComponent.getSystemMode()
      }
      return store.mode
    })

    return {
      themeMode,
      getAssetPath,
      userLogged,
    }
  },
})
</script>
