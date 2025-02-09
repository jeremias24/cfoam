<template>
    <div class="card card-flush shadow-sm border m-5">
        <div class="card-header">
            <span class="fw-bolder card-title fs-5">General Details</span>
        </div>
        <div class="card-body pt-0">

            <!--begin::Wrapper-->
            <div class="w-100 mb-5">
                <!--begin::Heading-->
                <div class="pb-5 pb-lg-5">
                    <!--begin::Title-->
                    <h2 class="required fw-bold d-flex align-items-center text-gray-900 fs-7">
                        Category
                    </h2>
                    <!--end::Title-->

                    <!--begin::Notice-->
                    <div class="text-gray-500 fs-7">
                        Choose the correct category based on your request.
                    </div>
                    <!--end::Notice-->
                </div>
                <!--end::Heading-->

                <!--begin::Group button-->
                <GroupButtonStatic :buttons="initCategoryGroupButtons">
                    <template v-slot:error_message>
                        <ErrorMessage name="sub-category" class="fv-plugins-message-container invalid-feedback">
                        </ErrorMessage>
                    </template>
                </GroupButtonStatic>
                <!--end::Group button-->

            </div>
            <!--end::Wrapper-->

            <!--begin::Wrapper-->
            <div class="w-100">
                <!--begin::Heading-->
                <div class="pb-5 pb-lg-5">
                    <!--begin::Title-->
                    <h2 class="required fw-bold d-flex align-items-center text-gray-900 fs-7">
                        Sub-Category
                    </h2>
                    <!--end::Title-->

                    <!--begin::Notice-->
                    <div class="text-gray-500 fs-7">
                        Choose the correct sub-category based on your request.
                    </div>
                    <!--end::Notice-->
                </div>
                <!--end::Heading-->

                <!--begin::Group button-->
                <GroupButtonStatic :buttons="initSubCategoryGroupButtons">
                    <template v-slot:error_message>
                        <ErrorMessage name="sub-category" class="fv-plugins-message-container invalid-feedback">
                        </ErrorMessage>
                    </template>
                </GroupButtonStatic>
                <!--end::Group button-->

            </div>
            <!--end::Wrapper-->

        </div>
    </div>
</template>

<script setup lang="ts">
import { ErrorMessage, Field } from "vee-validate";
import GroupButtonStatic from "@/assets/template/layouts/default-layout/components/extras/controls/button/GroupButtonStatic.vue";

const initCategoryGroupButtons = [
    {
        column: 12,
        name: "category",
        id: "web_based_system",
        label: "Web-Based System",
        value: "Web-Based System",
        info: "Any in-house web-based system, including new developments, enhancements, modifications, additional system reports, as well as reporting system errors and encountered bugs.",
        icon: "code",
    },
    {
        column: 12,
        name: "category",
        id: "oracle",
        label: "Oracle",
        value: "Oracle",
        info: "Any Oracle related issues such as application and database-related problems. implementing new modules, upgrading database or migrating applications, demands meticulous attention to detail.",
        icon: "cloud",
    },
    {
        column: 12,
        name: "category",
        id: "infrastructure_technical_operation",
        label: "Infrastructure / Technical Operation",
        value: "Infrastructure / Technical Operation",
        info: "Any network upgrades, server installations, or software migrations, email-related problem, equipment loan and any information technology Infrastructure related activity.",
        icon: "router",
    },
];

const initSubCategoryGroupButtons = [
    {
        column: 4,
        name: "sub-category",
        id: "support_service",
        label: "Support Service",
        value: "Support Service",
        info: "Seeking for assistance with specific issues or problems.",
        icon: "wrench",
    },
    {
        column: 4,
        name: "sub-category",
        id: "proejct_based",
        label: "Project Based",
        value: "Project Based",
        info: "A task with defined timeframes and objectives.",
        icon: "square-brackets",
    },
    {
        column: 4,
        name: "sub-category",
        id: "user_access",
        label: "User Access",
        value: "User Access",
        info: "Requesting to access a specific system or platform.",
        icon: "key",
    },
]
</script>
  