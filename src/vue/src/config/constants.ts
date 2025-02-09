const sysRefCode = "SYS-018-OS";

const approvalsTableHeaders = [
    {
        columnName: "Reference No.",
        columnLabel: "referenceNo",
        sortEnabled: true,
        columnWidth: 150,
        labelClass: "text-start",
    },
    {
        columnName: "System",
        columnLabel: "system",
        sortEnabled: true,
        columnWidth: 150,
        labelClass: "text-start",
    },
    {
        columnName: "Requestor",
        columnLabel: "subCategoryDescription",
        columnTruncate: true,
        sortEnabled: true,
        columnWidth: 150,
        labelClass: "text-start",
    },
    {
        columnName: "Status",
        columnLabel: "subject",
        sortEnabled: true,
        labelClass: "text-start",
    },
    {
        columnName: "Actions",
        columnLabel: "actions",
        sortEnabled: false,
        columnWidth: 100,
        labelClass: "text-center",
    },
];

const getMessageChannels = () => ({
    message: `message`
});

const messageEvents = {
    sent: "MessageEvent",
};

const getProductChannels = () => ({
    product: `product`
});

const productEvents = {
    sent: "ProductEvent",
};

export default {
    sysRefCode,
    approvalsTableHeaders,
    getMessageChannels,
    messageEvents,
    getProductChannels,
    productEvents
};
