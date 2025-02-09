<template>
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-center p-10">
        <!--begin::Card-->
        <div id="kt_card_approval" class="card card-flush w-md-650px pt-5">

            <!--begin::Header-->
            <div :style="`border-bottom: 5px solid ${themeMode == 'dark' ? '#fd0d1b' : '#000'};`"
                class="d-flex flex-stack align-items-center flex-wrap">
                <!--begin::Logo-->
                <div class="ms-5 mt-0">
                    <img v-if="themeMode == 'light' || themeMode == 'system'" alt="Logo"
                        :src="getAssetPath('media/logos/crgmpc.png')" class="h-25px" />
                    <img v-if="themeMode == 'dark'" alt="Logo" :src="getAssetPath('media/logos/crgmpc.png')"
                        class="h-25px" />
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

                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column pb-5">
                        <!--begin::Title-->
                        <span class="mb-0 lh-1 text-gray-600 fw-bolder text-center fs-7x">
                            404
                        </span>
                        <span class="mb-10 text-gray-600 fw-bolder text-center fs-1">
                            Page not found
                        </span>
                        <span class="mb-0 text-gray-600 fw-bolder fs-2">
                            Something is wrong
                        </span>
                        The page you are looking for was moved, removed, renamed, broken or might never existed.
                        <!--end::Title-->
                    </div>
                    <!--end::Wrapper-->

                </div>
                <!--end::Wrapper-->
            </div>
            <!--begin::Body-->
        </div>
        <!--end::Card-->

        <div class="fw-bold text-center text-muted pt-10">
            Go Home to
            <a href="#"
                :style="`color: ${themeMode == 'dark' ? '#fff' : '#000'}; text-decoration: none;`">
                {{ themeName }}
            </a>
        </div>

    </div>
    <!--end::Content wrapper-->
</template>

<script lang="ts">
import { defineComponent, computed } from "vue";
import { themeMode } from "@/assets/template/layouts/default-layout/utils/helper";
import { getAssetPath } from "@/assets/template/core/helpers/assets";

export default defineComponent({
    name: "view-error-404",
    setup() {
        const themeName = computed(() => {
            return import.meta.env.VITE_APP_NAME;
        });

        return {
            themeName,
            themeMode,
            getAssetPath,
        };
    },
});
</script>