import { cn } from '@/lib/utils';
import { Screening, ScreeningsByMonthData } from '@/types/screening';
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from '@headlessui/react';
import { Clock } from 'lucide-react';
import { Icon } from './ui/icon';

const tabStyles =
    'rounded border border-ring cursor-pointer bg-secondary-foreground data-selected:border-primary data-selected:bg-primary';

interface TabsProps {
    tabs: ScreeningsByMonthData[];
    screeningSelected: Screening | null;
    onScreeningSelect: (screening: Screening) => void;
}

export default function Tabs({
    tabs,
    screeningSelected,
    onScreeningSelect,
}: TabsProps) {
    const handleScreeningSelect = (screening: Screening) => {
        onScreeningSelect(screening);
    };
    return (
        <TabGroup>
            <TabList className="flex gap-2">
                {tabs.map((tab, index) => (
                    <Tab
                        key={index}
                        className={cn(
                            'flex items-center gap-2 px-4 py-2 font-bold capitalize',
                            tabStyles,
                        )}
                    >
                        {tab.month_name.split(' ')[0]}
                    </Tab>
                ))}
            </TabList>
            <TabPanels>
                {tabs.map((tab, index) => (
                    <TabPanel key={index}>
                        {/* nested tab */}
                        <TabGroup className="mt-4">
                            {/* Dias */}
                            <TabList className="flex gap-2">
                                {tab.days.map((day, index) => (
                                    <Tab
                                        className={cn(
                                            'flex w-20 flex-col px-2 py-4',
                                            tabStyles,
                                        )}
                                        key={index}
                                    >
                                        <span className="uppercase">
                                            {day.day_of_week.slice(0, 3)}
                                        </span>
                                        <span className="text-2xl font-bold">
                                            {day.date.split('-')[2]}
                                        </span>
                                    </Tab>
                                ))}
                            </TabList>
                            <TabPanels>
                                {tab.days.map((day, index) => (
                                    <TabPanel key={index}>
                                        {/* Formato y Audio */}
                                        <h4 className="my-4 font-inter font-bold uppercase">
                                            Formato y Audio
                                        </h4>
                                        <TabGroup className="mt-4">
                                            <TabList className="flex gap-2">
                                                {day.format_audios.map(
                                                    (format_audio, index) => (
                                                        <Tab
                                                            key={index}
                                                            className={cn(
                                                                'px-4 py-2 font-bold',
                                                                tabStyles,
                                                            )}
                                                        >
                                                            {format_audio.label}
                                                        </Tab>
                                                    ),
                                                )}
                                            </TabList>
                                            <h4 className="my-4 font-inter font-bold uppercase">
                                                Horarios
                                            </h4>
                                            <TabPanels className="mt-4 flex flex-wrap gap-2">
                                                {/* Hora */}
                                                {day.format_audios.map(
                                                    (format_audio, index) => (
                                                        <TabPanel
                                                            className="flex flex-wrap gap-2"
                                                            key={index}
                                                        >
                                                            {format_audio.times.map(
                                                                (
                                                                    time,
                                                                    index,
                                                                ) => (
                                                                    <div
                                                                        onClick={() =>
                                                                            handleScreeningSelect(
                                                                                time.screening,
                                                                            )
                                                                        }
                                                                        className={cn(
                                                                            'flex cursor-pointer items-center gap-2 rounded border border-gold bg-gold/20 px-4 py-2 font-bold text-gold data-selected:border-primary',
                                                                            screeningSelected?.id ===
                                                                                time
                                                                                    .screening
                                                                                    .id &&
                                                                                'border-primary bg-primary text-white',
                                                                        )}
                                                                        key={
                                                                            index
                                                                        }
                                                                    >
                                                                        <Icon
                                                                            className="size-5"
                                                                            iconNode={
                                                                                Clock
                                                                            }
                                                                        />
                                                                        {
                                                                            time.start_time_12h
                                                                        }
                                                                    </div>
                                                                ),
                                                            )}
                                                        </TabPanel>
                                                    ),
                                                )}
                                            </TabPanels>
                                        </TabGroup>
                                    </TabPanel>
                                ))}
                            </TabPanels>
                        </TabGroup>
                    </TabPanel>
                ))}
            </TabPanels>
        </TabGroup>
    );
}
