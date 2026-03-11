export type SeatType = 'regular' | 'vip' | 'luxury';
export type SeatStatus = 'available' | 'unavailable';

export interface Seat {
    id: number;
    hall_id: number;
    row: string;
    number: string;
    type: SeatType;
    status: SeatStatus;
    grid_x: number;
    grid_y: number;
    is_walkway: boolean;
    created_at: string;
    updated_at: string;

    // Campos relacionales (opcionales, dependiendo de si haces eager loading)
    // hall?: Hall; // Si importas la interfaz Hall
}

// Interfaz para crear un asiento (sin id ni timestamps)
export interface CreateSeatInput {
    hall_id: number;
    row: string;
    number: string;
    type?: SeatType; // ✅ Tiene default en BD, así que es opcional
    status?: SeatStatus; // ✅ Tiene default en BD, así que es opcional
    grid_x: number;
    grid_y: number;
    is_walkway?: boolean; // ✅ Tiene default en BD, así que es opcional
}

// Interfaz para actualizar un asiento (todos opcionales excepto id)
export interface UpdateSeatInput extends Partial<CreateSeatInput> {
    id: number;
}

export interface SeatRow {
    row: string;
    seats: Seat[];
}

export interface AppliedDiscount {
    amount: number;
    code: string;
    type: 'percentage' | 'fixed';
    total: number;
}
