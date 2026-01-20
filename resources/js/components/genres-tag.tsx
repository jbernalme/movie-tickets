export default function GenresTag({ genres }: { genres: string[] }) {
    return <div>{genres.join('/')}</div>;
}
