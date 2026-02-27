<?php

namespace App\Data;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ScreeningsByTimeData extends Data
{
    public function __construct(
        public string $start_time_24h, // "19:00"
        public string $start_time_12h, // "7:00 PM"
        public int $total,
        public ScreeningData $screening,
    ) {}
}

class ScreeningsByFormatAudioData extends Data
{
    public function __construct(
        public string $format, // "2D"
        public string $audio, // "subtitles"
        public string $label, // "2D Subtitulada"
        public int $total,
        /** @var array<ScreeningsByTimeData> */
        public array $times,
    ) {}
}

class ScreeningsByDayData extends Data
{
    public function __construct(
        public string $date, // "2026-01-30"
        public string $date_name, // "30 de enero"
        public string $day_of_week, // "viernes"
        public int $total,
        /** @var array<ScreeningsByFormatAudioData> */
        public array $format_audios,
    ) {}
}

class ScreeningsByMonthData extends Data
{
    public function __construct(
        public string $month, // "2026-01"
        public string $month_name, // "enero 2026"
        public int $total,
        /** @var array<ScreeningsByDayData> */
        public array $days,
    ) {}

    public static function fromScreeningsCollection(
        Collection $screenings,
    ): array {
        return $screenings
            ->groupBy(fn($screening) => $screening->start_time->format('Y-m'))
            ->map(function ($monthScreenings, $monthKey) {
                // Agrupar por día dentro del mes
                $dayGroups = $monthScreenings
                    ->groupBy(
                        fn($screening) => $screening->start_time->format(
                            'Y-m-d',
                        ),
                    )
                    ->map(function ($dayScreenings, $dayKey) {
                        $date = Carbon::parse($dayKey)->locale('es');

                        // Agrupar por formato + audio combinados
                        $formatAudioGroups = $dayScreenings
                            ->groupBy(
                                fn($screening) => $screening->format .
                                    '|' .
                                    $screening->audio,
                            )
                            ->map(function ($formatAudioScreenings, $key) {
                                [$format, $audio] = explode('|', $key);

                                $audioLabels = [
                                    'dubbed' => 'Doblada',
                                    'subtitles' => 'Subtitulada',
                                    'original' => 'Original',
                                ];

                                $audioLabel =
                                    $audioLabels[$audio] ?? ucfirst($audio);
                                $label = $format . ' ' . $audioLabel;

                                // Agrupar por hora
                                $timeGroups = $formatAudioScreenings
                                    ->groupBy(
                                        fn(
                                            $screening,
                                        ) => $screening->start_time->format(
                                            'H:i',
                                        ),
                                    )
                                    ->map(function ($timeScreenings, $timeKey) {
                                        $time = Carbon::parse($timeKey);
                                        return new ScreeningsByTimeData(
                                            start_time_24h: $timeKey,
                                            start_time_12h: $time->format(
                                                'g:i A',
                                            ),
                                            total: $timeScreenings->count(),
                                            screening: ScreeningData::from(
                                                $timeScreenings->first(),
                                            ),
                                        );
                                    })
                                    ->values()
                                    ->all();

                                return new ScreeningsByFormatAudioData(
                                    format: $format,
                                    audio: $audio,
                                    label: $label,
                                    total: $formatAudioScreenings->count(),
                                    times: $timeGroups,
                                );
                            })
                            ->values()
                            ->all();

                        return new ScreeningsByDayData(
                            date: $dayKey,
                            date_name: $date->translatedFormat('j \d\e F'),
                            day_of_week: $date->translatedFormat('l'),
                            total: $dayScreenings->count(),
                            format_audios: $formatAudioGroups,
                        );
                    })
                    ->values()
                    ->all();

                return new self(
                    month: $monthKey,
                    month_name: Carbon::parse($monthKey)
                        ->locale('es')
                        ->translatedFormat('F Y'),
                    total: $monthScreenings->count(),
                    days: $dayGroups,
                );
            })
            ->values()
            ->toArray();
    }
}
