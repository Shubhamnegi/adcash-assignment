
export interface ProductInterface {
    id: number;
    name: string;
    status: boolean;
    unitPrice: number;
    discountType?: string;
    minQtyForDiscount?: number;
    discount?: number;
}