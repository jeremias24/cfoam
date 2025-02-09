<template>
    <!--begin::Card-->
    <a :href="url" class="card border border-2 border-gray-300 border-hover">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-9">
            <!--begin::Card Title-->
            <div class="card-title m-0">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px w-50px bg-light">
                    <img :src="icon" alt="image" class="p-3" />
                </div>
                <!--end::Avatar-->
            </div>
            <!--end::Car Title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <span :class="getStatusDataBadgeColor" class="badge fw-bold me-auto px-4 py-3"> {{ userType }} </span>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end:: Card header-->

        <!--begin:: Card body-->
        <div class="card-body p-9">
            <!--begin::Name-->
            <div class="fs-3 fw-bold text-gray-900">
                {{ title }}
            </div>
            <!--end::Name-->
        </div>
        <!--end:: Card body-->
    </a>
    <!--end::Card-->
</template>
  
<script lang="ts">
import { computed, defineComponent } from "vue";

export default defineComponent({
    name: "systems-active-accesses",
    components: {},
    props: {
        statusDataBadgeColor: String,
        icon: String,
        title: String,
        userType: String,
        url: String,
        description: String,
    },
    setup(props) {
        const getStatusDataBadgeColor = computed(() => {
            return props.statusDataBadgeColor ? props.statusDataBadgeColor : "badge-light-success";
        });

        return {
            getStatusDataBadgeColor
        };
    },
});
</script>
  