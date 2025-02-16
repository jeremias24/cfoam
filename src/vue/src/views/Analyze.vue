<template>
  <div :class="`${initLoading ? 'overlay overlay-block' : ''}`">
    <div class="card mb-5 mt-0">
      <div class="card-header border-0 py-0">
        <!--begin::Card title-->
        <!--begin::Wrapper-->
        <div class="w-100 mb-2 mt-5">
          <!--begin::Heading-->
          <div class="pb-3 pb-lg-3">
            <!--begin::Title-->
            <h2 class="required fw-semibold d-flex align-items-center text-gray-900 fs-7">
              ANALYSIS OPTION
            </h2>
            <!--end::Title-->

            <!--begin::Notice-->
            <div class="text-gray-500 fs-7">
              Choose the correct analysis option based on your required.
            </div>
            <!--end::Notice-->
          </div>
          <!--end::Heading-->

          <!--begin::Group button-->
          <GroupButtonRadio
            v-model:data="initFormData.cameraId"
            :button="initCameraGroupButton"
          />
          <!--end::Group button-->       
        </div>
        <!--end::Wrapper-->
        <!--end::Card title-->
      </div>
    </div>

    <div v-if="isCameraActive || isUploadActive">
      <div class="card" >
          <!--begin::Col-->
          <div class="col-xl-12 mb-xl-5 px-0 pb-0">
            <div class="card-body py-0">
              <div class="row gx-6 gy-3">
                <div class="col-xl-6">
                  <div class="mt-5">
                    <div class="card-body py-0 px-0">
                      <div v-if="isCameraActive">
                        <!-- begin::Camera Stream -->
                        <video class="col-xl-6" ref="video" style="width: 100%; height: 100%" autoplay></video>
                        <!-- end::Camera Stream -->
                      </div>

                      <div v-if="isUploadActive">
                        <!--begin::Primary button-->
                        <Upload
                          v-model:formData="initFormData.images"
                          form-key="images"
                          container-path="storage/upload/images"
                          max-filesize="10"
                          button-label="Attach Images/s"
                          button-icon="picture"
                          :filesize-accepted="10"
                          :car-parts="initCarParts"
                        >
                        </Upload>
                        <!--end::Primary button-->

                        <!-- v-if="imagesUploadCount > 0" -->
          
                        <button @click="submit()" type="submit"
                          :data-kt-indicator="initLoading ? 'on' : null"
                          class="btn btn-lg fw-bold btn-primary"
                        > 
                          <span v-if="!initLoading" class="indicator-label">
                            <KTIcon icon-name="send" icon-class="fs-5 ms-2 me-0" />  Submit
                          </span>

                          <span v-if="initLoading" class="indicator-progress">
                            In Progress...
                            <span
                              class="spinner-border spinner-border-sm align-middle ms-2"
                            >
                            </span>
                          </span>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-6 mb-5">
                  <div class="mt-5">
                    <div class="card-body py-5 px-5">
                      <div class="col-xl-12 text-center">
                        <VueApexCharts v-if="isAnalyzed" type="pie" :options="chartOptions" :series="series" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--end::Col-->
      </div>
    </div>

    <!--begin::Overlay Layer-->
    <div
      v-show="initLoading"
      class="overlay-layer bg-dark bg-opacity-5"
    >
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      
    </div>
    <!--end::Overlay Layer-->

  </div>
</template>

<script lang="ts">
import {  defineComponent,
  defineExpose,
  ref,
  onMounted,
  watch,
  computed,
  type WritableComputedRef } from "vue";
import VueWebCam from "vue-web-cam";
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { MenuComponent } from "@/assets/template/ts/components";
import ApiService from "@/assets/template/core/services/ApiService";
import { useAuthStore } from "@/assets/template/stores/auth";
import MixinService from "@/assets/template/core/services/MixinService";
import GroupButtonRadio from "@/assets/template/layouts/default-layout/components/extras/controls/button/GroupButtonRadio.vue";
import Upload from "@/assets/template/layouts/default-layout/components/extras/controls/upload/Upload.vue";
import { IAnalysis } from "@/types/analysis";
import { ICarParts } from "@/types/carParts";
import VueApexCharts from "vue3-apexcharts";

