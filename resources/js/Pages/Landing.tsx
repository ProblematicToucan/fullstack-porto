import MainLayout from '@/Layouts/MainLayout'
import { Head } from '@inertiajs/react'

export default function Landing() {
    return (
        <MainLayout>
            <Head title='Gamal Abdul Aziz' />
            <div>
                You're in Landing page!
            </div>
        </MainLayout>
    )
}
