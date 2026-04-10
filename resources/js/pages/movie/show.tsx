import { select } from '@/actions/App/Http/Controllers/SeatController';
import Footer from '@/components/footer';
import HeroDetails from '@/components/hero-details';
import { Icon } from '@/components/icon';
import Tabs from '@/components/tabs';
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

const people = [
    { id: 1, name: 'Durward Reynolds' },
    { id: 2, name: 'Kenton Towne' },
    { id: 3, name: 'Therese Wunsch' },
    { id: 4, name: 'Benedict Kessler' },
    { id: 5, name: 'Katelyn Rohan' },
];

export default function Show({
    movie,
    screenings,
}: {
    movie: MovieDetails;
    screenings: ScreeningsByMonthData[];
}) {
    const [selectedPerson, setSelectedPerson] = useState(people[0]);
    const [screeningSelected, setScreeningSelected] =
        useState<Screening | null>(null);
    // console.log({ movie, screenings });
    // console.log(screenings[0].days[0].format_audios[0].times[0].start_time_12h);

    return (
        <MainLayout>
            <HeroDetails imgPath={movie.random_bg} movie={movie} />

            <div className="container mx-auto grid grid-cols-9 gap-10">
                <div className="col-span-6">
                    <section className="rounded-xl border border-ring bg-primary-foreground backdrop-blur-lg">
                        <div className="p-10">
                            <Title className="mb-6">Información</Title>
                            <p className="font-inter text-muted-foreground">
                                {movie.overview}
                            </p>
                        </div>
                        <div className="grid grid-cols-3 gap-10 p-10">
                            <div className="border-t border-muted pt-4">
                                <h4 className="font-bold uppercase">
                                    Director
                                </h4>
                                <p className="text-muted-foreground">
                                    {movie.director
                                        .map((d) => d.name)
                                        .join(', ')}
                                </p>
                            </div>
                            <div className="border-t border-muted pt-4">
                                <h4 className="font-bold uppercase">Genero</h4>
                                <p className="text-muted-foreground">
                                    {movie.genres}
                                </p>
                            </div>
                            <div className="border-t border-muted pt-4">
                                <h4 className="font-bold uppercase">
                                    Duración
                                </h4>
                                <p className="text-muted-foreground">
                                    {movie.runtime}
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
                <div className="col-span-3">
                    {screenings.length > 0 ? (
                        <section className="rounded-xl border border-primary bg-primary-foreground p-10 backdrop-blur-lg">
                            <h1 className="mb-4 text-center font-bebas-neue text-4xl uppercase">
                                Reserva de boletos
                            </h1>
                            <h4 className="font-inter font-bold uppercase">
                                Teatro
                            </h4>
                            <Listbox
                                value={selectedPerson}
                                onChange={setSelectedPerson}
                            >
                                <ListboxButton className="my-6 flex w-full cursor-pointer items-center justify-between rounded border border-ring bg-secondary-foreground px-4 py-3 font-inter">
                                    <span className="flex items-center gap-2">
                                        <Icon
                                            className="size-6 stroke-primary"
                                            iconNode={MapPin}
                                        />
                                        {selectedPerson.name}
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
                                    {people.map((person) => (
                                        <ListboxOption
                                            key={person.id}
                                            value={person}
                                            className="group flex cursor-pointer gap-2 rounded p-2 data-focus:bg-primary"
                                        >
                                            <Icon
                                                className="invisible size-6 stroke-primary group-data-focus:stroke-white group-data-selected:visible"
                                                iconNode={Check}
                                            />
                                            {person.name}
                                        </ListboxOption>
                                    ))}
                                </ListboxOptions>
                            </Listbox>
                            <h4 className="mb-4 font-inter font-bold uppercase">
                                Selecciona fecha
                            </h4>
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
