<template>
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            <KTContent></KTContent>

        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->
</template>

<script lang="ts">
import { defineComponent, nextTick, onBeforeMount, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { reinitializeComponents } from "@/assets/template/core/plugins/keenthemes";
import LayoutService from "@/assets/template/core/services/LayoutService";

import KTContent from "@/assets/template/layouts/default-layout/components/content/Content.vue";

export default defineComponent({
    name: "plain-layout",
    components: {
        KTContent,
    },
    setup() {
        const route = useRoute();

        onBeforeMount(() => {
            LayoutService.init();
        });

        onMounted(() => {
            nextTick(() => {
                reinitializeComponents();
            });
        });

        watch(
            () => route.path,
            () => {
                nextTick(() => {
                    reinitializeComponents();
                });
            }
        );
    },
});
</script>