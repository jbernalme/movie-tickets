// types/screening.ts

export type SeatStatus = 'available' | 'unavailable';

export interface SeatTypeData {
    id: number;
    name: 'regular' | 'vip' | 'luxury';
    display_name: string;
    description?: string;
    price_multiplier: number;
    color_class: string;
    created_at: string;
    updated_at: string;
}

export interface Seat {
    id: number;
    hall_id: number;
    seat_type_id: number;
    row: string;
    number: string;
    status: SeatStatus;
    grid_x: number;
    grid_y: number;
    is_walkway: boolean;
    created_at: string;
    updated_at: string;

    // Relación con seat_type (cargada con eager loading)
    seat_type: SeatTypeData;
}

export interface Hall {
    id: number;
    name: string;
    capacity: number;
    rows: number;
    seats_per_row: number;
    created_at: string;
    updated_at: string;

    // Relación con seats (cargada con eager loading)
    seats?: Seat[];
}

export interface Screening {
    id: number;
    movie_id: number;
    hall_id: number;
    start_time: string;
    end_time: string;
    base_price: number;
    format: string;
    audio: string;
    status: string;
    display: Display;
    state: State;

    // Relación con hall (cargada con eager loading)
    hall?: Hall;
}

export interface SeatRow {
    row: string;
    seats: Seat[];
}

export interface ScreeningsByMonthData {
    month: string;
    month_name: string;
    total: number;
    days: ScreeningByDay[];
}

export interface ScreeningByDay {
    date: string;
    date_name: string;
    day_of_week: string;
    total: number;
    format_audios: ScreeningsByFormatAudio[];
}

export interface ScreeningBytime {
    start_time_24h: string;
    start_time_12h: string;
    total: number;
    screening: Screening;
}

export interface Display {
    date: string;
    time: string;
    duration: string;
    format_badge: string;
    audio_label: string;
    urls: string;
}

export interface State {
    is_upcoming: boolean;
    is_ongoing: boolean;
    is_finished: boolean;
    is_today: boolean;
    starts_in: string;
}

export interface ScreeningsByFormatAudio {
    format: string;
    audio: string;
    label: string;
    total: number;
    times: ScreeningBytime[];
}

// export interface Screening {
//     id: number;
//     movie_id: number;
//     hall_id: number;
//     start_time: string;
//     end_time: string;
//     format: string;
//     audio: string;
//     status: string;
//     display: Display;
//     state: State;
// }
// export interface ScreeningsByMonthData {
//     month: string;
//     month_name: string;
//     total: number;
//     days: ScreeningByDay[];
// }

// export interface ScreeningByDay {
//     date: string;
//     date_name: string;
//     day_of_week: string;
//     total: number;
//     format_audios: ScreeningsByFormatAudio[];
// }

// export interface ScreeningBytime {
//     start_time_24h: string;
//     start_time_12h: string;
//     total: number;
//     screening: Screening;
// }

// export interface Display {
//     date: string;
//     time: string;
//     duration: string;
//     format_badge: string;
//     audio_label: string;
//     urls: string;
// }
// export interface State {
//     is_upcoming: boolean;
//     is_ongoing: boolean;
//     is_finished: boolean;
//     is_today: boolean;
//     starts_in: string;
// }

// export interface ScreeningsByFormatAudio {
//     format: string;
//     audio: string;
//     label: string;
//     total: number;
//     times: ScreeningBytime[];
// }
