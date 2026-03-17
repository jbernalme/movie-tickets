import CinemaScreen from '@/components/cinema-screen';
import Footer from '@/components/footer';
import CinemaSeat from '@/components/svg/cinema-seat';
import TicketSummary from '@/components/ticket-summary';
import { useDiscount } from '@/hooks/use-discount';

import MainLayout from '@/layouts/main-layout';
import { Screening, Seat, SeatRow } from '@/types/screening';
import { useState } from 'react';

interface SelectProps {
    screening: Screening;
    seatsByRows: SeatRow[];
}

export default function Select({ screening, seatsByRows }: SelectProps) {
    const [selectedSeats, setSelectedSeats] = useState<Seat[]>([]);
    const [subtotal, setSubtotal] = useState(0);

    const {
        discountCode,
        setDiscountCode,
        appliedDiscount,
        error,
        isValidating,
        validateDiscount,
        removeDiscount,
        isAnimating,
    } = useDiscount();

    console.log({ screening, appliedDiscount });

    const handleSeatClick = (seat: Seat) => {
        setSelectedSeats((prev) => {
            const isSelected = prev.some((s) => s.id === seat.id);

            // Calcula el nuevo array
            const newSelectedSeats = isSelected
                ? prev.filter((s) => s.id !== seat.id)
                : [...prev, seat];

            // Calcula el subtotal con el NUEVO array
            const newSubtotal = newSelectedSeats.reduce(
                (total, s) =>
                    total + s.seat_type.price_multiplier * screening.base_price,
                0,
            );

            // Actualiza subtotal
            setSubtotal(newSubtotal);

            // Si no hay asientos, remover descuento
            if (newSelectedSeats.length === 0 && appliedDiscount) {
                removeDiscount();
            }

            // Si hay descuento aplicado, re-validar con nuevo subtotal
            if (appliedDiscount && newSelectedSeats.length > 0) {
                validateDiscount(appliedDiscount.code, newSubtotal);
            }

            // Retorna el nuevo array
            return newSelectedSeats;
        });
    };

    return (
        <MainLayout>
            <div className="container mx-auto grid grid-cols-9 gap-10 pt-14">
                <div className="col-span-6">
                    <CinemaScreen />

                    <div className="mx-auto mt-20 w-fit space-y-4">
                        {seatsByRows.map((row) => (
                            <div
                                key={row.row}
                                className="flex items-center justify-center"
                            >
                                <span className="w-8 text-center font-bold text-gray-600">
                                    {row.row}
                                </span>
                                <div className="grid flex-1 auto-cols-[minmax(2rem,3rem)] grid-flow-col gap-1">
                                    {row.seats.map((seat) => (
                                        <CinemaSeat
                                            key={seat.id}
                                            row={row.row}
                                            number={seat.number}
                                            walkable={Boolean(seat.is_walkway)}
                                            onClick={() => {
                                                handleSeatClick(seat);
                                            }}
                                            isSelected={selectedSeats.some(
                                                (s) => s.id === seat.id,
                                            )}
                                        />
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
                <div className="col-span-3 flex flex-col items-center">
                    <h1 className="mb-6 text-center font-bebas-neue text-4xl uppercase">
                        Reserva de boletos
                    </h1>

                    <TicketSummary
                        screening={screening}
                        selectedSeats={selectedSeats}
                        setDiscountCode={setDiscountCode}
                        validateDiscount={validateDiscount}
                        discountCode={discountCode}
                        subtotal={subtotal}
                        isValidating={isValidating}
                        appliedDiscount={appliedDiscount}
                        removeDiscount={removeDiscount}
                        error={error}
                        isAnimating={isAnimating}
                    />
                </div>
            </div>
            <Footer />
        </MainLayout>
    );
}
