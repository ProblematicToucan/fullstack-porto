import { iTechStack } from '@/types';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card'

export default function TechStack({ techStack }: { techStack: iTechStack[] }) {
    // Render nothing if techStack is null or an empty array
    if (!Array.isArray(techStack) || techStack.length === 0) {
        return null;
    }

    return (
        <section className="py-12 bg-background">
            <div className="container mx-auto px-4">
                <h2 className="text-3xl font-bold text-center mb-8">Tech Stack Used in This Project</h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
                    style={{ gridTemplateColumns: "repeat(auto-fill, minmax(250px, 1fr))" }}>
                    {techStack.map((tech, index) => (
                        <Card key={index} className='min-w-[250px] max-w-full'>
                            <CardHeader className="flex flex-row items-center gap-4">
                                <img src={tech.logo} alt={tech.name} className="w-8 h-8" />
                                <CardTitle>{tech.name}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <CardDescription>{tech.description}</CardDescription>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            </div>
        </section>
    )
}
