import { show } from '@/actions/App/Http/Controllers/MovieController';
import { Movie } from '@/types/movie';
import { Link } from '@inertiajs/react';

export function MovieCard({ item }: { item: Movie }) {
    return (
        <figure
            className={`border-kiwi group relative overflow-hidden rounded-md border-2`}
        >
            <Link href={show({ movie: 1, slug: 'test' })}>
                <img
                    src={item.poster_thumbnail}
                    alt="poster"
                    className="lazyload w-full transition duration-500 ease-in-out group-hover:opacity-50"
                />
            </Link>
            <footer className="absolute right-0 -bottom-full left-0 rounded-b-md px-4 py-2 transition-all duration-300 group-hover:bottom-0">
                <span className="text-light text-md font-semibold select-none">
                    {item.name}
                </span>
            </footer>
        </figure>
    );
}
