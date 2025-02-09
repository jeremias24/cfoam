<template>
  <tbody class="text-gray-900">
    <template v-for="(row, i) in data" :key="i">
      <tr>
        <td v-if="checkboxEnabled" class="bg-light">
          <div
            class="form-check form-check-sm form-check-custom form-check-solid ps-3"
          >
            <input
              class="form-check-input border border-gray-400 cursor-pointer"
              type="checkbox"
              :value="row[checkboxLabel]"
              v-model="selectedItems"
              @change="onChange"
            />
          </div>
        </td>
        <td v-if="rowNumberingEnabled" class="bg-light">
          {{ i + (activePage * itemPerPage) + 1 }}
        </td>
        <template v-for="(properties, j) in header" :key="j">
          <td :class="`${(j === header.length - 1) ? 'border-start border-gray-300 text-center' : ''}`">
            <slot :name="`${properties.columnLabel}`" :row="row">
              {{ row }}
            </slot>
          </td>
        </template>
      </tr>
    </template>
  </tbody>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";

export default defineComponent({
  name: "table-body-row",
  components: {},
  props: {
    header: { type: Array as () => Array<any>, required: true },
    data: { type: Array as () => Array<any>, required: true },
    currentlySelectedItems: { type: Array, required: false, default: () => [] },
    checkboxEnabled: { type: Boolean, required: false, default: false },
    checkboxLabel: {
      type: String as () => string,
      required: false,
      default: "id",
    },
    rowNumberingEnabled: { type: Boolean, required: false, default: false },
    activePage: { type: Number, required: false },
    itemPerPage: { type: Number, required: false },
  },
  emits: ["on-select"],
  setup(props, { emit }) {
    const selectedItems = ref<Array<any>>([]);
    const activePage = ref<number>(0);
    const itemPerPage = ref<number>(25);

    watch(
      () => [...props.currentlySelectedItems],
      (currentValue) => {
        if (props.currentlySelectedItems.length !== 0) {
          selectedItems.value = [
            ...new Set([...selectedItems.value, ...currentValue]),
          ];
        } else {
          selectedItems.value = [];
        }
      }
    );

    watch(
      () => props.activePage,
      () => {
        activePage.value = props.activePage - 1;
      }
    );

    watch(
      () => props.itemPerPage,
      () => {
        itemPerPage.value = props.itemPerPage;
      }
    );

    const onChange = () => {
      emit("on-select", selectedItems.value);
    };

    return {
      selectedItems,
      onChange,
      activePage,
      itemPerPage,
    };
  },
});
</script>
