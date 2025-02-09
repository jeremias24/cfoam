<template>
  <div v-show="title == 'New User'" class="m-5">
    <span class="fw-bold fs-7"
      >Note: Check email and username availability before submitting
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";

export default defineComponent({
  name: "note-card",
  components: {},
  props: {
    title: { type: String, required: true, default: "" },
  },
  setup(props) {
    const title = ref(props.title);

    watch(
      () => props.title,
      (curr, prev) => {
        title.value = curr;
      }
    );

    return {
      title,
    };
  },
});
</script>