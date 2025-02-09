<template>
  <!--begin::Stepper-->
  <div
    class="stepper stepper-links d-flex flex-column"
    id="kt_stepper"
    ref="initStepperRef"
  >
    <!--begin::Nav-->
    <div class="stepper-nav">
      <!--begin::Step-->
      <div v-for="(stepper, i) in initStepperTab" :key="i" :class="`stepper-item ${stepper?.current ? 'current' : '' }`" data-kt-stepper-element="nav">
        <span class="stepper-title fs-5">{{ stepper?.title }}</span>
      </div>
      <!--end::Step-->
    </div>
    <!--end::Nav-->

    <StepperForm
      :stepper-el="initStepperRef"
      :close-handler="props.closeHandler"
    >
      <template v-for="stepper in initStepperForm" v-slot:stepper_content>
        <component :is="stepper"></component>
      </template>
    </StepperForm>
  </div>
  <!--end::Stepper-->
</template>

<script setup lang="ts">
import { ref } from "vue";
import StepperForm from "@/assets/template/layouts/default-layout/components/stepper/StepperForm.vue";

interface Props {
  tab?: [];
  step?: [];
  closeHandler?: () => void;
};

const props = defineProps<Props>();
const initStepperRef = ref<HTMLElement | null>(null);
const initStepperTab = props.tab;
const initStepperForm = props.step;
</script>
