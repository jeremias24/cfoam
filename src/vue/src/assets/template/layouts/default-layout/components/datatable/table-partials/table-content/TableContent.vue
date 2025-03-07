<template>
  <div class="table-responsive border border-gray-300">
    <table
      :class="[loading && 'overlay overlay-block']"
      class="table align-middle table-row-dashed table-row-gray-300 table-hover fs-7 gy-2 dataTable no-footer"
    >
      <TableHeadRow
        @onSort="onSort"
        @onSelect="selectAll"
        :checkboxEnabledValue="check"
        :checkboxEnabled="checkboxEnabled"
        :rowNumberingEnabled="rowNumberingEnabled"
        :actionsEnabled="actionsEnabled"
        :sort-label="sortLabel"
        :sort-order="sortOrder"
        :header="header"
      />
      <TableBodyRow
        v-if="data.length !== 0"
        @onSelect="itemsSelect"
        :currentlySelectedItems="selectedItems"
        :data="data"
        :header="header"
        :checkbox-enabled="checkboxEnabled"
        :checkbox-label="checkboxLabel"
        :rowNumberingEnabled="rowNumberingEnabled"
        :active-page="activePage"
        :item-per-page="itemPerPage"
      >
        <template v-for="(_, name) in $slots" v-slot:[name]="{ row: item }">
          <slot :name="name" :row="item" />
        </template>
      </TableBodyRow>
      <template v-else>
        <tr class="odd">
          <td colspan="7" class="dataTables_empty fs-7 text-center">
            {{ emptyTableText }}
          </td>
        </tr>
      </template>
      <Loading v-if="loading" />
    </table>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, watch } from "vue";
import TableHeadRow from "@/assets/template/layouts/default-layout/components/datatable/table-partials/table-content/table-head/TableHeadRow.vue";
import TableBodyRow from "@/assets/template/layouts/default-layout/components/datatable/table-partials/table-content/table-body/TableBodyRow.vue";
import Loading from "@/assets/template/layouts/default-layout/components/datatable/table-partials/Loading.vue";
import type { Sort } from "@/assets/template/layouts/default-layout/components/datatable/table-partials/models";

export default defineComponent({
  name: "table-body",
  props: {
    header: { type: Array, required: true },
    data: { type: Array, required: true },
    emptyTableText: { type: String, default: "No data found" },
    sortLabel: { type: String, required: false, default: null },
    sortOrder: {
      type: String as () => "asc" | "desc",
      required: false,
      default: "asc",
    },
    checkboxEnabled: { type: Boolean, required: false, default: false },
    checkboxLabel: { type: String, required: false, default: "id" },
    rowNumberingEnabled: { type: Boolean, required: false, default: false },
    actionsEnabled: { type: Boolean, required: false, default: false },
    loading: { type: Boolean, required: false, default: false },
    activePage: { type: Number, required: false },
    itemPerPage: { type: Number, required: false },
  },
  emits: ["on-sort", "on-items-select"],
  components: {
    TableHeadRow,
    TableBodyRow,
    Loading,
  },
  setup(props, { emit }) {
    const selectedItems = ref<Array<unknown>>([]);
    const allSelectedItems = ref<Array<unknown>>([]);
    const activePage = ref<number>();
    const itemPerPage = ref<number>();
    const check = ref<boolean>(false);

    watch(
      () => props.data,
      () => {
        selectedItems.value = [];
        allSelectedItems.value = [];
        check.value = false;
        // eslint-disable-next-line
        props.data.forEach((item: any) => {
          if (item[props.checkboxLabel]) {
            allSelectedItems.value.push(item[props.checkboxLabel]);
          }
        });
      }
    );

    watch(
      () => props.activePage,
      () => {
        activePage.value = props.activePage;
      }
    );

    watch(
      () => props.itemPerPage,
      () => {
        itemPerPage.value = props.itemPerPage;
      }
    );

    // eslint-disable-next-line
    const selectAll = (checked: any) => {
      check.value = checked;
      if (checked) {
        selectedItems.value = [
          ...new Set([...selectedItems.value, ...allSelectedItems.value]),
        ];
      } else {
        selectedItems.value = [];
      }
    };

    //eslint-disable-next-line
    const itemsSelect = (value: any) => {
      selectedItems.value = [];
      //eslint-disable-next-line
      value.forEach((item:any) => {
        if (!selectedItems.value.includes(item)) selectedItems.value.push(item);
      });
    };

    const onSort = (sort: Sort) => {
      emit("on-sort", sort);
    };

    watch(
      () => [...selectedItems.value],
      (currentValue) => {
        if (currentValue) {
          emit("on-items-select", currentValue);
        }
      }
    );

    onMounted(() => {
      selectedItems.value = [];
      allSelectedItems.value = [];
      check.value = false;
      // eslint-disable-next-line
      props.data.forEach((item: any) => {
        if (item[props.checkboxLabel]) {
          allSelectedItems.value.push(item[props.checkboxLabel]);
        }
      });
    });

    return {
      onSort,
      selectedItems,
      selectAll,
      itemsSelect,
      check,
      activePage,
      itemPerPage,
    };
  },
});
</script>
