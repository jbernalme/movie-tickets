import { formatDate } from '@/lib/date';
import { cn } from '@/lib/utils';
import { Screening, Seat } from '@/types/screening';
import { AppliedDiscount } from '@/types/seat';
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from '@headlessui/react';
import { ChevronDown } from 'lucide-react';
import { Icon } from './icon';
import InputDiscount from './input-discount';

interface TicketSummaryProps {
    screening: Screening;
    selectedSeats: Seat[];
    setDiscountCode: (code: string) => void;
    validateDiscount: (code: string, subtotal: number) => Promise<void>;
    discountCode: string;
    subtotal: number;
    isValidating: boolean;
    appliedDiscount: AppliedDiscount | null;
    removeDiscount: () => void;
    error: string | null;
    isAnimating?: boolean;
}

export default function TicketSummary({
    screening,
    selectedSeats,
    setDiscountCode,
    validateDiscount,
    discountCode,
    subtotal,
    isValidating,
    appliedDiscount,
    removeDiscount,
    error,
    isAnimating = false,
}: TicketSummaryProps) {
    const screeningDate = formatDate(
        screening.start_time,
        'MMM D, YYYY h:mm a',
    );
    return (
        <>
            <div className="group relative w-[360px] overflow-hidden rounded-xl border border-gray-800">
                <div className="absolute top-0 right-0 left-0 h-1 bg-primary group-hover:animate-pulse" />
                <div className="space-y-6 p-10">
                    <div className="flex items-center justify-between border-b border-gray-800 pb-4">
                        <h2 className="text-center font-bebas-neue text-3xl font-bold text-white">
                            Movie <span className="text-primary">Tikets</span>
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
                            <span className="font-bold">California</span>
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
                                                            key={seat.id}
                                                            className="rounded border border-primary bg-primary-foreground px-2 py-1 text-sm text-primary uppercase"
                                                        >
                                                            {seat.row}
                                                            {seat.number}
                                                        </span>
                                                    </div>
                                                    <span className="text-sm text-foreground capitalize">
                                                        {seat.seat_type.name}
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
                                validateDiscount={validateDiscount}
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
                                isAnimating && 'animate-price-highlight',
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
        </>
    );
}
