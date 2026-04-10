import { Head, Link } from '@inertiajs/react';

interface Props {
    status: number;
    title?: string;
    description?: string;
}

const httpMessages: Record<number, { title: string; description: string }> = {
    403: {
        title: 'Acceso denegado',
        description: 'No tienes permiso para acceder a esta página.',
    },
    404: {
        title: 'Página no encontrada',
        description: 'La página que buscas no existe o fue movida.',
    },
    500: {
        title: 'Error del servidor',
        description: 'Algo salió mal en el servidor. Intenta más tarde.',
    },
    503: {
        title: 'Servicio no disponible',
        description: 'Estamos en mantenimiento. Vuelve pronto.',
    },
};

export default function Error({ status, title, description }: Props) {
    // Si vienen title/description del backend los usa,
    // si no, busca en el mapa de HTTP, si tampoco hay usa fallback
    const fallback = httpMessages[status] ?? {
        title: 'Error inesperado',
        description: 'Ocurrió un error inesperado.',
    };

    const resolvedTitle = title ?? fallback.title;
    const resolvedDescription = description ?? fallback.description;

    return (
        <>
            <Head title={`${status} — ${resolvedTitle}`} />
            <div className="flex min-h-screen flex-col items-center justify-center gap-4">
                <span className="text-7xl font-bold text-gray-300">
                    {status}
                </span>
                <h1 className="text-2xl font-semibold text-gray-800">
                    {resolvedTitle}
                </h1>
                <p className="max-w-md text-center text-gray-500">
                    {resolvedDescription}
                </p>
                <Link
                    href="/"
                    className="mt-4 rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700"
                >
                    Volver al inicio
                </Link>
            </div>
        </>
    );
}
