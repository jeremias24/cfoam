interface IUser {
    id: string | null;
    employeeNo: string | null;
    firstName: String | null;
    middleName: string | null;
    lastName: string | null;
    suffixName: string | null;
    nickName: string | null;
    fullName: string | null;
    email: string | null;
    phoneNumber: string | null;
    customerId: string | null;
    customerName: string | null;
    dealerSatelliteId: number | null;
    username: string | null;
    password: string | null;
    position: string | null;
    positionTitle: string | null;
    sectionId: number | null;
    section: string | null;
    departmentId: number | null;
    department: string | null;
    divisionId: number | null;
    division: string | null;
    userClassId: number | null;
    userClass: string | null;
    partyNumber: number | null;
    partyId: number | null;
    accesses: Object | null;
    approvedBy: string | null,
    approvals: Object | null;
}

export type { IUser };