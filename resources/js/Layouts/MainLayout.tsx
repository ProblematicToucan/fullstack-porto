import Headbar from '@/Components/Headbar'
import { Toaster } from '@/Components/ui/toaster'
import { PropsWithChildren } from 'react'
import { usePage } from '@inertiajs/react'

export default function MainLayout({ children }: PropsWithChildren) {
    const { component } = usePage()
    const isLandingPage = component === 'Landing'

    return (
        <div className={isLandingPage ? 'h-screen overflow-hidden' : ''}>
            <Headbar />
            <main className={isLandingPage ? 'h-full' : 'mt-20 max-w-7xl mx-4 sm:mx-auto sm:px-6 lg:px-8'}>
                {children}
            </main>
            <Toaster />
        </div>
    )
}
