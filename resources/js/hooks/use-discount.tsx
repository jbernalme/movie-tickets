import { AppliedDiscount } from '@/types/seat';
import axios from 'axios';
import { useState } from 'react';

export function useDiscount() {
    const [discountCode, setDiscountCode] = useState('');
    const [isDiscountValid, setIsDiscountValid] = useState(false);
    const [appliedDiscount, setAppliedDiscount] =
        useState<AppliedDiscount | null>(null);
    const [error, setError] = useState<string | null>(null);
    const [isValidating, setIsValidating] = useState(false);
    const [isAnimating, setIsAnimating] = useState(false);

    const validateDiscount = async (code: string, subtotal: number) => {
        // No validar si está vacío
        if (!code.trim()) {
            setError('Ingresa un código de descuento');
            return;
        }

        setIsValidating(true);
        setError(null);

        try {
            const response = await axios.post('/api/discounts/validate', {
                code,
                subtotal,
            });

            if (response.data.valid) {
                setIsAnimating(true);
                setTimeout(() => setIsAnimating(false), 600);
                setIsDiscountValid(true);
                setAppliedDiscount({
                    amount: response.data.discountData.amount,
                    code: response.data.discountData.code,
                    type: response.data.discountData.type,
                    discount_amount: response.data.discount_amount,
                    subtotal: response.data.subtotal,
                    total: response.data.total,
                });
                setDiscountCode(response.data.discountData.code);
            } else {
                setError(response.data.message);
            }
        } catch (error) {
            console.error('Error validating discount:', error);
            setError('Error al validar el código de descuento');
        } finally {
            setIsValidating(false);
        }
    };

    const removeDiscount = () => {
        setDiscountCode('');
        setAppliedDiscount(null);
        setError(null);
    };

    return {
        discountCode,
        setDiscountCode,
        isDiscountValid,
        setIsDiscountValid,
        appliedDiscount,
        setAppliedDiscount,
        error,
        isValidating,
        isAnimating,
        validateDiscount,
        removeDiscount,
    };
}
