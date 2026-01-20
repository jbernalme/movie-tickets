import AppLogo from './app-logo';

export default function Footer() {
    return (
        <footer className="text-gray-400">
            <div className="container mx-auto py-20">
                <div className="grid grid-cols-4">
                    <div className="flex flex-col justify-between p-6">
                        <div className="flex items-center">
                            <AppLogo />
                        </div>
                        <p className="font-inter text-sm">
                            Nuestro cine es más que una sala de proyección: es
                            un refugio para los amantes del séptimo arte, un
                            lugar donde cada función se convierte en una
                            experiencia inolvidable.{' '}
                        </p>
                    </div>
                    <div className="flex flex-col justify-between p-6">
                        <h4 className="font-bebas-neue text-2xl font-bold">
                            Explora
                        </h4>
                        <ul>
                            <li>Películas</li>
                            <li>Cartelera</li>
                            <li>Próximamente</li>
                        </ul>
                    </div>
                    <div className="flex flex-col justify-between p-6">
                        <h4 className="font-bebas-neue text-2xl font-bold">
                            Contacto
                        </h4>
                        <ul>
                            <li>Teléfono: 123-456-789</li>
                            <li>Email: info@movietickets.com</li>
                            <li>Dirección: Calle 123, Ciudad</li>
                        </ul>
                    </div>
                    <div className="flex flex-col justify-between p-6">
                        <h4 className="font-bebas-neue text-2xl font-bold">
                            Redes Sociales
                        </h4>
                        <ul>
                            <li>Facebook</li>
                            <li>Twitter</li>
                            <li>Instagram</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div className="mt-10 border-t border-gray-900 py-10">
                <p className="text-center text-gray-500">
                    © 2025 Movie Tickets. All rights reserved.
                </p>
            </div>
        </footer>
    );
}
