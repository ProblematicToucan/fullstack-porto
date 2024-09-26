import {
    Calculator,
    CreditCard,
    House,
    Moon,
    PanelsTopLeft,
    Settings,
    Sun,
    User,
} from "lucide-react"
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
    CommandSeparator,
    CommandShortcut,
} from "@/Components/ui/command"

import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/Components/ui/collapsible"
import { useContext, useEffect, useRef, useState } from "react"
import { router } from "@inertiajs/react";
import ThemeContext from "@/Theme/ThemeContext";

export default function CommandMenu() {
    const [isOpen, setIsOpen] = useState(false);
    const inputRef = useRef<HTMLInputElement>(null);
    const themeContext = useContext(ThemeContext);

    // If the context is undefined, it means the component is used outside the provider
    if (!themeContext) {
        throw new Error('ThemeToggleButton must be used within a ThemeProvider');
    }
    const { theme, toggleTheme } = themeContext;


    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (inputRef.current && !inputRef.current.contains(event.target as Node)) {
                setIsOpen(false)
            }
        }

        document.addEventListener("mousedown", handleClickOutside)
        return () => {
            document.removeEventListener("mousedown", handleClickOutside)
        }
    }, []);

    const handleItemClick = (path: string) => {
        router.visit(route(path))
    }

    return (
        <Command ref={inputRef} className="rounded-lg border shadow-md md:min-w-[450px]">
            <Collapsible
                open={isOpen}
                className="sticky w-full">
                <CollapsibleTrigger asChild>
                    <CommandInput onFocus={() => setIsOpen(true)} className="border-none focus:ring-0 flex-grow" placeholder="Type a command or search..." />
                </CollapsibleTrigger>
                <CollapsibleContent>
                    <CommandList>
                        <CommandEmpty>No results found.</CommandEmpty>
                        <CommandGroup heading="Suggestions">
                            <CommandItem onSelect={() => handleItemClick('landing')} disabled={route().current('landing')}>
                                <House className="mr-2 h-4 w-4" />
                                <span>Home</span>
                            </CommandItem>
                            <CommandItem onSelect={() => handleItemClick('project.index')} disabled={route().current('project.index')}>
                                <PanelsTopLeft className="mr-2 h-4 w-4" />
                                <span>Projects</span>
                            </CommandItem>
                            <CommandItem>
                                <Calculator className="mr-2 h-4 w-4" />
                                <span>Calculator</span>
                            </CommandItem>
                        </CommandGroup>
                        <CommandSeparator />
                        <CommandGroup heading="Settings">
                            <CommandItem>
                                <User className="mr-2 h-4 w-4" />
                                <span>Profile</span>
                                <CommandShortcut>⌘P</CommandShortcut>
                            </CommandItem>
                            <CommandItem>
                                <CreditCard className="mr-2 h-4 w-4" />
                                <span>Billing</span>
                                <CommandShortcut>⌘B</CommandShortcut>
                            </CommandItem>
                            <CommandItem>
                                <Settings className="mr-2 h-4 w-4" />
                                <span>Settings</span>
                                <CommandShortcut>⌘S</CommandShortcut>
                            </CommandItem>
                            <CommandItem onSelect={toggleTheme}>
                                <span className="flex items-center">
                                    {theme === 'dark' ? (
                                        <Sun className="mr-2 h-4 w-4" />
                                    ) : (
                                        <Moon className="mr-2 h-4 w-4" />
                                    )}
                                    {theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
                                </span>
                                <CommandShortcut>⌘D</CommandShortcut>
                            </CommandItem>
                        </CommandGroup>
                    </CommandList>
                </CollapsibleContent>
            </Collapsible>
        </Command>
    )
}
