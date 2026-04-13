import { cn } from '@/lib/utils';

export default function BoxWraper({
    children,
    className = '',
}: {
    children: React.ReactNode;
    className?: string;
}) {
    return (
        <div
            className={cn(
                'relative overflow-hidden rounded-xl border border-gray-800',
                className,
            )}
        >
            <div className="absolute top-0 right-0 left-0 h-1 bg-primary group-hover:animate-pulse" />
            {children}
        </div>
    );
}