export default defineComponent({
  name: "analyze",
  components: {
    VueWebCam,
    GroupButtonRadio,
    Upload,
    VueApexCharts,
  },
  setup() {
    /** Default Setup */
    const authStore = useAuthStore();
    
    const initLoading = ref<boolean>(false);
    const isAnalyzed = ref<boolean>(false);
    const initCameraGroupButton = ref([]);
    const initDefaultFormData = {
      id: null,
      images: []
    };

    const initValidationError = ref({
      valid: false,
      id: "",
      error: "",
    });

    const initFormData = ref<IAnalysis>({ ...initDefaultFormData });
    const initFormRules = ref();
    const initCarParts = ref<ICarParts>();

    const initFormDefaultRules = ref({
      images: [
        {
          required: true,
          message: "Attach Image/s field is required.",
          type: "images"
        },
      ],
    });

    const video = ref<HTMLVideoElement | null>(null);
    const isCameraActive = ref(false);
    const isUploadActive = ref(false);

    const cameras = ref([
      {
        id: 1,
        icon: "bi bi-camera-fill", 
        description: "Start Camera",
        hintInfo: "Capture a real-time image",
        activeFlag: 1
      },
      {
        id: 2,
        icon: "picture",
        description: "Upload Image",
        hintInfo: "Upload an images for analysis.",
        activeFlag: 1
      }
    ]);
    
    const chartOptions = ref({
      chart: {
        type: "pie",
        width: "100%",
      },
      labels: [], 
      legend: {
        position: "bottom",
      },
      tooltip: {
        y: {
          formatter: (val: number) => `${val}`,
        },
      },
    });

    const series = ref<number[]>([]);
    const imagesUploadCount = ref<number>(0);

    const getUser = computed(() => {
      return authStore?.getUser();
    });

    onMounted(() => {
      initCameraGroupButton.value = cameras.value.map(
        (value) => ({ ...value, column: 6, name: "camera" })
      );

      getCarParts();
    });

    watch(
      () => initFormData.value.cameraId,
      (value) => {
        if (value == 1) {
          isCameraActive.value = true;
          startCamera();
          isUploadActive.value = false;
          isAnalyzed.value = false;
        } else {
          isCameraActive.value = false;
          endCamera();
          isUploadActive.value = true;
          isAnalyzed.value = false;
        }
      }
    );

    watch(
      () => initFormData.value.images,
      (value) => {
        imagesUploadCount.value = value.images.length;
      },
      { deep: true }
    );

    const startCamera = async () => {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        if (video.value) {
          video.value.srcObject = stream;
        }
      } catch (error) {

        MixinService.showAlert(
          `Error accessing camera.`
        );
        console.error("Error accessing camera: ", error);
      }
    };

    const endCamera = async () => {
       try {
        if (video.value && video.value.srcObject) {
          const stream = video.value.srcObject as MediaStream;
          stream.getTracks().forEach((track) => track.stop()); // Stop all tracks
          video.value.srcObject = null; // Clear the video element's source
        }
      } catch (error) {
        MixinService.showAlert(`Error stopping the camera.`);
        console.error("Error stopping camera: ", error);
      }
    };

    const submit = () => {
      initFormRules.value = initFormDefaultRules.value;

      initValidationError.value = MixinService.validateForm(
        initFormData.value,
        initFormRules.value
      );

      console.log(initFormData.value);

      if (initValidationError.value.valid) {

        MixinService.showConfirmAlert(
          "Are you sure you want to submit?",
          "Submit",
          () => {
            initLoading.value = true;
  
            ApiService.aiPost(
              "analysis",
              MixinService.serialize(initFormData.value), {
                headers: { "Content-Type": "multipart/form-data" },
              }
            )
            .then(({ data }) => {
              console.log(data);
              
              if (data?.defects) {
                const defects = data.defects;
                chartOptions.value = {
                  ...chartOptions.value,
                  labels: Object.keys(defects).map(
                    (label) => label.charAt(0).toUpperCase() + label.slice(1)
                  ), // Capitalized defect names
                };

                isAnalyzed.value = true;

                series.value = Object.values(defects); 
              } 
            })
            .catch(({ error }) => {
              console.log(error);
            })
            .finally(() => {
              initLoading.value = false;
            });
          }
        );
      }
    };

    const getCarParts = () => {
      ApiService.get("car-parts")
      .then(({ data }) => {
        if (data?.carParts) {
          initCarParts.value = data.carParts;
        } 
      })
      .catch(({ error }) => {
        console.log(error);
      })
    };

    return {
      initFormData,
      initCameraGroupButton,
      initCarParts,
      getAssetPath,
      getUser,
      video,
      startCamera,
      isCameraActive,
      isUploadActive,
      submit,
      initLoading,
      chartOptions,
      series,
      isAnalyzed,
      imagesUploadCount
    };
  },
});
</script>