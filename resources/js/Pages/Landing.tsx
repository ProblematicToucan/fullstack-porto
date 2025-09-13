import { Button } from '@/Components/ui/button'
import MainLayout from '@/Layouts/MainLayout'
import { Head, router } from '@inertiajs/react'

export default function Landing() {
    return (
        <MainLayout>
            <Head>
                <title>Gamal Abdul Aziz</title>
                <meta name="landing" content="Gamal Abdul Aziz - Portfolio Website" />
            </Head>
            <section className="w-full min-h-screen flex items-center justify-center">
                <div className="container px-4 md:px-6">
                    <div className="grid gap-6 items-center">
                        <div className="flex flex-col justify-center space-y-4 text-center">
                            <div className="space-y-2">
                                <h1 className="text-3xl font-bold tracking-tighter sm:text-5xl xl:text-6xl xl:leading-tight bg-clip-text dark:text-transparent dark:bg-gradient-to-r dark:from-white dark:to-gray-500">
                                    Building the Future, One Line at a Time
                                </h1>
                                <p className="max-w-[600px] mx-auto">
                                    Unlock scalable, efficient, and secure solutions built with precision and performance at the core.
                                </p>
                            </div>
                            <div className="w-full max-w-sm space-y-2 mx-auto">
                                <Button onClick={() => router.visit(route('project.index'))} variant={"default"} type="button">
                                    Explore
                                </Button>
                                <p className="text-xs">
                                    Get connected to stay up to date with me on&nbsp;
                                    <a className="underline underline-offset-2" href="https://www.linkedin.com/in/gamal-abdul-aziz/">
                                        LinkedIn
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </MainLayout>
    )
}
