import { reference } from '@popperjs/core';
<template>
    <!--begin::Form-->
    <Form id="kt_form_completed" class="form w-100">
        <!--begin::Alert-->
        <div class="d-flex align-items-center p-5">
            <!--begin::Icon-->
            <i :class="`ki-duotone ki-${initIcons[initIcon]} fs-6x me-2`">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <!--end::Icon-->

            <!--begin::Wrapper-->
            <div class="d-flex flex-column">
                <!--begin::Title-->
                <h1 class="my-5 text-gray-900 text-center">
                    <div v-show="getIcon == 'success' || getIcon == 'info'"><span class="fw-bolder">{{ getReferenceId
                            }}</span> {{ getNotificationContent }}.</div>
                    <div v-show="getIcon == 'warning'">{{ getNotificationContent }} <span class="fw-bolder">{{
                            getReferenceId }}</span>.</div>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Alert-->
    </Form>
    <!--end::Form-->
</template>

<script lang="ts">
import { defineComponent, ref, computed } from "vue";

export default defineComponent({
    name: "form-verification-notification",
    props: {
        data: { type: Object, required: true },
    },
    setup(props) {

        const initIcon = ref("");
        const initIcons = ref({
            success: "check-circle text-success",
            danger: "cross-circle text-danger",
            info: "information-2 text-info",
            warning: "information-5 text-warning",
        });

        const getIcon = computed(() => {
            return initIcon.value
        });

        const getAction = computed(() => {
            return props.data?.action;
        });

        const getReferenceId = computed(() => {
            return props.data?.referenceId;
        });

        const getNotificationContent = computed(() => {

            if (props?.data.status == "Verified") {
                initIcon.value = "success";
                return "has been verified";
            } else if(props?.data.status == "Already Verified") {
                initIcon.value = "info";
                return "has been already verified";
            } else if (props.data?.status == "Something Wrong") {
                initIcon.value = "warning";
                return "There's something wrong processing of your approval for this";
            }
        });

        return {
            initIcon,
            initIcons,
            getIcon,
            getAction,
            getReferenceId,
            getNotificationContent,
        };
    },
});
</script>
