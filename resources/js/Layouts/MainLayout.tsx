import Headbar from '@/Components/Headbar'
import { PropsWithChildren } from 'react'

export default function MainLayout({ children }: PropsWithChildren) {
    return (
        <div>
            <Headbar />
            <main className='mt-20 max-w-7xl mx-4 sm:mx-auto sm:px-6 lg:px-8'>{children}</main>
        </div>
    )
}
