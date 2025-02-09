<template>
  <!--begin::Pos food-->
  <div class="card card-flush card-p-0 bg-transparent border-0">
    <!--begin::Body-->
    <div class="card-body">
      <!--begin::Nav-->
      <ul
        class="nav nav-pills d-flex justify-content-between nav-pills-custom gap-3 mb-6"
      >
        <!--begin::Item-->
        <li
          v-for="(category, index) in categories"
          :key="index"
          class="nav-item mb-3 me-0"
        >
          <!--begin::Nav link-->
          <a
            @click="setSelectedCategory(category.name)"
            class="nav-link nav-link-border-solid btn btn-outline btn-flex btn-active-color-primary flex-column flex-stack pt-9 pb-7 page-bg"
            data-bs-toggle="pill"
            style="width: 138px; height: 180px"
          >
            <!--begin::Icon-->
            <div class="nav-icon mb-3">
              <!--begin::Food icon-->
              <img src="" class="w-50px" alt="" />
              <!--end::Food icon-->
            </div>
            <!--end::Icon-->
            <!--begin::Info-->
            <div class="">
              <span class="text-gray-800 fw-bold fs-2 d-block">{{
                category.name
              }}</span>
              <span class="text-gray-500 fw-semibold fs-7"
                >{{ category.products.length }} Options</span
              >
            </div>
            <!--end::Info-->
          </a>
          <!--end::Nav link-->
        </li>
        <!--end::Item-->
      </ul>
      <!--end::Nav-->

      <div v-if="loading">Loading data...</div>

      <div v-else>
        <!--begin::Tab Content-->

        <div class="tab-content">
          <!--begin::Tap pane-->

          <div class="tab-pane fade show active">
            <!--begin::Wrapper-->
            <div class="d-flex flex-wrap d-grid gap-5 gap-xxl-9">
              <!--begin::Products-->
              <div v-for="(product, index) in sortedProducts" :key="index">
                <!--begin::Card-->
                <div class="card card-flush flex-row-fluid p-6 pb-5 mw-100">
                  <!--begin::Body-->
                  <div class="card-body text-center">
                    <!--begin::Food img-->
                    <img
                      src=""
                      class="rounded-3 mb-4 w-150px h-150px w-xxl-200px h-xxl-200px"
                      alt=""
                    />
                    <!--end::Food img-->
                    <!--begin::Info-->
                    <div class="mb-2">
                      <!--begin::Title-->
                      <div class="text-center">
                        <span
                          class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-3 fs-xl-1"
                          >{{ product.name }}</span
                        >
                        <span
                          class="text-gray-500 fw-semibold d-block fs-6 mt-n1"
                          >{{ product.description }}</span
                        >
                      </div>
                      <!--end::Title-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Total-->
                    <span class="text-success text-end fw-bold fs-1"
                      >$16.50$</span
                    >
                    <!--end::Total-->
                  </div>
                  <!--end::Body-->
                </div>
                <!--end::Card-->
              </div>
              <!--end::Products-->
            </div>
            <!--end::Wrapper-->
          </div>
          <!--end::Tap pane-->
        </div>
        <!--end::Tab Content-->
      </div>
      <!--end: Card Body-->
    </div>
    <!--end::Pos food-->
  </div>
</template>


<script lang="ts">
import { defineComponent, onMounted, ref, watch, computed } from "vue";

import type { IProduct } from "@/types/products";
import { MenuComponent } from "@/assets/template/ts/components";
export default defineComponent({
  name: "card-pos-agriposcard",
  components: {},
  props: {
    products: Array,
    categories: Array,
  },
  setup(props) {
    const sortedProducts = ref<Array<IProduct>>();
    const loading = ref<boolean>(true);

    watch(
      () => [props.products, props.categories],
      () => {
        MenuComponent.reinitialization();
        loading.value = !props.categories || !props.products;
      }
    );

    const setSelectedCategory = (category) => {
      sortedProducts.value = props.products.filter(
        (value) => value.category.name == category
      );
    };

    onMounted(() => {});

    return {
      sortedProducts,
      setSelectedCategory,
      loading,
    };
  },
});
</script>
  

