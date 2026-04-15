import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { cn } from '@/lib/utils';
import { Seat } from '@/types/screening';
import { Footprints } from 'lucide-react';
import { memo, SVGProps } from 'react';
import { Icon } from '../ui/icon';

type CinemaSeatProps = {
    seat: Seat;
    isSelected?: boolean;
    onSeatClick: (seat: Seat) => void;
    // ✅ className ya está incluido automáticamente
} & SVGProps<SVGSVGElement>;

const CinemaSeat = ({
    seat,
    className,
    isSelected = false,
    onSeatClick,
    ...props
}: CinemaSeatProps) => {
    const { row, number, is_walkway } = seat;
    console.log({ row, number });

    const handleClick = () => {
        if (!is_walkway) {
            onSeatClick(seat);
        }
    };

    return (
        <div className="flex items-center justify-center">
            {!is_walkway ? (
                <Tooltip>
                    <TooltipTrigger className="cursor-pointer" asChild>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 32 32"
                            fill="none"
                            onClick={handleClick}
                            {...props}
                            className={cn(
                                'aspect-square cursor-pointer transition-colors',
                                'hover:text-white',
                                isSelected ? 'text-primary' : 'text-gray-600',
                                className,
                            )}
                        >
                            <rect
                                width={16}
                                height={12}
                                x={8}
                                y={6}
                                fill="currentColor"
                                rx={2}
                            />
                            <rect
                                width={20}
                                height={8}
                                x={6}
                                y={18}
                                fill="currentColor"
                                rx={2}
                            />
                            <rect
                                width={3}
                                height={12}
                                x={4}
                                y={16}
                                fill="currentColor"
                                opacity={0.8}
                                rx={1}
                            />
                            <rect
                                width={3}
                                height={12}
                                x={25}
                                y={16}
                                fill="currentColor"
                                opacity={0.8}
                                rx={1}
                            />
                        </svg>
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
