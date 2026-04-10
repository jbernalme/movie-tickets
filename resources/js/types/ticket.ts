import { Screening } from './screening';

export default interface Ticket {
    id: number;
    screening?: Screening;
    ticket_code: string;
    total_price: number;
    discount_price: number;
    final_price: number;
    status: 'pending' | 'confirmed' | 'cancelled' | 'used';
    expires_at: string;
    used_at: string;
    created_at: string;
    updated_at: string;
}
