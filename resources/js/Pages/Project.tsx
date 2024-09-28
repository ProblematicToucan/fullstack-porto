import MainLayout from '@/Layouts/MainLayout';
import { Button } from '@/Components/ui/button';
import { Head, router } from '@inertiajs/react';
import { Forward, Inbox, RefreshCw, Reply } from 'lucide-react';
import { useCallback, useState } from 'react';
import { iProject, PageProps } from '@/types';

interface iProjectListProps {
    projects: { data: iProject[] };
    selectedProject: iProject | null;
    onProjectClick: (project: iProject) => void;
}

interface iProjectViewProps {
    selectedProject: iProject | null;
    loading: boolean;
    error: string | null;
}

export default function Project({ projects }: PageProps) {
    const [selectedProject, setSelectedProject] = useState<iProject | null>(null)
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const handleProjectClick = useCallback(async (project: iProject) => {
        setLoading(true);
        setError(null);
        try {
            const { data } = await window.axios.get<iProject>(`/project/${project.slug}`);
            setSelectedProject(data);
        } catch (error) {
            console.error('Error fetching project data:', error);
            setError('Failed to load project data.');
        } finally {
            setLoading(false);
        }
    }, []);

    return (
        <MainLayout>
            <Head title='Projects' />
            <div className="flex h-[800px] border shadow-md md:min-w-[450px]">
                <ProjectList
                    projects={projects}
                    selectedProject={selectedProject}
                    onProjectClick={handleProjectClick}
                />
                <ProjectView selectedProject={selectedProject} loading={loading} error={error} />
            </div>
        </MainLayout>
    );
}

function ProjectList({
    projects,
    selectedProject,
    onProjectClick,
}: iProjectListProps) {
    return (
        <div className="border-r w-64 p-4 overflow-y-auto md:w-[260px] lg:w-64 flex flex-col">
            <div className="flex items-center mb-4">
                <h2 className="text-lg font-bold">Projects</h2>
                <div className="ml-auto">
                    <Button variant="ghost" size="icon">
                        <RefreshCw className="w-5 h-5" />
                    </Button>
                </div>
            </div>
            <div className="flex-1 overflow-y-auto">
                {projects.data.map((project) => (
                    <div
                        key={project.id}
                        className={`px-3 py-2 rounded-md cursor-pointer transition-colors
                            ${selectedProject?.id === project.id ? 'bg-muted' : 'hover:bg-muted'}`}
                        onClick={() =>
                            project.id === selectedProject?.id ? null : onProjectClick(project)
                        }
                    >
                        <div className="font-medium">{project.title}</div>
                        <div className="text-sm text-muted-foreground truncate">{project.category_names}</div>
                        <div className="text-xs text-muted-foreground truncate">{project.repo_url}</div>
                    </div>
                ))}
            </div>
        </div>
    );
}

function ProjectView({ selectedProject, loading, error }: iProjectViewProps) {
    return (
        <div className="flex-1 p-6 overflow-y-auto">
            {loading ? (
                <div className="flex items-center justify-center h-full">
                    <h2 className="text-lg font-bold">Loading...</h2>
                </div>
            )
                : error ? (
                    <div className="flex items-center justify-center h-full">
                        <span className="text-lg font-bold">{error}</span>
                    </div>
                ) : selectedProject ? (
                    <>
                        <div className="flex items-center mb-4">
                            <h2 className="text-lg font-bold">{selectedProject.title}</h2>
                            <div className="ml-auto">
                                <Button variant="ghost" size="icon">
                                    <Reply className="w-5 h-5" />
                                </Button>
                                <Button variant="ghost" size="icon">
                                    <Forward className="w-5 h-5" />
                                </Button>
                            </div>
                        </div>
                        <div className="mb-4">
                            <div className="font-medium">{selectedProject.category_names}</div>
                            <div className="text-sm text-muted-foreground">{selectedProject.repo_url}</div>
                        </div>
                        <div className="prose prose-lg max-w-none whitespace-pre-wrap">{selectedProject.slug}</div>
                    </>
                ) : (
                    <div className="flex flex-col items-center justify-center h-full">
                        <Inbox className="w-16 h-16 mb-4" />
                        <h2 className="text-lg font-bold mb-2">No project selected</h2>
                        <p className="text-muted-foreground">Click on a project in the sidebar to view its details.</p>
                    </div>
                )}
        </div>
    );
}
