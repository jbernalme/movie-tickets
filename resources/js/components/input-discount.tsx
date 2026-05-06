import { cn } from '@/lib/utils';
import { AppliedDiscount } from '@/types/seat';
import { useForm } from '@inertiajs/react';
import { AlertCircle, Check, X } from 'lucide-react';
import { Icon } from './ui/icon';

interface inputDiscountProps {
    validateDiscount: (code: string, amount: number) => void;
    subtotal: number;
    isValidating: boolean;
    appliedDiscount: AppliedDiscount | null;
    removeDiscount: () => void;
    error: string | null;
}

export default function InputDiscount({
    validateDiscount,
    subtotal,
    isValidating,
    appliedDiscount,
    removeDiscount,
    error,
}: inputDiscountProps) {
    const { data, setData, reset } = useForm({
        code: '',
    });
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        validateDiscount(data.code, subtotal);
        reset();
    };

    const buttonDisabled = isValidating || !!appliedDiscount;
    return (
        <form onSubmit={handleSubmit}>
            <div className="mb-4 flex items-center gap-2">
                <input
                    name="code"
                    value={data.code}
                    placeholder="Ingresa tu código"
                    disabled={buttonDisabled}
                    type="text"
                    className={cn(
                        'w-full rounded border border-muted bg-muted px-4 py-2 text-sm font-bold text-muted-foreground uppercase transition-colors duration-300 focus:border-primary/50 focus:outline-none',
                        error && 'border-red-500',
                    )}
                    onChange={(e) => setData('code', e.target.value)}
                />
                <button
                    disabled={buttonDisabled}
                    className="cursor-pointer rounded border border-primary/50 bg-primary/10 px-2 py-2 text-sm font-bold text-primary uppercase transition-colors duration-300 hover:border-primary/70 hover:bg-primary/20 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:border-primary/50 disabled:hover:bg-primary/10"
                >
                    Aplicar
                </button>
            </div>
            {error && (
                <span className="flex items-center gap-2 text-red-500">
                    <Icon className="size-6" iconNode={AlertCircle} />
                    {error}
                </span>
            )}
            {appliedDiscount && (
                <>
                    <div className="mb-4 flex animate-in justify-between rounded border border-green-400 bg-green-950 p-2 text-sm text-green-400 duration-600 fade-in slide-in-from-top-2">
                        <div className="flex items-center gap-2">
                            <Icon className="size-4" iconNode={Check} />
                            <span>
                                Descuento aplicado:{' '}
                                <span className="font-bold">
                                    {appliedDiscount?.code}
                                </span>
                            </span>
                        </div>
                        <button
                            className="cursor-pointer"
                            onClick={removeDiscount}
                        >
                            <Icon className="size-4" iconNode={X} />
                        </button>
                    </div>
                    <div className="flex flex-col text-sm">
                        <div className="flex justify-between">
                            <span className="text-gray-500">Subtotal</span>
                            <span className="font-jetbrains-mono font-bold text-foreground">
                                ${subtotal}
                            </span>
                        </div>
                        <div className="flex justify-between">
                            <span className="text-gray-500">Cupón:</span>
                            <span className="font-jetbrains-mono text-green-500">
                                {appliedDiscount.type === 'percentage'
                                    ? `${appliedDiscount.amount}%`
                                    : `-$${appliedDiscount.amount}`}
                            </span>
                        </div>
                        {appliedDiscount.type === 'percentage' && (
                            <div className="flex justify-between">
                                <span className="text-gray-500">Descuento</span>
                                <span className="font-jetbrains-mono font-bold text-red-500">
                                    -${appliedDiscount.discount_amount}
                                </span>
                            </div>
                        )}
                    </div>
                </>
            )}
        </form>
    );
}
