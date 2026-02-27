export default function CinemaScreen() {
    return (
        <div className="relative h-[90px] w-full overflow-hidden [border-radius:50%_/_100%_100%_0_0] border-t-[6px] border-primary bg-gradient-to-b from-primary/60 via-primary/30 to-transparent [filter:drop-shadow(0_-10px_25px_rgba(236,19,19,0.8))]">
            <span className="absolute bottom-0 left-1/2 -translate-x-1/2 -translate-y-1/2 transform font-jetbrains-mono text-2xl font-bold text-white uppercase">
                Pantalla
            </span>
        </div>
    );
}
