import { cn } from '@/lib/utils';

type TagType = 'primary' | 'gold'; // o 'secondary', 'success', etc.

export default function Tag({
    children,
    type = 'primary',
}: {
    children: React.ReactNode;
    type?: TagType; // opcional, con valor por defecto
}) {
    return (
        <span
            className={cn(
                'rounded border px-2 py-1 font-inter text-xs font-bold uppercase',
                type === 'primary'
                    ? 'border-primary bg-primary'
                    : 'border-gold bg-gold/20 text-gold',
            )}
        >
            {children}
        </span>
    );
}
