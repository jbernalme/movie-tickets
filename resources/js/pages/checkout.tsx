import BoxWraper from '@/components/box-wraper';
import ButtonMt from '@/components/button-mt';
import Footer from '@/components/footer';
import MainLayout from '@/layouts/main-layout';
import { formatDate } from '@/lib/date';
import { process } from '@/routes/payment';
import { Info } from 'lucide-react';
import { Icon } from '@/components/icon';

interface SeatInfo {
    id: number;
    row: string;
    number: string;
    seat_type: {
        name: string;
        price_multiplier: number;
    };
    pivot: {
        price: number;
    };
}

interface ScreeningInfo {
    id: number;
    start_time: string;
    end_time: string;
    base_price: number;
    format: string;
    audio: string;
    hall: {
        name: string;
    };
    movie: {
        id: number;
        title: string;
        poster_path: string;
    };
}

interface TicketInfo {
    id: number;
    ticket_code: string;
    total_price: number;
    discount_price: number;
    final_price: number;
    status: string;
    expires_at: string;
    screening: ScreeningInfo;
    seats: SeatInfo[];
}

export default function Checkout({ ticket }: { ticket: TicketInfo }) {
    const screeningDate = formatDate(
        ticket.screening.start_time,
        'MMM D, YYYY h:mm a',
    );

    const handlePay = async () => {
        try {
            const xsrfToken = document.cookie
                .split('; ')
                .find((row) => row.startsWith('XSRF-TOKEN='))
                ?.split('=')[1];

            const response = await fetch(process(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': decodeURIComponent(xsrfToken ?? ''),
                },
                credentials: 'same-origin',
                body: JSON.stringify({ ticket_id: ticket.id }),
            });

            const data = await response.json();

            if (data.init_point) {
                window.location.href = data.init_point;
            } else if (data.error) {
                alert(data.error);
            }
        } catch (error) {
            console.error('Payment error:', error);
            alert('Error al procesar el pago');
        }
    };

    return (
        <MainLayout>
            <div className="container mx-auto pt-14">
                <div className="mb-8 flex flex-col">
                    <h1 className="font-bebas-neue text-5xl font-bold uppercase">
                        Finalizar{' '}
                        <span className="text-primary">compra</span>
                    </h1>
                </div>

                <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <div className="lg:col-span-2 space-y-6">
                        <BoxWraper className="p-6">
                            <h2 className="font-bebas-neue text-2xl uppercase text-white">
                                {ticket.screening.movie.title}
                            </h2>
                            <div className="mt-4 space-y-2 text-sm text-gray-400">
                                <p>
                                    <span className="font-bold text-white">Sala:</span>{' '}
                                    {ticket.screening.hall.name}
                                </p>
                                <p>
                                    <span className="font-bold text-white">Fecha:</span>{' '}
                                    {screeningDate}
                                </p>
                                <p>
                                    <span className="font-bold text-white">Formato:</span>{' '}
                                    {ticket.screening.format} - {ticket.screening.audio}
                                </p>
                            </div>
                        </BoxWraper>

                        <BoxWraper className="p-6">
                            <h3 className="font-bebas-neue text-xl uppercase text-white">
                                Asientos seleccionados
                            </h3>
                            <div className="mt-4 flex flex-wrap gap-2">
                                {ticket.seats.map((seat) => (
                                    <span
                                        key={seat.id}
                                        className="rounded border border-primary bg-primary-foreground px-3 py-1 text-sm text-primary uppercase"
                                    >
                                        {seat.row}{seat.number}
                                    </span>
                                ))}
                            </div>
                        </BoxWraper>

                        <BoxWraper className="p-6">
                            <h3 className="font-bebas-neue text-xl uppercase text-white">
                                Detalle de precios
                            </h3>
                            <div className="mt-4 space-y-2">
                                {ticket.seats.map((seat) => (
                                    <div key={seat.id} className="flex items-center justify-between text-sm">
                                        <span className="text-gray-400">
                                            Asiento {seat.row}{seat.number} ({seat.seat_type.name})
                                        </span>
                                        <span className="font-jetbrains-mono font-bold text-white">
                                            ${seat.pivot.price}
                                        </span>
                                    </div>
                                ))}
                                <div className="border-t border-gray-800 pt-2">
                                    <div className="flex items-center justify-between text-sm">
                                        <span className="text-gray-400">Subtotal</span>
                                        <span className="font-jetbrains-mono font-bold text-white">
                                            ${ticket.total_price}
                                        </span>
                                    </div>
                                    {ticket.discount_price > 0 && (
                                        <div className="flex items-center justify-between text-sm">
                                            <span className="text-green-400">Descuento</span>
                                            <span className="font-jetbrains-mono font-bold text-green-400">
                                                -${ticket.discount_price}
                                            </span>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </BoxWraper>
                    </div>

                    <div className="lg:col-span-1">
                        <BoxWraper className="sticky top-24 p-6">
                            <h3 className="font-bebas-neue text-2xl uppercase text-white">
                                Resumen
                            </h3>

                            <div className="mt-6 space-y-4">
                                <div className="flex items-center justify-between">
                                    <span className="text-gray-400">Código</span>
                                    <span className="font-jetbrains-mono text-sm text-white">
                                        {ticket.ticket_code}
                                    </span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <span className="text-gray-400">Boletos</span>
                                    <span className="font-bold text-white">
                                        {ticket.seats.length}
                                    </span>
                                </div>
                                <div className="border-t border-gray-800 pt-4">
                                    <div className="flex items-center justify-between">
                                        <span className="text-lg font-bold text-white">Total</span>
                                        <span className="font-jetbrains-mono text-3xl font-bold text-primary">
                                            ${ticket.final_price}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div className="mt-2 flex items-center gap-2 rounded border bg-gold/10 p-3 text-xs text-gold">
                                <Icon iconNode={Info} className="size-4 shrink-0" />
                                Tienes 10 minutos para completar el pago
                            </div>

                            <ButtonMt
                                fullWidth
                                className="mt-6"
                                onClick={handlePay}
                            >
                                Pagar con Mercado Pago
                            </ButtonMt>
                        </BoxWraper>
                    </div>
                </div>
            </div>
            <Footer />
        </MainLayout>
    );
}
