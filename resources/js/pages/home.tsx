import Carousel from '@/components/carousel';
import Footer from '@/components/footer';
import Hero from '@/components/hero';
import { Icon } from '@/components/icon';
import { MovieCard } from '@/components/movie-card';
import { Button } from '@/components/ui/button';
import Title from '@/components/ui/title';
import MainLayout from '@/layouts/main-layout';
import { BreadcrumbItem } from '@/types';
import { MoviesData } from '@/types/movie';
import { ChevronRight } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '/',
    },
];

interface HomePageProps {
    movies: MoviesData;
}

export default function Home({ movies }: HomePageProps) {
    console.log(movies.upcoming.results[0].genres);
    return (
        <MainLayout breadcrumbs={breadcrumbs}>
            <Hero
                imgPath={movies.trending.results[1].backdrop_path}
                movie={movies.upcoming.results[1]}
            />

            <section className="pb-20">
                <div className="container mx-auto">
                    <div className="mb-6 flex justify-between">
                        <Title>Estrenos</Title>
                        <Button
                            variant="link"
                            className="cursor-pointer font-inter font-bold uppercase hover:no-underline"
                        >
                            Ver todo
                            <Icon iconNode={ChevronRight} className="size-5" />
                        </Button>
                    </div>
                    <Carousel
                        data={movies.trending.results}
                        CardComponent={MovieCard}
                    />
                </div>
            </section>
            <section className="bg-secondary py-20">
                <div className="container mx-auto">
                    <div className="mb-6 flex justify-between">
                        <Title>Próximamente</Title>
                        <Button
                            variant="link"
                            className="cursor-pointer font-inter font-bold uppercase hover:no-underline"
                        >
                            Ver todo
                            <Icon iconNode={ChevronRight} className="size-5" />
                        </Button>
                    </div>
                    <Carousel
                        data={movies.trending.results}
                        CardComponent={MovieCard}
                    />
                </div>
            </section>
            <Footer />
        </MainLayout>
    );
}
