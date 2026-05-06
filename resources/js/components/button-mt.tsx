import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import React from 'react';

const commonButtonStyles =
    'inline-flex items-center justify-center gap-2 cursor-pointer rounded border px-4 py-3 text-base font-bold uppercase disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none ';

const buttonStylesPrimary = cn(
    commonButtonStyles,
    'border-primary/50 bg-primary text-foreground shadow-md shadow-primary/50 transition-all duration-300 hover:-translate-y-[2px] hover:border-primary/70 hover:shadow-lg disabled:hover:-translate-y-0 disabled:hover:border-primary/50',
);

const buttonStylesSecondary = cn(
    commonButtonStyles,
    'border border-ring bg-ring/70 text-muted-foreground transition-colors duration-300 hover:bg-ring hover:text-foreground',
);

interface ButtonMtProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    children: React.ReactNode;
    className?: string;
    fullWidth?: boolean;
    variant?: 'primary' | 'secondary';
    href?: string;
    method?: 'get' | 'post' | 'put' | 'patch' | 'delete';
}

export default function ButtonMt({
    children,
    className,
    fullWidth = false,
    variant = 'primary',
    href,
    method,
    ...props
}: ButtonMtProps) {
    const combinedClassName = cn(
        variant === 'primary' ? buttonStylesPrimary : buttonStylesSecondary,
        fullWidth && 'w-full',
        className,
    );

    if (href) {
        return (
            <Link href={href} method={method} className={combinedClassName}>
                {children}
            </Link>
        );
    }

    return (
        <button className={combinedClassName} {...props}>
            {children}
        </button>
    );
}
