import React, { createContext, useEffect, useState, ReactNode } from 'react';

// Define the type for the theme context
interface ThemeContextProps {
    theme: string;
    toggleTheme: () => void;
}

// Set the default value for ThemeContext
const ThemeContext = createContext<ThemeContextProps | undefined>(undefined);

// Define props for the ThemeProvider
interface ThemeProviderProps {
    children: ReactNode;
}

// ThemeProvider component in TypeScript
export const ThemeProvider: React.FC<ThemeProviderProps> = ({ children }) => {
    const [theme, setTheme] = useState<string>(() => {
        // Check if we're in a browser environment
        if (typeof window !== 'undefined' && typeof localStorage !== 'undefined') {
            return localStorage.getItem('theme') || 'light';
        }
        // Default to 'light' for SSR
        return 'light';
    });

    useEffect(() => {
        // Only run in browser environment
        if (typeof window !== 'undefined' && typeof document !== 'undefined') {
            // Update the root class based on the theme state
            document.documentElement.classList.toggle('dark', theme === 'dark');
            // Store the theme preference
            if (typeof localStorage !== 'undefined') {
                localStorage.setItem('theme', theme);
            }
        }
    }, [theme]);

    const toggleTheme = () => {
        setTheme((prevTheme) => (prevTheme === 'dark' ? 'light' : 'dark'));
    };

    return (
        <ThemeContext.Provider value={{ theme, toggleTheme }}>
            {children}
        </ThemeContext.Provider>
    );
};

export default ThemeContext;
