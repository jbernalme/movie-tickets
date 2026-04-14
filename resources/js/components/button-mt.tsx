import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import React from 'react';

const buttonStyles =
    'w-full flex items-center justify-center gap-2 cursor-pointer rounded border border-primary/50 bg-primary px-4 py-3 text-base font-bold text-foreground uppercase shadow-md shadow-primary/50 transition-all duration-300 hover:-translate-y-[2px] hover:border-primary/70 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none disabled:hover:-translate-y-0 disabled:hover:border-primary/50';

type ButtonMtProps = React.ComponentProps<typeof Link> & {
    children: React.ReactNode;
};

export default function ButtonMt({
    children,
    className,
    ...props
}: ButtonMtProps) {
    return (
        <Link className={cn(buttonStyles, className)} {...props}>
            {children}
        </Link>
    );
}
