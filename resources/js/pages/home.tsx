import MainLayout from '@/layouts/main-layout';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '/',
    },
];

export default function Home() {
    return (
        <MainLayout breadcrumbs={breadcrumbs}>
            <h1>Home</h1>
        </MainLayout>
    );
}
