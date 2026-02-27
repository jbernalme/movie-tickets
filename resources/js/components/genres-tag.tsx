import { Button } from './ui/button';

export default function GenresTag({
    genres,
    type = 'default',
}: {
    genres: string[];
    type?: 'default' | 'button';
}) {
    return type === 'button' ? (
        <div className="flex gap-2">
            {genres.map((genre) => (
                <Button key={genre} variant="secondary">
                    {genre}
                </Button>
            ))}
        </div>
    ) : (
        <div>{genres.join('/')}</div>
    );
}
