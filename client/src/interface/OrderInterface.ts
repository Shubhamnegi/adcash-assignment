export interface OrderInterface {
    id: number;
    quantity: number;
    total: number;
    created_at: string;
    user_name: string;
    product_name: string;
    user?: {
        id: number;
        name: string;
        status: boolean;
    },
    product?: {
        id: number;
        name: string;
        status: boolean;
        unitPrice: number;
        discountType: string;
        minQtyForDiscount: number;
        discount: number;
    },
}