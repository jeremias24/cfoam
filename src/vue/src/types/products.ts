interface IProduct {
    id: number | null;
    name: string | null;
    description: String | null;
    categoryId: number | null;
    isActive: number | null;
    createdBy: number | null;
    createdAt: string | null;
    updatedBy: number | null;
    updatedAt: string | null;
}

export type { IProduct };