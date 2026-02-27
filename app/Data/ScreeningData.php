<?php
namespace App\Data;

use Carbon\Carbon;
use App\Models\Screening;
use Spatie\LaravelData\Data;

class ScreeningData extends Data
{
    public function __construct(
        public int $id,
        public int $movie_id,
        public int $hall_id,
        public Carbon $start_time,
        public Carbon $end_time,
        public string $format, // '2D', '3D', 'IMAX'
        public string $audio, // 'subtitles', 'dubbed'
        public string $status,
    ) {
        // 'upcoming', 'ongoing', 'finished'
    }

    public static function fromModel(Screening $screening): self
    {
        return new self(
            id: $screening->id,
            movie_id: $screening->movie_id,
            hall_id: $screening->hall_id,
            start_time: $screening->start_time,
            end_time: $screening->end_time,
            format: $screening->format,
            audio: $screening->audio,
            status: $screening->status,
        );
    }

    public function with(): array
    {
        return [
            // Formateo para display
            'display' => [
                'date' => $this->start_time
                    ->locale('es')
                    ->isoFormat('D [de] MMMM'),
                'time' => $this->start_time->format('H:i'),
                'duration' =>
                    $this->start_time->diffInMinutes($this->end_time) . ' min',
                'format_badge' => $this->format,
                'audio_label' =>
                    $this->audio === 'subtitles' ? 'Subtitulada' : 'Doblada',
            ],

            // Estados
            'state' => [
                'is_upcoming' => $this->status === 'upcoming',
                'is_ongoing' => $this->status === 'ongoing',
                'is_finished' => $this->status === 'finished',
                'is_today' => $this->start_time->isToday(),
                'starts_in' => $this->start_time->diffForHumans(),
            ],

            // URLs
            // 'urls' => [
            //     'book' => route('screenings.book', $this->id),
            //     'details' => route('screenings.show', $this->id),
            // ],
        ];
    }
}
