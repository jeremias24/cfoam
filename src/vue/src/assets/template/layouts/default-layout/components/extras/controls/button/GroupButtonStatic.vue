<template>
    <!--begin::Input group-->
    <div class="fv-row">
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div v-for="(button, i) in initButtons" :key="i" :class="`col-sm-${button.column}`">
                <!--begin::Option-->
                <Field type="radio" :id="`kt_radio_${button.id}`" :name="button.name" :value="button.value"
                    v-model="initData" class="btn-check" />
                <label
                    class="btn btn-hover-light-primary btn-active-light-primary btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-3"
                    :class="`${button.disabled == true ? 'disabled' : ''}`"
                    :for="`kt_radio_${button.id}`">
                    <KTIcon :icon-name="button.icon" icon-class="fs-3x me-5" />

                    <!--begin::Info-->
                    <span class="d-block fw-semibold text-start">
                        <span class="text-gray-900 fw-bold d-block fs-6 mb-2">
                            {{ button.label }}
                        </span>
                        <span class="text-gray-500 fs-7">
                            {{ button.info }}
                        </span>
                    </span>
                    <!--end::Info-->
                </label>
                <!--end::Option-->
            </div>
            <!--end::Col-->

            <slot name="error_message"></slot>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Input group-->
</template>

<script setup lang="ts">
import {
    computed,
    defineEmits,
    WritableComputedRef,
    watch,
    ref
} from "vue";
import { Field } from "vee-validate";
interface Props {
    data: "";
    buttons?: [];
};

const props = defineProps<Props>();
const initButtons = ref(props.buttons);


const emits = defineEmits(["update:data"]);

const initData: WritableComputedRef<string> = computed({
    get(): string {
        return props.data;
    },
    set(value: string): void {
        emits("update:data", value);
    },
});

watch(
    () => props.buttons,
    (val) => {
        initButtons.value = val;
    },
);

</script>