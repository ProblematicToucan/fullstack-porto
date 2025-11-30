import {
    Calculator,
    CreditCard,
    House, MessageSquareCode,
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
import { router, usePage } from "@inertiajs/react";
import ThemeContext from "@/Theme/ThemeContext";

// Safe route helper for SSR compatibility
const safeRoute = (name: string, params?: Record<string, any>): string => {
    if (typeof route !== 'undefined' && route) {
        return route(name, params);
    }
    // Fallback for SSR - return a basic path
    const routeMap: Record<string, string> = {
        'landing': '/',
        'project.index': '/project',
        'post.index': '/post',
        'bio': '/bio',
    };
    return routeMap[name] || '/';
};

// Safe route current check for SSR compatibility
const isCurrentRoute = (routeName: string, currentComponent?: string): boolean => {
    if (typeof route !== 'undefined' && route) {
        return route().current(routeName) || false;
    }
    // Fallback for SSR - check component name
    const componentMap: Record<string, string> = {
        'landing': 'Landing',
        'project.index': 'Project',
        'post.index': 'Post',
        'bio': 'Profile',
    };
    return componentMap[routeName] === currentComponent;
};

export default function CommandMenu() {
    const [isOpen, setIsOpen] = useState(false);
    const commandRef = useRef<HTMLInputElement>(null);
    const searchRef = useRef<HTMLInputElement>(null);
    const themeContext = useContext(ThemeContext);
    const { component } = usePage();

    // Throw error if not used within ThemeProvider
    if (!themeContext) {
        throw new Error('ThemeToggleButton must be used within a ThemeProvider');
    }

    const { theme, toggleTheme } = themeContext;

    const handleOutsideClick = (event: MouseEvent) => {
        if (isOpen && commandRef.current && !commandRef.current.contains(event.target as Node)) {
            setIsOpen(false);
        }
    };

    const handleKeyDown = (event: KeyboardEvent) => {
        if (event.key === "Escape" && isOpen) {
            event.preventDefault();
            setIsOpen(false);
            searchRef.current?.blur();
        }

        if (event.key === "k" && (event.metaKey || event.ctrlKey)) {
            event.preventDefault();
            searchRef.current?.focus();
        }

        if (event.key === "d" && (event.metaKey || event.ctrlKey)) {
            event.preventDefault();
            toggleTheme();
        }

        if (event.key === "p" && (event.metaKey || event.ctrlKey)) {
            event.preventDefault();
            if (isCurrentRoute('bio', component as string)) return;
            router.visit(safeRoute('bio'), {
                preserveState: true,
                replace: true,
            });
        }
    };

    useEffect(() => {
        // Only run in browser environment
        if (typeof document === 'undefined') return;

        document.addEventListener("mousedown", handleOutsideClick);
        document.addEventListener("keydown", handleKeyDown);

        return () => {
            document.removeEventListener("mousedown", handleOutsideClick);
            document.removeEventListener("keydown", handleKeyDown);

        };
    }, [isOpen]); // Only update on isOpen change

    const handleItemClick = (path: string) => {
        router.visit(safeRoute(path), {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <Command ref={commandRef} className="rounded-lg border shadow-md md:min-w-[450px]">
            <Collapsible
                open={isOpen}
                className="sticky w-full">
                <CollapsibleTrigger asChild>
                    <CommandInput ref={searchRef} onFocus={() => setIsOpen(true)} className="border-none focus:ring-0 flex-grow" placeholder="Type a command or search... [⌘K]" />
                </CollapsibleTrigger>
                <CollapsibleContent>
                    <CommandList>
                        <CommandEmpty>No results found.</CommandEmpty>
                        <CommandGroup heading="Suggestions">
                            <CommandItem onSelect={() => handleItemClick('landing')} disabled={isCurrentRoute('landing', component as string)}>
                                <House className="mr-2 h-4 w-4" />
                                <span>Home</span>
                            </CommandItem>
                            <CommandItem onSelect={() => handleItemClick('project.index')} disabled={isCurrentRoute('project.index', component as string)}>
                                <PanelsTopLeft className="mr-2 h-4 w-4" />
                                <span>Projects</span>
                            </CommandItem>
                            <CommandItem onSelect={() => handleItemClick('post.index')} disabled={isCurrentRoute('post.index', component as string)}>
                                <MessageSquareCode className="mr-2 h-4 w-4" />
                                <span>Posts</span>
                            </CommandItem>
                        </CommandGroup>
                        <CommandSeparator />
                        <CommandGroup heading="Settings">
                            <CommandItem onSelect={() => handleItemClick('bio')} disabled={isCurrentRoute('bio', component as string)}>
                                <User className="mr-2 h-4 w-4" />
                                <span>Profile</span>
                                <CommandShortcut>⌘P</CommandShortcut>
                            </CommandItem>
                            <CommandItem disabled>
                                <CreditCard className="mr-2 h-4 w-4" />
                                <span>Billing</span>
                                <CommandShortcut>⌘B</CommandShortcut>
                            </CommandItem>
                            <CommandItem disabled>
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
