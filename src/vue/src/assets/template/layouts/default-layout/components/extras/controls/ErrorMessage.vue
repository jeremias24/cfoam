<template>
    <div v-show="!initError.valid && initName == initError.id" :name="initName"
        :class="`fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback mt-0 ${initClass}`">
        <div>{{ initError.error }}</div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";

export default defineComponent({
    name: "control-error-message",
    props: {
        name: { type: String, required: true },
        data: { type: String, required: true },
        error: { type: Object, required: false, default: {} },
        class: { type: String, required: false, default: "mb-5" }
    },
    setup(props) {
        const initName = ref(props.name);
        const initError = ref(props.error);
        const initClass = ref(props.class);
        watch(
            () => props.data,
            (val) => {
                switch (initError.value?.type) {
                    case "required":
                        if (val !== "undefined" && val !== "" && val !== null) {
                            initError.value.valid = true;
                        }
                        break;
                }
            }
        );

        watch(
            () => props.error,
            (val) => {
                initError.value = val;
            }
        );

        return {
            initName,
            initError,
            initClass,
        };
    },
});
</script>