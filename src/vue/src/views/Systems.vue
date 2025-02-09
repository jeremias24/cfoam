<template>
    <!--begin::Row-->
    <div class="row g-6 g-xl-9">
        <!--begin::Col-->
        <div class="col-md-6 col-xl-3" v-for="(system, index) in initCardData" :key="index">
            <KTCard :url="system.url" :title="system.system" :userType="system.userType"
                :icon="getAssetPath('media/svg/brand-logos/plurk.svg')">
            </KTCard>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</template>
  
<script lang="ts">
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { MenuComponent } from "@/assets/template/ts/components";
import type { ISystem } from "@/types/systems";
import { defineComponent, onMounted, ref } from "vue";
import KTCard from "@/components/cards/view-system/SystemsActiveAccesses.vue";
import ApiService from "@/assets/template/core/services/ApiService";

export default defineComponent({
    name: "systems-listing",
    components: {
        KTCard,
    },
    setup() {


        const initCardData = ref<Array<ISystem>>([]);

        onMounted(() => {
            fetchData();

        });

        const fetchData = () => {
            ApiService.get("/systems/active-accesses")
                .then(({ data }) => {
                    initCardData.value = data.systems;
                }).then(() => {
                    MenuComponent.reinitialization();
                }).catch(error => {
                    console.log(error);
                });
        }

        return {
            initCardData,
            getAssetPath
        };
    },
});
</script>
  