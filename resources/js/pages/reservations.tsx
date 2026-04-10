import MainLayout from '@/layouts/main-layout';
import Ticket from '@/types/ticket';

export default function ShoppingCart({ tickets }: { tickets: Ticket[] }) {
    console.log(tickets);
    return (
        <MainLayout>
            <div className="container mx-auto pt-14">
                <div className="flex flex-col">
                    <h1 className="font-bebas-neue text-5xl font-bold uppercase">
                        Tus <span className="text-primary">reservas</span>
                    </h1>
                    <span className="text-sm text-gray-500">
                        {tickets.length} boletos
                    </span>
                </div>
                <div className="flex flex-col gap-4">
                    {tickets.map((ticket) => (
                        <div className="group relative overflow-hidden rounded-xl border border-gray-800 transition-all duration-800 hover:translate-y-[-2px]">
                            <div className="absolute top-0 right-0 left-0 h-1 bg-primary opacity-0 transition-all duration-800 group-hover:opacity-100" />
                            <div key={ticket.id} className="p-4">
                                <h2>{ticket.screening?.movie?.title}</h2>
                                <p>{ticket.screening?.start_time}</p>
                                <p>{ticket.screening?.end_time}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </MainLayout>
    );
}
