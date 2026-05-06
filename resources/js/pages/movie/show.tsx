import { select } from '@/actions/App/Http/Controllers/SeatController';
import BoxWraper from '@/components/box-wraper';
import ButtonMt from '@/components/button-mt';
import Footer from '@/components/footer';
import HeroDetails from '@/components/hero-details';
import { Icon } from '@/components/icon';
import Subtitle from '@/components/subtitle';
import Tabs from '@/components/tabs';
import Title from '@/components/title';
import MainLayout from '@/layouts/main-layout';
import { MovieDetails } from '@/types/movie';
import { Screening, ScreeningsByMonthData } from '@/types/screening';
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/react';
import { Armchair, Check, ChevronDown, Info, MapPin } from 'lucide-react';
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
                            <Subtitle className="mb-4">Teatro</Subtitle>
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
                            <div className="mt-4 flex flex-col gap-4">
                                <Tabs
                                    tabs={screenings}
                                    screeningSelected={screeningSelected}
                                    onScreeningSelect={setScreeningSelected}
                                />
                                {screeningSelected ? (
                                    <ButtonMt
                                        fullWidth
                                        href={
                                            select({
                                                slug: movie.slug,
                                                screening: screeningSelected.id,
                                            }).url
                                        }
                                    >
                                        <Icon
                                            className="size-6"
                                            iconNode={Armchair}
                                        />
                                        Eligir asiento
                                    </ButtonMt>
                                ) : (
                                    <span className="flex items-center justify-center gap-2 text-center text-lg text-muted-foreground">
                                        <Icon
                                            className="size-6"
                                            iconNode={Info}
                                        />
                                        Selecciona una función
                                    </span>
                                )}
                            </div>
                        </section>
                    ) : (
                        <section className="rounded-xl border border-primary bg-primary-foreground py-6">
                            <h1 className="text-center font-bebas-neue text-4xl uppercase">
                                No hay funciones disponibles
                            </h1>
                            <p className="text-center text-lg">
                                Por favor, intenta más tarde
                            </p>
                        </section>
                    )}
                </div>
            </div>
            <Footer />
        </MainLayout>
    );
}
