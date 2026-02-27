import ZigzagWave from './svg/zigziag-wave';

export default function Ticket() {
    return (
        <div className="h-1/2 max-w-md text-accent-foreground">
            <ZigzagWave className="w-full" top={true} />
            <div className="flex h-full w-full justify-center bg-accent-foreground font-jetbrains-mono text-background">
                <div className="flex w-full flex-col items-center gap-2 p-6">
                    <div className="flex flex-col items-center gap-2">
                        <div className="flex flex-col">
                            <span className="text-xs">Main 123456</span>
                            <span className="text-xs">California</span>
                        </div>
                        <div className="border-6 border-double p-2 text-2xl">
                            Movie
                            <span className="font-extrabold">Tikets</span>
                        </div>
                        <div className="text-xs">
                            <span>Fecha: 2025-10-25</span>
                            <span>Hora: 20:00</span>
                        </div>
                        <div className="text-xs">
                            <span>Asientos: 2</span>
                        </div>
                    </div>
                    <hr className="my-4 w-full border-t-2 border-dashed border-gray-400" />
                    <span>Precio: $20.00</span>
                </div>
            </div>
            <ZigzagWave className="w-full" top={false} />
        </div>
    );
}
