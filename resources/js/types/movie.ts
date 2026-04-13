export interface Movie {
    id: number;
    imdb_id: string;
    title: string;
    original_title: string;
    slug: string;
    overview: string | null;
    backdrop_path: string;
    poster_path: string;
    poster_thumbnail: string;
    vote_average: number;
    release_date: string;
    year: string;
    genres: string[];
    tmdb_id: number;
    runtime: string;
    tagline: string;
    status: string;
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
    now_playing: Movie[];
    upcoming: Movie[];
    trending: Movie[];
}

export interface MovieDetails extends Movie {
    // Movie ya incluye: id, slug, title, overview, backdrop_path, poster_path,
    // poster_thumbnail, vote_average, release_date, year, genres

    // Propiedades adicionales específicas de MovieDetails
    tagline: string;
    runtime: string;
    imdb_link: string;
    poster_url: string;
    random_bg: string;
    credits: Credits;
    videos: Video[];
    gallery: Gallery;
    images: MovieDetailsImage[];
    backdrops: Backdrop[];
    crew: Cast[];
    director: Cast[];
    screenplay: Cast[];
    cast: Cast[];
    cast_str_list: string;
    tmdb_id: number;
    original_title: string;
    imdb_id: string;
    status: string;
}

export interface Backdrop {
    aspect_ratio: number;
    height: number;
    iso_3166_1: null | string;
    iso_639_1: null | string;
    file_path: string;
    vote_average: number;
    vote_count: number;
    width: number;
    thumbnail: string;
    w780: string;
    w1280: string;
    original: string;
    caption: string;
}

export interface Cast {
    adult: boolean;
    gender: number;
    id: number;
    known_for_department: string;
    name: string;
    original_name: string;
    popularity: number;
    profile_path: null | string;
    cast_id?: number;
    character?: string;
    credit_id: string;
    order?: number;
    department?: string;
    job?: string;
}

export interface Credits {
    cast: Cast[];
    crew: Cast[];
}

export interface Gallery {
    images: VideoElement[];
    videos: VideoElement[];
}

export interface VideoElement {
    thumbnail: string;
    source: string;
}

export interface MovieDetailsImage {
    aspect_ratio: number;
    height: number;
    iso_3166_1: null | string;
    iso_639_1: null | string;
    file_path: string;
    vote_average: number;
    vote_count: number;
    width: number;
}

export interface Video {
    iso_639_1: string;
    iso_3166_1: string;
    name: string;
    key: string;
    site: string;
    size: number;
    type: string;
    official: boolean;
    published_at: Date;
    id: string;
    url: string;
}
