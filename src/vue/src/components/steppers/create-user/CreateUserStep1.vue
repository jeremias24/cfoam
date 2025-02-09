<template>
    <!--begin::Step 1-->
    <div class="current" data-kt-stepper-element="content">
      <!--begin::Wrapper-->
      <div class="w-100">
        <!--begin::Heading-->
        <div class="pb-5 pb-lg-5">
          <!--begin::Title-->
          <h2 class="fw-bold d-flex align-items-center text-gray-900 fs-5">
            Choose Project Type
          </h2>
          <!--end::Title-->
  
          <!--begin::Notice-->
          <div class="text-gray-500 fw-semibold fs-7">
            Choose the correct project type based on your request.
          </div>
          <!--end::Notice-->
        </div>
        <!--end::Heading-->
  
        <!--begin::Input group-->
        <div class="fv-row">
          <!--begin::Row-->
          <div class="row">
            <!--begin::Col-->
            <div class="col-lg-6">
              <!--begin::Option-->
              <Field
                type="radio"
                class="btn-check"
                name="accountType"
                value="personal"
                id="kt_create_account_form_account_type_personal"
              />
              <label
                class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-3"
                for="kt_create_account_form_account_type_personal"
              >
                <KTIcon icon-name="code" icon-class="fs-3x me-5" />
  
                <!--begin::Info-->
                <span class="d-block fw-semibold text-start">
                  <span class="text-gray-900 fw-bold d-block fs-5 mb-2">
                    Software Development
                  </span>
                  <span class="text-gray-500 fw-semibold fs-7">
                    Any new in-house software or system development, enhancements, or modifications, as well as additional system reports related.
                  </span>
                </span>
                <!--end::Info-->
              </label>
              <!--end::Option-->
            </div>
            <!--end::Col-->
  
            <!--begin::Col-->
            <div class="col-lg-6">
              <!--begin::Option-->
              <Field
                type="radio"
                class="btn-check"
                name="accountType"
                value="corporate"
                id="kt_create_account_form_account_type_corporate"
              />
              <label
                class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-3"
                for="kt_create_account_form_account_type_corporate"
              >
                <KTIcon icon-name="cloud-change" icon-class="fs-3x me-5" />
  
                <!--begin::Info-->
                <span class="d-block fw-semibold text-start">
                  <span class="text-gray-900 fw-bold d-block fs-5 mb-2">
                    Oracle
                  </span>
                  <span class="text-gray-500 fw-semibold fs-7">
                    Any Oracle new or upgrade, including application and database-related projects, such as implementing new modules, upgrading database versions, or migrating applications, demands meticulous attention to detail.
                  </span>
                </span>
                <!--end::Info-->
              </label>
              <!--end::Option-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-lg-6">
              <!--begin::Option-->
              <Field
                type="radio"
                class="btn-check"
                name="accountType"
                value="corporate"
                id="kt_create_account_form_account_type_corporate1"
              />
              <label
                class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center"
                for="kt_create_account_form_account_type_corporate1"
              >
                <KTIcon icon-name="router" icon-class="fs-3x me-5" />
  
                <!--begin::Info-->
                <span class="d-block fw-semibold text-start">
                  <span class="text-gray-900 fw-bold d-block fs-5 mb-2">
                    Infrastructure
                  </span>
                  <span class="text-gray-500 fw-semibold fs-7">
                    Any network upgrades, server installations, or software migrations and any information technology Infrastructure related activity.
                  </span>
                </span>
                <!--end::Info-->
              </label>
              <!--end::Option-->
            </div>
            <!--end::Col-->
  
            <ErrorMessage
              name="accountType"
              class="fv-plugins-message-container invalid-feedback"
            ></ErrorMessage>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Input group-->
      </div>
      <!--end::Wrapper-->
    </div>
    <!--end::Step 1-->
  </template>
  
  <script setup lang="ts">
  import { ErrorMessage, Field } from "vee-validate";
  </script>
  