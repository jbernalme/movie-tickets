import { Field, Radio, RadioGroup } from '@headlessui/react';
import { useState } from 'react';

const plans = ['Startup', 'Business', 'Enterprise'];
export default function InputRadio({
    options,
    RadioComponent,
}: {
    options: string[];
    RadioComponent: React.ComponentType<{ option: string }>;
}) {
    const [selected, setSelected] = useState(plans[0]);
    return (
        <RadioGroup
            value={selected}
            onChange={setSelected}
            aria-label="Server size"
        >
            {options.map((option) => (
                <Field key={option} className="flex items-center gap-2">
                    <Radio
                        value={option}
                        className="group flex cursor-pointer rounded-lg bg-white/5 text-white shadow-md transition focus:not-data-focus:outline-none data-checked:bg-white/10 data-focus:outline data-focus:outline-white"
                    >
                        <RadioComponent option={option} />
                    </Radio>
                </Field>
            ))}
        </RadioGroup>
    );
}
