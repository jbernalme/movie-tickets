import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { cn } from '@/lib/utils';
import { Seat } from '@/types/screening';
import { Armchair, Footprints } from 'lucide-react';
import { memo } from 'react';
import { Icon } from '../ui/icon';

type CinemaSeatProps = {
    seat: Seat;
    isSelected?: boolean;
    onSeatClick: (seat: Seat) => void;
    className?: string;
};

const CinemaSeat = ({
    seat,
    isSelected = false,
    onSeatClick,
}: CinemaSeatProps) => {
    const { row, number, is_walkway } = seat;
    console.log({ row, number });

    const handleClick = () => {
        console.log({ seat });

        if (!is_walkway) {
            onSeatClick(seat);
        }
    };

    return (
        <div className="flex items-center justify-center">
            {!is_walkway ? (
                seat.status === 'available' ? (
                    <Tooltip>
                        <TooltipTrigger className="cursor-pointer" asChild>
                            <div onClick={handleClick}>
                                <Icon
                                    iconNode={Armchair}
                                    className={cn(
                                        'size-10 cursor-pointer stroke-1 transition-colors',
                                        'hover:text-white',

                                        isSelected
                                            ? 'text-primary'
                                            : 'text-gray-600',
                                    )}
                                />
                            </div>
                        </TooltipTrigger>

                        <TooltipContent
                            className="bg-white"
                            stylesArrow="bg-white fill-white"
                        >
                            <p className="font-jetbrains-mono font-bold text-primary uppercase">
                                {row}
                                {number}
                            </p>
                        </TooltipContent>
                    </Tooltip>
                ) : (
                    <Icon
                        iconNode={Armchair}
                        className="size-10 stroke-1 text-gold"
                    />
                )
            ) : (
                <Icon
                    iconNode={Footprints}
                    className="size-8 stroke-1 text-primary"
                />
            )}
        </div>
    );
};
// Función de comparación para memo (ahora más simple)
const areEqual = (prevProps: CinemaSeatProps, nextProps: CinemaSeatProps) => {
    // Solo re-renderiza si cambia la selección o el asiento es diferente
    if (prevProps.isSelected !== nextProps.isSelected) return false;
    if (prevProps.seat.id !== nextProps.seat.id) return false;

    // onSeatClick está memorizado con useCallback, no debería cambiar
    return true;
};

export default memo(CinemaSeat, areEqual);
