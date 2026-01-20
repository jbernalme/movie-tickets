


import { cn } from '@/lib/utils';

export default function Title({ children, className }: { children: React.ReactNode, className?: string }) {
    return (
        <div className={cn('border-l-4 border-primary pl-4', className)}>

            <h1 className="font-bebas-neue text-3xl font-bold">{children}</h1>
        </div>
    );
}
