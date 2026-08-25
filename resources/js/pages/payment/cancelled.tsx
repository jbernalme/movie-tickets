import BoxWraper from '@/components/box-wraper';
import ButtonMt from '@/components/button-mt';
import MainLayout from '@/layouts/main-layout';
import { index as reservationsIndex } from '@/routes/reservations';
import { Link } from '@inertiajs/react';
import { XCircle } from 'lucide-react';

export default function Cancelled() {
    return (
        <MainLayout>
            <div className="container mx-auto flex min-h-[60vh] items-center justify-center pt-14">
                <BoxWraper className="w-full max-w-md p-10 text-center">
                    <XCircle className="mx-auto size-16 text-red-500" />
                    <h1 className="mt-6 font-bebas-neue text-4xl font-bold uppercase">
                        Pago{' '}
                        <span className="text-red-500">cancelado</span>
                    </h1>
                    <p className="mt-4 text-gray-400">
                        Has cancelado el proceso de pago. Tu reserva ha sido
                        liberada y los asientos están disponibles nuevamente.
                    </p>
                    <div className="mt-8 flex justify-center gap-4">
                        <Link href="/">
                            <ButtonMt variant="secondary">
                                Volver al inicio
                            </ButtonMt>
                        </Link>
                        <Link href={reservationsIndex()}>
                            <ButtonMt>Mis reservas</ButtonMt>
                        </Link>
                    </div>
                </BoxWraper>
            </div>
        </MainLayout>
    );
}
