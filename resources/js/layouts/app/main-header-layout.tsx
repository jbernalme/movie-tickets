import { AppHeader } from '@/components/app-header';
import { type BreadcrumbItem } from '@/types';
import { PropsWithChildren } from 'react';

export default function MainHeaderLayout({
    children,
    breadcrumbs,
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    return (
        <div>
            <AppHeader breadcrumbs={breadcrumbs} />

            <main>{children}</main>
        </div>
    );
}
