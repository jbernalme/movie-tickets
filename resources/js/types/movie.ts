export interface Movie {
    id: number;
    title: string;
    name: string;
    slug: string;
    overview: string | null;
    backdrop_path: string;
    poster_path: string;
    poster_thumbnail: string;
    vote_average: string;
    release_date: string;
    year: string;
    genre_ids: number[];
    genres: string[];
}

export interface MovieResponse {
    results: Movie[];
    page: number;
    total_pages: number;
    total_results: number;
}

export interface Genre {
    id: number;
    name: string;
}

export interface MoviesData {
    trending: MovieResponse;
    nowPlaying: MovieResponse;
    upcoming: MovieResponse;
    topRated: MovieResponse;
    genres: Genre[];
}
