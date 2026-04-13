import { cn } from "@/lib/utils";

export default function Subtitle({ children, className }: { children: React.ReactNode; className?: string }) {
    return <h4 className={cn("font-inter text-xs font-bold text-muted-foreground uppercase mb-4", className)}>{children}</h4>;
}