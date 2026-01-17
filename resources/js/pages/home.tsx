import MainLayout from '@/layouts/main-layout';
import { BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '/',
    },
];

export default function Home() {
    const { movies } = usePage().props;

    console.log({ movies });

    return (
        <MainLayout breadcrumbs={breadcrumbs}>
            <h1>Home</h1>
        </MainLayout>
    );
}
