<template>
    <!--begin::Input group-->
    <div class="fv-row">
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div v-for="(button, i) in initButton" :key="i" :class="`col-sm-${button.column}`">
                <div v-if="Boolean(button.activeFlag)">
                  <!--begin::Option-->
                  <label
                      class="btn btn-hover-light-primary btn-active-light-primary btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-3"
                      :for="`kt_checkbox_${button.name}_${button.id}`"
                  >
                    <span class="form-check form-check-custom form-check-solid form-check-sm me-4">
                      <Field
                          type="checkbox"
                          :id="`kt_checkbox_${button.name}_${button.id}`"
                          :name="button.name"
                          :value="button.id"
                          class="form-check-input cursor-pointer"
                          v-model="initData"
                      />
                    </span>

                    <KTIcon :icon-name="button.icon" icon-class="fs-3x me-5" />

                    <!--begin::Info-->
                    <span class="d-block text-start">
                        <span class="text-gray-900 fw-semibold d-block fs-6 mb-2">
                            {{ button.description }}
                        </span>
                        <span class="text-gray-500 fw-normal fs-7">
                            {{ button.hintInfo }}
                        </span>
                    </span>
                    <!--end::Info-->
                  </label>
                  <!--end::Option-->
                </div>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Input group-->
</template>

<script setup lang="ts">
import { defineProps, defineEmits, ref, watch, computed, WritableComputedRef } from 'vue';
import { Field } from "vee-validate";

interface Props {
  data: "";
  button?: [];
};

const props = defineProps<Props>();
const emits = defineEmits(["update:data"]);

const initButton = ref(props.button);

const initData: WritableComputedRef<string> = computed({
  get(): string {
    return props.data;
  },
  set(value: string): void {
    emits("update:data", value);
  },
});

watch(
  () => props.button,
  (value) => {
    initButton.value = value;
  },
);
</script>