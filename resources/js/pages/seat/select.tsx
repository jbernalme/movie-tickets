import CinemaScreen from '@/components/cinema-screen';
import Footer from '@/components/footer';
import { Icon } from '@/components/icon';
import InputDiscount from '@/components/input-discount';
import CinemaSeat from '@/components/svg/cinema-seat';
import { useDiscount } from '@/hooks/use-discount';

import MainLayout from '@/layouts/main-layout';
import { formatDate } from '@/lib/date';
import { cn } from '@/lib/utils';
import { Screening, Seat, SeatRow } from '@/types/screening';
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from '@headlessui/react';
import { ChevronDown } from 'lucide-react';
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
        isDiscountValid,
        setIsDiscountValid,
        appliedDiscount,
        setAppliedDiscount,
        error,
        isValidating,
        validateDiscount,
        removeDiscount,
        isAnimating,
    } = useDiscount();

    const screeningDate = formatDate(
        screening.start_time,
        'MMM D, YYYY h:mm a',
    );
    console.log({ screening, screeningDate });

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
            <div className="container mx-auto grid grid-cols-9 gap-10">
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

                    <div className="group relative w-[360px] overflow-hidden rounded-xl border border-gray-800">
                        <div className="absolute top-0 right-0 left-0 h-1 bg-primary group-hover:animate-pulse" />
                        <div className="space-y-6 p-10">
                            <div className="flex items-center justify-between border-b border-gray-800 pb-4">
                                <h2 className="text-center font-bebas-neue text-3xl font-bold text-white">
                                    Movie{' '}
                                    <span className="text-primary">Tikets</span>
                                </h2>
                                <span className="text-sm text-gray-500 uppercase">
                                    Main 123456
                                </span>
                            </div>
                            <div className="space-y-4 border-b border-gray-800 pb-4">
                                <div className="flex items-center justify-between">
                                    <span className="text-sm text-gray-500 uppercase">
                                        Ubicación
                                    </span>
                                    <span className="font-bold">
                                        California
                                    </span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <span className="text-sm text-gray-500 uppercase">
                                        Función
                                    </span>
                                    <span className="font-bold capitalize">
                                        {screeningDate}
                                    </span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <span className="text-sm text-gray-500 uppercase">
                                        Asientos
                                    </span>

                                    <span className="font-bold">
                                        {selectedSeats.length}
                                    </span>
                                </div>
                                <div className="flex flex-wrap gap-2">
                                    {selectedSeats.map((seat) => (
                                        <span
                                            key={seat.id}
                                            className="rounded border border-primary bg-primary-foreground px-3 py-1 text-sm text-primary uppercase"
                                        >
                                            {seat.row}
                                            {seat.number}
                                        </span>
                                    ))}
                                </div>
                                <div>
                                    <Disclosure>
                                        <DisclosureButton className="group flex w-full cursor-pointer items-center justify-center gap-2 rounded border border-primary/50 bg-primary/10 py-2 text-sm font-bold text-primary uppercase transition-colors duration-300 hover:border-primary/70 hover:bg-primary/20">
                                            Ver detalles
                                            <Icon
                                                className="size-5 transition-transform duration-300 group-data-open:rotate-180"
                                                iconNode={ChevronDown}
                                            />
                                        </DisclosureButton>
                                        <DisclosurePanel
                                            transition
                                            className="mt-4 origin-top rounded border border-primary/50 bg-primary/10 p-4 transition duration-200 ease-out data-closed:-translate-y-6 data-closed:opacity-0"
                                        >
                                            <div className="mb-4 space-y-2 divide-y divide-primary/10">
                                                {selectedSeats.map((seat) => (
                                                    <div className="flex items-center justify-between pb-2">
                                                        <div className="flex items-center gap-2">
                                                            <div className="w-12">
                                                                <span
                                                                    key={
                                                                        seat.id
                                                                    }
                                                                    className="rounded border border-primary bg-primary-foreground px-2 py-1 text-sm text-primary uppercase"
                                                                >
                                                                    {seat.row}
                                                                    {
                                                                        seat.number
                                                                    }
                                                                </span>
                                                            </div>
                                                            <span className="text-sm text-foreground capitalize">
                                                                {
                                                                    seat
                                                                        .seat_type
                                                                        .name
                                                                }
                                                            </span>
                                                        </div>
                                                        <span className="font-jetbrains-mono text-sm font-bold text-foreground">
                                                            ${' '}
                                                            {seat.seat_type
                                                                .price_multiplier *
                                                                screening.base_price}
                                                        </span>
                                                    </div>
                                                ))}
                                            </div>
                                            <div className="flex items-center justify-between border-t border-primary/40 pt-4">
                                                <span className="text-sm text-foreground uppercase">
                                                    Subtotal
                                                </span>
                                                <span className="font-jetbrains-mono font-bold text-primary">
                                                    $ {subtotal}
                                                </span>
                                            </div>
                                        </DisclosurePanel>
                                    </Disclosure>
                                </div>
                                <div className="">
                                    <span className="mb-2 block text-sm text-gray-500 uppercase">
                                        Código de descuento
                                    </span>

                                    <InputDiscount
                                        setDiscountCode={setDiscountCode}
                                        validateDiscount={validateDiscount}
                                        discountCode={discountCode}
                                        subtotal={subtotal}
                                        isValidating={isValidating}
                                        appliedDiscount={appliedDiscount}
                                        removeDiscount={removeDiscount}
                                        error={error}
                                    />
                                </div>
                            </div>
                            <div className="flex w-full items-center justify-between">
                                <span className="text-sm text-gray-500 capitalize">
                                    Total a pagar
                                </span>
                                <span
                                    className={cn(
                                        'font-jetbrains-mono text-3xl font-bold text-primary transition-all duration-300',
                                        isAnimating &&
                                            'animate-price-highlight',
                                    )}
                                >
                                    ${appliedDiscount?.total || subtotal}
                                </span>
                            </div>

                            <button
                                type="button"
                                className={cn(
                                    'w-full cursor-pointer rounded border border-primary/50 bg-primary px-4 py-3 text-base font-bold text-foreground uppercase shadow-md shadow-primary/50 transition-all duration-300 hover:-translate-y-[2px] hover:border-primary/70 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none disabled:hover:-translate-y-0 disabled:hover:border-primary/50',
                                )}
                                disabled={isValidating || !selectedSeats.length}
                            >
                                {selectedSeats.length > 0
                                    ? 'Confirmar Reserva'
                                    : 'Selecciona un asiento'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <Footer />
        </MainLayout>
    );
}
