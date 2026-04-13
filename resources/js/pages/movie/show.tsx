import { select } from '@/actions/App/Http/Controllers/SeatController';
import BoxWraper from '@/components/box-wraper';
import Footer from '@/components/footer';
import HeroDetails from '@/components/hero-details';
import { Icon } from '@/components/icon';
import Tabs from '@/components/tabs';
import Subtitle from '@/components/ui/subtitle';
import Title from '@/components/ui/title';
import MainLayout from '@/layouts/main-layout';
import { MovieDetails } from '@/types/movie';
import { Screening, ScreeningsByMonthData } from '@/types/screening';
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/react';
import { Link } from '@inertiajs/react';
import { Armchair, Check, ChevronDown, MapPin } from 'lucide-react';
import { useState } from 'react';

const theaters = [
    {
        id: 1,
        name: 'Premier Limonar & IMAX',
        location: 'Cali, Valle del cauca',
    },
    { id: 2, name: 'Hayuelos', location: 'Bogotá, Cundinamarca' },
    { id: 3, name: 'Mall del Norte', location: 'Medellín, Antioquia' },
    { id: 4, name: 'Mallplaza', location: 'Manizales, Caldas' },
    {
        id: 5,
        name: 'Mallplaza Buenavista',
        location: 'Barranquilla, Atlántico',
    },
];

export default function Show({
    movie,
    screenings,
}: {
    movie: MovieDetails;
    screenings: ScreeningsByMonthData[];
}) {
    const [selectedTheater, setSelectedTheater] = useState(theaters[0]);
    const [screeningSelected, setScreeningSelected] =
        useState<Screening | null>(null);
    console.log({ movie });
    // console.log(screenings[0].days[0].format_audios[0].times[0].start_time_12h);

    return (
        <MainLayout>
            <HeroDetails imgPath={movie.random_bg} movie={movie} />

            <div className="container mx-auto grid grid-cols-9 gap-10">
                <div className="col-span-6">
                    <BoxWraper>
                        <section>
                            <div className="p-10">
                                <Title className="mb-6">Information</Title>
                                <p className="font-inter text-foreground">
                                    {movie.overview}
                                </p>
                            </div>
                            <div className="grid grid-cols-3 gap-10 p-10">
                                <div className="border-t border-muted pt-4">
                                    <Subtitle className="">Director</Subtitle>
                                    <p className="text-lg font-bold">
                                        {movie.director
                                            .map((d) => d.name)
                                            .join(', ')}
                                    </p>
                                </div>
                                <div className="border-t border-muted pt-4">
                                    <Subtitle className="font-bold uppercase">
                                        Genero
                                    </Subtitle>
                                    <p className="text-lg font-bold">
                                        {movie.genres.join(', ')}
                                    </p>
                                </div>
                                <div className="border-t border-muted pt-4">
                                    <Subtitle className="font-bold uppercase">
                                        Duración
                                    </Subtitle>
                                    <p className="text-lg font-bold">
                                        {movie.runtime}
                                    </p>
                                </div>
                            </div>
                        </section>
                    </BoxWraper>
                </div>
                <div className="col-span-3">
                    {screenings.length > 0 ? (
                        <section className="rounded-xl bg-card p-10">
                            <Subtitle>Teatro</Subtitle>
                            <Listbox
                                value={selectedTheater}
                                onChange={setSelectedTheater}
                            >
                                <ListboxButton className="flex w-full cursor-pointer items-center justify-between rounded bg-background px-4 py-3 font-inter">
                                    <span className="flex items-center gap-4">
                                        <Icon
                                            className="size-6 stroke-primary"
                                            iconNode={MapPin}
                                        />
                                        <div className="flex flex-col items-start">
                                            <span className="text-base font-bold text-foreground uppercase">
                                                {selectedTheater.name}
                                            </span>
                                            <span className="text-sm text-muted-foreground">
                                                {selectedTheater.location}
                                            </span>
                                        </div>
                                    </span>
                                    <Icon
                                        className="size-6"
                                        iconNode={ChevronDown}
                                    />
                                </ListboxButton>
                                <ListboxOptions
                                    className="w-(--button-width) rounded border border-ring bg-secondary-foreground p-4 [--anchor-gap:8px]"
                                    anchor="bottom start"
                                >
                                    {theaters.map((theater) => (
                                        <ListboxOption
                                            key={theater.id}
                                            value={theater}
                                            className="group flex cursor-pointer gap-2 rounded p-2 data-focus:bg-primary"
                                        >
                                            <Icon
                                                className="invisible size-6 stroke-primary group-data-focus:stroke-white group-data-selected:visible"
                                                iconNode={Check}
                                            />
                                            {theater.name}
                                        </ListboxOption>
                                    ))}
                                </ListboxOptions>
                            </Listbox>
                            {/* <Subtitle className="mt-4 font-inter font-bold uppercase">
                                Selecciona fecha
                            </Subtitle> */}
                            <div className="flex flex-col gap-6 overflow-x-auto scroll-auto">
                                <Tabs
                                    tabs={screenings}
                                    screeningSelected={screeningSelected}
                                    onScreeningSelect={setScreeningSelected}
                                />
                                <Link
                                    className="flex cursor-pointer items-center justify-center gap-2 rounded bg-primary py-4 font-inter text-xl font-bold text-white uppercase hover:bg-primary/80 disabled:cursor-not-allowed disabled:bg-primary/40"
                                    disabled={!screeningSelected}
                                    as="button"
                                    href={
                                        screeningSelected
                                            ? select({
                                                  slug: movie.slug,
                                                  screening:
                                                      screeningSelected.id,
                                              })
                                            : '#'
                                    }
                                >
                                    <Icon
                                        className="size-10"
                                        iconNode={Armchair}
                                    />
                                    Eligir asiento
                                </Link>
                            </div>
                        </section>
                    ) : (
                        <section className="rounded-xl border border-primary bg-primary-foreground p-10 backdrop-blur-lg">
                            <h1 className="mb-4 text-center font-bebas-neue text-4xl uppercase">
                                No hay funciones disponibles
                            </h1>
                        </section>
                    )}
                </div>
            </div>
            <Footer />
        </MainLayout>
    );
}
