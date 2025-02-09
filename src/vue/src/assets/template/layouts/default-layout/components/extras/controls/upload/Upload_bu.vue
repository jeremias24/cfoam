
<template>
    <!--begin::Row-->
    <div class="row">
        <!--begin::Upload-->
        <div class="mb-2">
            <!--begin::Controls-->
            <div class="mb-1">
                <input type="file" id="kt_file_upload" @change="uploadFile" class="d-none" />
                <label for="kt_file_upload" class="btn btn-lg btn-primary fw-bold me-2">
                    <KTIcon :icon-name="initButtonIcon" icon-class="fs-3 me-1" />
                    {{ initButtonLabel }}
                </label>
            </div>
            <!--end::Controls-->

            <!--begin::Items-->
            <div v-for="(file, i) in initFiles" class="bg-light wm-200px p-3 mt-3 border border-gray-300 border-rounded cursor-default h-40px" style="border-radius: 10px;">
                <div class="d-flex flex-stack align-items-center flex-wrap">
                    <!--begin::File-->
                    <div>
                        <div class="text-primary fs-7">
                            <span class="fw-semibold">{{ file.filename }}</span>
                            <span class="fw-bolder mx-1">({{ getFilesize(file.filesize) }})</span>
                        </div>
                    </div>
                    <!--end::File-->

                    <!--begin::Toolbar-->
                    <div>
                        <span
                            v-show="file.sourceType == 'load'"
                            data-bs-toggle="tooltip"
                            data-bs-delay-show="1000"
                            data-bs-trigger="hover"
                            title="View"
                            class="menu-icon cursor-pointer text-hover-primary"
                            data-kt-element="icon"
                        >
                            <KTIcon icon-name="eye" icon-class="fs-3 me-3" />
                        </span>
                        <span
                            @click="deleteFile(i)"
                            data-bs-toggle="tooltip"
                            data-bs-delay-show="1000"
                            data-bs-trigger="hover"
                            title="Delete"
                            class="cursor-pointer text-hover-danger"
                        >
                            <KTIcon icon-name="trash" icon-class="fs-3" />
                        </span>
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <!--end::Items-->

            <!--begin::Hint-->
            <span class="form-text text-muted fs-7">Upload the supporting documents with max file size of {{ initFilesizeAccepted }}MB.</span>
            <!--end::Hint-->
        </div>
        <!--end::Upload-->
    </div>
    <!--end::Row-->
</template>

<script lang="ts">
import { defineComponent, ref, computed, watch } from "vue";
import MixinService from "@/assets/template/core/services/MixinService";

export default defineComponent({
  name: "control-upload",
  props: {
    formData: { type: Object, required: true },
    formKey: { type: String, required: true },
    containerPath: { type: String, required: true },
    filesizeAccepted: { type: Number, required: true, default: 10 },
    maxFilesAccepted: { type: String, required: true, default: "infinity" },
    maxFilesize: { type: String, required: true, default: "infinity" },
    fileExtensionAccepted: { type: String, required: true, default: "any" },
    checkDuplicateFile: { type: Boolean, required: true, default: true },
    buttonLabel: { type: String, required: true },
    buttonIcon: { type: String, required: true },
  },
  emits: ["update:formData"],
  setup(props, { emit }) {
    const initButtonLabel = ref(props.buttonLabel);
    const initButtonIcon = ref(props.buttonIcon);
    const initFilesizeAccepted = ref(props.filesizeAccepted);

    const initFiles = ref([]);
    const initFile = ref(null);

    const uploadFile = (event) => {
        initFile.value = event.target.files[0];

        if (props.fileExtensionAccepted == "any" || props.fileExtensionAccepted == initFile.value?.name?.substr(initFile.value?.name?.lastIndexOf(".") + 1)) {
            if (props.maxFilesAccepted == "infinity" || parseInt(props.maxFilesAccepted) >= initFiles.value.length) {
                if (parseInt(initFilesizeAccepted.value) * 1048576 >= initFile.value?.size) {
                    if (props.maxFilesize == "infinity" || (props.maxFilesize * 1048576) >= MixinService.calculateSum(initFiles.value, "filesize")) {
                        let process = true;

                        if (props.checkDuplicateFile && initFiles.value.filter(value => value.filename == initFile.value?.name && value.filesize == initFile.value?.size && value.lastModified == initFile.value?.lastModified).length) {
                            process = false;
                        }

                        if (process) {
                            initFiles.value.push({
                                file: initFile.value,
                                filename: initFile.value?.name,
                                filetype: initFile.value?.name?.substr(initFile.value?.name?.lastIndexOf(".") + 1),
                                filesize: initFile.value?.size,
                                filepath: props.containerPath,
                                mimeType: initFile.value?.mimeType,
                                lastModified: initFile.value?.lastModified,
                                sourceType: "upload",
                            });

                            emit("update:formData",  { [props.formKey]: initFiles.value });
                        } else {
                            MixinService.showAlert(`File already exist.`);
                        }
                    } else {
                        MixinService.showAlert(`Reach accomulated maximum ${props.maxFilesize}MB for all files.`);
                    }
                } else {
                    MixinService.showAlert(`Maximum of ${initFilesizeAccepted.value}MB per file only.`);
                }
            } else {
                MixinService.showAlert(`Maximum of ${props.maxFilesAccepted} files accepted.`);
            }
        } else {
            MixinService.showAlert(`${props.fileExtensionAccepted} file extension is accepted.`);
        }
    };

    const deleteFile = (value) => {
        MixinService.showConfirmAlert("Are you sure you want to delete this file?", "Delete", () => {
            initFiles.value.splice(value, 1);
        }, "danger");
    };

    const getFilesize = (value) => {
        return MixinService.covertFilesize(value);
    };
    
    return {
      initFiles,
      uploadFile,
      deleteFile,
      initFilesizeAccepted,
      initButtonLabel,
      initButtonIcon,
      getFilesize,
    };
  },
});
</script>