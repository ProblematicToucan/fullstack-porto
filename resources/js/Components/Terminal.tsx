import { PropsWithChildren } from "react";

/**
 * v0 by Vercel.
 * @see https://v0.dev/t/xiSjIAI
 * Documentation: https://v0.dev/docs#integrating-generated-code-into-your-nextjs-app
 */
export function Terminal({ children, className }: PropsWithChildren<{ className?: string }>) {
    return (
        <aside className={`bg-black text-white p-6 rounded-lg w-full max-w-lg font-mono ${className}`}>
            <div className="flex justify-between items-center">
                <div className="flex space-x-2 text-red-500">
                    <div className="w-3 h-3 rounded-full bg-red-500" />
                    <div className="w-3 h-3 rounded-full bg-yellow-500" />
                    <div className="w-3 h-3 rounded-full bg-green-500" />
                </div>
                <p className="text-sm">bash</p>
            </div>
            <div className="mt-4">
                {children}
                <p className="text-green-400">$ <span className="cursor-pulse">_</span></p>
            </div>
        </aside>
    )
}

export function TerminalGreenLine({ children }: { children: React.ReactNode }) {
    return (
        <p className="text-green-400">{children}</p>
    );
}

export function TerminalWhiteLine({ children }: { children: React.ReactNode }) {
    return (
        <p className="text-white">{children}</p>
    );
}
