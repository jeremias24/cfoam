import { ref } from "vue";
import Swal from "sweetalert2/dist/sweetalert2.js";

/**
 * @description check value
 * @param value: any
 */
export const hasValue = (value: any): any => {
    if (value === null || value === undefined || value === "") {
        return false;
    }

    return true;
}

/**
 * @description serialize object
 * @param data: object
 */

export const serialize = (data: object, form: any = null, namespace: any = null, index: any = null): any => {
    var formData = hasValue(form) ? form : new FormData();
    var formKey;

    for (var property in data) {
        if (data.hasOwnProperty(property)) {
            if (hasValue(namespace)) {
                formKey = `${namespace}${hasValue(index) ? `[${index}]` : ""}[${property}]`;
            } else {
                formKey = property;
            }

            if (Array.isArray(data[property])) {
                for (const [key, value] of Object.entries(data[property])) {
                    serialize(value, formData, property, key);
                }
            } else {
                if (
                    typeof data[property] === "object" &&
                    !(data[property] instanceof File)
                ) {
                    serialize(data[property], formData, property);
                } else {
                    formData.append(formKey, data[property]);
                }
            }
        }
    }

    return formData;
};

export const isValidEmail = (email: string) => {
    // Regular expression for email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

export const isValidPassword = (password: string): boolean => {

    // Check if the password is at least 12 characters long
    if (password.length < 12) {
        return false;
    }

    // Check if the password contains at least one uppercase letter
    if (!/[A-Z]/.test(password)) {
        return false;
    }

    // Check if the password contains at least one lowercase letter
    if (!/[a-z]/.test(password)) {
        return false;
    }

    // Check if the password contains at least one digit
    if (!/\d/.test(password)) {
        return false;
    }

    // Check if the password contains at least one non-alphanumeric character
    if (!/[^a-zA-Z0-9]/.test(password)) {
        return false;
    }

    // Check if the password contains  at least one special character
    const specialCharRegex = /(?=.*[@$!%*?&_-])/;
    if (!specialCharRegex.test(password)) {
        return false;
    }

    // If all checks pass, the password is valid
    return true;
}

export const validateForm = (data: object, rules: any): any => {
    const validation = ref({
        valid: true,
        id: "",
        error: null,
        type: "",
    });

    Object.entries(data).forEach(([key, value]) => {
        if (key in rules && validation.value.valid) {
            rules[key].every(item => {

                if (item?.required && !hasValue(value)) {
                    validation.value = {
                        valid: false,
                        id: key,
                        error: item?.message,
                        type: "required",
                    }

                    return false;
                }

                if (item?.type === "email" && !hasValue(value) && !isValidEmail(value)) {
                    validation.value = {
                        valid: false,
                        id: key,
                        error: item?.message,
                        type: "email",
                    }

                    return false;
                }

                if (item?.type === "email" && item?.existing) {
                    validation.value = {
                        valid: false,
                        id: key,
                        error: item?.message,
                        type: "email",
                    }

                    return false;
                }

                if (item?.type === "username" && item?.existing) {
                    validation.value = {
                        valid: false,
                        id: key,
                        error: item?.message,
                        type: "username",
                    }

                    return false;
                }

                if (item?.type === "password" && !hasValue(value) && !isValidPassword(value)) {
                    validation.value = {
                        valid: false,
                        id: key,
                        error: item?.message,
                        type: "password",
                    }

                    return false;
                }

                if (item?.type === 'accesses' && value.length == 0) {

                    validation.value = {
                        valid: false,
                        id: key,
                        error: item?.message,
                        type: "accesses",
                    }

                    return false;
                }

                return true;
            });
        }
    });

    if (!validation.value.valid) {
        showAlert(validation.value.error);
    }

    return validation.value;
};


/**
 * @description Select2 No Data
 * @param
 */
export const select2NoData = () => {
    const data = ref([{
        label: "No results found",
        value: "",
        disabled: true,
    }]);

    return data.value;
};

/**
 * @description Select2 No Data
 * @param
 */
export const select2ClearData = () => {
    const data = ref([{
        label: "Clear selection",
        value: null, // Set the value to null to indicate clearing
    }]);

    return data.value;
};

/**
 * @description Convert filesize
 * @param value: number, decimal: number, withUnits: boolean
 */
export const covertFilesize = (value: number, decimals: number = 2, withUnits: boolean = true) => {
    const units = ref(["B", "KB", "MB", "GB", "TB", "PB"]);
    let i = 0;

    for (i; value > 1024; i++) {
        value /= 1024;
    }

    return `${parseFloat(value.toFixed(decimals))} ${withUnits ? units.value[i] : ""}`;
};

/**
 * @description Calculate sum of array object
 * @param value: array, key: string
 */
export const calculateSum = (value: any, key: string) => {
    return value.reduce((accumulator, item) => {
        return accumulator + item[key]
    }, 0);
};

/**
 * @description Encrypt string value
 * @param value: array, key: string
 */
export const encrypt = (value: string) => {
    return window.btoa(encodeURIComponent(value));
};

/**
 * @description Decrypt string value
 * @param value: array, key: string
 */
export const decrypt = (value: string) => {
    //return JSON.parse(decodeURIComponent(window.atob(value)));
    return decodeURIComponent(window.atob(value));
};

/**
 * @description Show alert modal
 * @param content: string, type: string, buttonStyle: string
 */

export const showAlert = (content: string, type: string = "error", buttonStyle: string = "primary", callback: any = () => { }) => {
    Swal.fire({
        text: content,
        icon: type,
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        heightAuto: false,
        customClass: {
            confirmButton: `btn btn-sm btn-${buttonStyle}`,
        },
    }).then(function (result) {
        if (result.isConfirmed)
            callback();
    });
};

/**
 * @description Show confirm alert modal
 * @param content: string, buttonTitle: string, callback: any, type: string, buttonStyle: string
 */
export const showConfirmAlert = (content: string, buttonTitle: string, callback: any = () => { }, buttonStyle: string = "primary", type: string = "question") => {
    Swal.fire({
        html: `<span class="fs-7">${content}</span>`,
        icon: type,
        buttonsStyling: false,
        showCancelButton: true,
        confirmButtonText: `Ok, ${buttonTitle} it!`,
        cancelButtonText: 'No, return!',
        heightAuto: false,
        customClass: {
            confirmButton: `btn btn-sm fw-bold hover-scale fs-7 btn-${buttonStyle}`,
            cancelButton: `btn btn-sm fw-bold hover-scale fs-7 btn-active-light`,
        },
    }).then(function (result) {
        if (result.isConfirmed)
            callback();
    });
};

/**
 * @description get corresponding color od status code
 * @param value: any
 */
export const getStatusColor = (value: any) => {
    let color = "dark";

    if (["DRFT"].includes(value.toUpperCase())) {
        color = "dark";
    } else if (["CRTD", "SBMTD"].includes(value.toUpperCase())) {
        color = "primary";
    } else if (["PNDG"].includes(value.toUpperCase())) {
        color = "info";
    } else if (["CNCLD", "RJCTD"].includes(value.toUpperCase())) {
        color = "danger";
    } else if (["APRVD", "CMPLTD"].includes(value.toUpperCase())) {
        color = "success";
    }

    return color;
};

export default {
    hasValue,
    serialize,
    validateForm,
    select2NoData,
    covertFilesize,
    calculateSum,
    encrypt,
    decrypt,
    showAlert,
    showConfirmAlert,
    getStatusColor,
}; 