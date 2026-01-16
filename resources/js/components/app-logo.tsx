import AppLogoIcon from './app-logo-icon';

export default function AppLogo() {
    return (
        <>
            <div className="-rotate-6">
                <AppLogoIcon className="size-10 fill-primary stroke-background" />
            </div>

            <div className="ml-0.5 grid flex-1 text-left font-bebas-neue text-2xl">
                <span className="mb-0.5 truncate leading-tight">
                    Movie
                    <span className="ml-0.5 font-extrabold text-primary">
                        Tikets
                    </span>
                </span>
            </div>
        </>
    );
}
