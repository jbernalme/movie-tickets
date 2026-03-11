import { format } from '@formkit/tempo';

export const formatDate = (
    dateInput: string | Date,
    formatString: string = 'DD/MM/YYYY',
) => {
    if (!dateInput) return '';

    return format({
        date: dateInput,
        format: formatString,
        tz: 'America/Bogota',
        locale: 'es',
    });
};
