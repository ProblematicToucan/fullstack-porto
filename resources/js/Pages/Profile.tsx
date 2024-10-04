import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import { ResizablePanelGroup } from '@/Components/ui/resizable'
import MainLayout from '@/Layouts/MainLayout'
import { Head } from '@inertiajs/react'
import { Github, Linkedin, Twitter } from 'lucide-react'
import React from 'react'

export default function Profile() {
    return (
        <MainLayout>
            <Head title='Bio' />
            <ResizablePanelGroup
                direction="horizontal"
                className="min-h-[500px] md:min-h-[600px] lg:min-h-[800px] max-h-[800px] rounded-lg shadow-md border md:min-w-[450px] mb-10"
            >
                <Card className='border-none flex flex-col self-center m-60'>
                    <CardHeader className="flex flex-col sm:flex-row items-center gap-4">
                        <Avatar className="w-24 h-24 sm:w-32 sm:h-32">
                            <AvatarImage alt="Profile picture" src="https://gravatar.com/avatar/a050c229c02b4e62f1c916753615ce14?size=256" />
                            <AvatarFallback>GA</AvatarFallback>
                        </Avatar>
                        <div className="text-center sm:text-left">
                            <CardTitle className="text-3xl">Gamal Abdul Aziz</CardTitle>
                            <CardDescription className="text-xl">Sofware Developer</CardDescription>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <p className="text-muted-foreground">
                            Passionate full stack developer with 5+ years of experience in building scalable web applications.
                            Specializing in React, Node.js, and cloud technologies. Always eager to learn and tackle new challenges.
                        </p>
                        <div className="flex flex-wrap justify-center sm:justify-start gap-2">
                            <Button variant="outline" size="icon" onClick={() => window.open('https://github.com/ProblematicToucan')}>
                                <Github className="h-4 w-4" />
                                <span className="sr-only">GitHub</span>
                            </Button>
                            <Button variant="outline" size="icon" onClick={() => window.open('https://www.linkedin.com/in/gamal-abdul-aziz/')}>
                                <Linkedin className="h-4 w-4" />
                                <span className="sr-only">LinkedIn</span>
                            </Button>
                            <Button variant="outline" size="icon" onClick={() => window.open('https://x.com/si_gamalaziz/')}>
                                <Twitter className="h-4 w-4" />
                                <span className="sr-only">Twitter</span>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </ResizablePanelGroup>
        </MainLayout>
    )
}
