import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { A11y, Autoplay, Navigation, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/react';
import type { SwiperOptions } from 'swiper/types';
import { Movie } from '../types/movie';

interface CarouselProps {
    data: Movie[];
    CardComponent: React.ComponentType<{ item: Movie }>;
    autoplay?: boolean;
    navigation?: boolean;
    pagination?: boolean;
    slidesPerView?: number;
    spaceBetween?: number;
}

export default function Carousel({
    data,
    CardComponent,
    autoplay = true,
    navigation = false,
    pagination = false,
    slidesPerView = 6,
    spaceBetween = 15,
}: CarouselProps) {
    if (!data || data.length === 0) {
        return null;
    }

    const swiperConfig: SwiperOptions = {
        modules: [A11y, Autoplay, Navigation, Pagination],
        spaceBetween,
        slidesPerView: 1,
        navigation,
        pagination: pagination ? { clickable: true } : false,
        autoplay: autoplay
            ? {
                  delay: 2500,
                  disableOnInteraction: false,
                  pauseOnMouseEnter: true,
              }
            : false,
        breakpoints: {
            350: {
                slidesPerView: 2,
                spaceBetween,
            },
            440: {
                slidesPerView: 3,
                spaceBetween,
            },
            768: {
                slidesPerView: 4,
                spaceBetween,
            },
            1024: {
                slidesPerView: slidesPerView,
                spaceBetween,
            },
        },
    };

    return (
        <Swiper {...swiperConfig}>
            {data.map((item) => (
                <SwiperSlide key={item.id}>
                    <CardComponent item={item} />
                </SwiperSlide>
            ))}
        </Swiper>
    );
}
