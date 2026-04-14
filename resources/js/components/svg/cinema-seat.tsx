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
    row: string;
    number: string;
    seat: Seat;
    walkable?: boolean;
    isSelected?: boolean;
    onSeatClick: (seat: Seat) => void;
    // ✅ className ya está incluido automáticamente
} & SVGProps<SVGSVGElement>;

const CinemaSeat = ({
    row,
    number,
    seat,
    walkable,
    className,
    isSelected = false,
    onSeatClick,
    ...props
}: CinemaSeatProps) => {
    console.log({ row, number });

    const handleClick = () => {
        if (!walkable) {
            onSeatClick(seat);
        }
    };

    return (
        <div className="flex items-center justify-center">
            {!walkable ? (
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
// Función de comparación personalizada
const areEqual = (prevProps: CinemaSeatProps, nextProps: CinemaSeatProps) => {
    // Solo re-renderiza si isSelected realmente CAMBIÓ de true a false o viceversa
    if (prevProps.isSelected !== nextProps.isSelected) {
        return false; // false = SÍ necesita re-renderizar
    }

    // Si isSelected es igual, compara otras props que realmente importan
    if (prevProps.walkable !== nextProps.walkable) {
        return false;
    }

    // Si el seat.id es diferente (no debería pasar, pero por seguridad)
    if (prevProps.seat.id !== nextProps.seat.id) {
        return false;
    }

    // La función onSeatClick está memorizada, no debería cambiar
    // Si todo es igual, NO re-renderices
    return true; // true = NO necesita re-renderizar
};

export default memo(CinemaSeat, areEqual);
