import { Icon } from '@/components/icon';
import Tag from '@/components/tag';
import { Movie } from '@/types/movie';
import { Ticket } from 'lucide-react';
import GenresTag from './genres-tag';
import { Button } from './ui/button';

export default function Hero({
    imgPath,
    movie,
}: {
    imgPath: string;
    movie: Movie;
}) {
    return (
        <section className="relative h-[calc(100vh-64px)] overflow-hidden bg-amber-300">
            <picture className="">
                <source srcSet={imgPath} type="" media="" />
                <source srcSet="" type="" media="" />
                <img src={imgPath} alt="" loading="eager" />
            </picture>
            <div
                className="absolute inset-0"
                style={{
                    background:
                        'linear-gradient(0deg,rgba(10, 10, 10, 1) 5%, rgba(0, 0, 0, 0) 100%)',
                }}
            />
            <div className="absolute inset-0 container mx-auto flex flex-col justify-center gap-2">
                <div className="flex max-w-[60%] flex-col gap-4">
                    <div className="flex gap-2">
                        <Tag type="primary">Trending</Tag>
                        <Tag type="gold">Imax special</Tag>
                    </div>
                    <h1 className="font-bebas-neue text-6xl font-bold text-foreground">
                        {movie.title}
                    </h1>
                    <span className="line-clamp-4 font-inter text-lg text-foreground">
                        {movie.overview}
                    </span>
                    <div className="flex flex-col font-inter">
                        <GenresTag genres={movie.genres} />
                    </div>
                    <div className="flex gap-2">
                        <Button
                            className="cursor-pointer font-inter font-bold text-foreground"
                            variant="default"
                        >
                            <Icon iconNode={Ticket} />
                            Reserva tu entrada
                        </Button>
                        <Button
                            className="cursor-pointer font-inter font-bold text-foreground"
                            variant="secondary"
                        >
                            Ver más
                        </Button>
                    </div>
                </div>
            </div>
        </section>
    );
}
