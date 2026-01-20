import { type BreadcrumbItem } from '@/types';
import type { ReactNode } from 'react';
import MainHeaderLayout from './app/main-header-layout';

interface MainLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function MainLayout({ children, breadcrumbs }: MainLayoutProps) {
    return (
        <div>
            <MainHeaderLayout breadcrumbs={breadcrumbs} />

            {children}
        </div>
    );
}
