
<template>
    <!--begin::Row-->
    <div class="row">
        <!--begin::Upload-->
        <div class="mb-2">
            <!--begin::Controls-->
            <div class="mb-5">
                <input type="file" id="kt_file_upload" allow-multiple="true" accept="image/*" multiple @change="uploadFiles" class="d-none" />
                <label for="kt_file_upload" class="btn btn-lg btn-primary fw-bold me-2=">
                    <KTIcon :icon-name="initButtonIcon" icon-class="fs-3 me-1" />
                    {{ initButtonLabel }}
                </label>
            </div>
            <!--end::Controls-->

            <div class="col-xl-12 mb-xl-10 mb-4">
                <div class="row gx-6 gy-3">

                    <div v-for="(file, i) in initFiles" class="col-xl-3">
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <!--begin::Preview existing avatar-->
                            <img
                                v-if="file.preview"
                                :src="file.preview"
                                alt="Uploaded Image"
                                class="img-thumbnail"
                                style="width: 150px; height: 120px;"
                            />
                            <!--end::Preview existing avatar-->

                            <!--begin::Toolbar-->
                            <div>
                                <!--begin::Label-->
                                <label  @click="deleteFile(i)"  class="btn btn-icon btn-circle btn-active-color-danger w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip"   data-bs-delay-show="1000" title="Delete">
                                    <i class="ki-duotone ki-cross fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </label>
                                <!--end::Label-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Hint-->
            <span class="form-text text-muted fs-7">Upload supported images {{ fileExtensionAccepted }} and a total size limit of {{ initFilesizeAccepted }} MB.</span>
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
    fileExtensionAccepted: { type: String, required: true, default: () => ["png", "jpg", "jpeg", "webp"] },
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

    const uploadFiles = (event: Event) => {

        const files = Array.from((event.target as HTMLInputElement).files || []);

        const acceptedExtensions = Array.isArray(props.fileExtensionAccepted) ? props.fileExtensionAccepted.map(ext => ext.toLowerCase()) : [props.fileExtensionAccepted.toLowerCase()];
        const maxFiles = props.maxFilesAccepted === "infinity" ? Infinity : parseInt(props.maxFilesAccepted);
        const maxFileSizeMB = parseInt(initFilesizeAccepted.value); // Max file size per file (MB)
        const maxTotalSizeMB = props.maxFilesize === "infinity" ? Infinity : parseInt(props.maxFilesize); // Max accumulated size (MB)
        
        let totalSizeMB = MixinService.calculateSum(initFiles.value, "filesize") / 1048576; // Convert existing size to MB

        files.forEach((file) => {
            const fileExtension = file.name.split('.').pop()?.toLowerCase() || '';
            const fileSizeMB = file.size / 1048576; // Convert file size to MB

            // 🔹 Validate file extension
            if (props.fileExtensionAccepted !== "any" && !acceptedExtensions.includes(fileExtension)) {
                MixinService.showAlert(`Only ${acceptedExtensions.join(", ")} file extensions are accepted.`);
                return;
            }

            // 🔹 Validate max files count
            if (initFiles.value.length >= maxFiles) {
                MixinService.showAlert(`Maximum of ${props.maxFilesAccepted} files accepted.`);
                return;
            }

            // 🔹 Validate individual file size
            if (fileSizeMB > maxFileSizeMB) {
                MixinService.showAlert(`Maximum of ${initFilesizeAccepted.value}MB per file allowed.`);
                return;
            }

            // 🔹 Validate total accumulated file size
            if (totalSizeMB + fileSizeMB > maxTotalSizeMB) {
                MixinService.showAlert(`Reached accumulated maximum ${props.maxFilesize}MB for all files.`);
                return;
            }

            // 🔹 Prevent duplicate files
            const isDuplicate = initFiles.value.some(value => 
                value.filename === file.name &&
                value.filesize === file.size &&
                value.lastModified === file.lastModified
            );
            if (props.checkDuplicateFile && isDuplicate) {
                MixinService.showAlert(`File ${file.name} already exists.`);
                return;
            }

            // 🔹 Read file and generate preview
            const reader = new FileReader();
            reader.onload = () => {
                initFiles.value.push({
                    file,
                    filename: file.name,
                    filetype: fileExtension,
                    filesize: file.size,
                    preview: reader.result, // Preview URL
                    filepath: props.containerPath,
                    mimeType: file.type,
                    lastModified: file.lastModified,
                    sourceType: "upload",
                });

                // 🔹 Update formData after all files are processed
                emit("update:formData", { [props.formKey]: [...initFiles.value] });
            };

            reader.onerror = () => {
                MixinService.showAlert(`Error reading file: ${file.name}`);
            };

            reader.readAsDataURL(file);
            totalSizeMB += fileSizeMB; // Update total size
        });
    };


    /* const uploadFiles = (event: Event) => {
        const files = Array.from((event.target as HTMLInputElement).files || []);
        
        files.forEach((file) => {
            if (
                props.fileExtensionAccepted === "any" || 
                props.fileExtensionAccepted === file.name.substr(file.name.lastIndexOf(".") + 1) || 
                props.fileExtensionAccepted.includes(file.name.substr(file.name.lastIndexOf(".") + 1))
            ) {
                
                if (props.maxFilesAccepted === "infinity" || parseInt(props.maxFilesAccepted) >= initFiles.value.length) {
                    if (parseInt(initFilesizeAccepted.value) * 1048576 >= file.size) {
                        if (
                            props.maxFilesize === "infinity" || 
                            parseInt(props.maxFilesize) * 1048576 >= 
                            MixinService.calculateSum(initFiles.value, "filesize") + file.size
                        ) {
                            let process = true;

                            if (
                                props.checkDuplicateFile && 
                                initFiles.value.some(
                                    (value) => 
                                        value.filename === file.name &&
                                        value.filesize === file.size &&
                                        value.lastModified === file.lastModified
                                )
                            ) {
                                process = false;
                            }

                            if (process) {

                                const reader = new FileReader();
                                reader.onload = () => {
                                    initFiles.value.push({
                                        file,
                                        file: file,
                                        filename: file.name,
                                        filetype: file.name.substr(file.name.lastIndexOf(".") + 1),
                                        filesize: file.size,
                                        preview: reader.result, // Preview URL
                                        filepath: props.containerPath,
                                        mimeType: file.type,
                                        lastModified: file.lastModified,
                                        sourceType: "upload",
                                    });

                                }

                                emit("update:formData", { [props.formKey]: initFiles.value });

                                reader.readAsDataURL(file);
                            } else {
                                MixinService.showAlert(`File ${file.name} already exists.`);
                            }
                        } else {
                            MixinService.showAlert(`Reached accumulated maximum ${props.maxFilesize}MB for all files.`);
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
        });
    }; */


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


    const openViewer = (index: number) => {
      currentImageIndex.value = index;
      viewerVisible.value = true;
    };

    
    return {
      initFiles,
      uploadFiles,
      deleteFile,
      initFilesizeAccepted,
      initButtonLabel,
      initButtonIcon,
      getFilesize,
      openViewer
    };
  },
});
</script>