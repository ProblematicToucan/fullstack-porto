import MainLayout from '@/Layouts/MainLayout';
import {Button} from '@/Components/ui/button';
import {Head, router} from '@inertiajs/react';
import {Forward, Inbox, Reply} from 'lucide-react';
import {lazy, Suspense, useCallback, useState} from 'react';
import {iProject, PageProps} from '@/types';
import {useToast} from '@/hooks/use-toast';
import MailView from "@/Components/MailView";
import ListView from "@/Components/ListView";
import SeoContent from "@/Components/SeoContent";

const LazyTechStack = lazy(() => import('@/Components/TechStack'));
const LazyPhotoProvider = lazy(() => import('react-photo-view').then(module => ({default: module.PhotoProvider})));
const LazyPhotoView = lazy(() => import('react-photo-view').then(module => ({default: module.PhotoView})));
const LazyProjectMedia = lazy(() => import('@/Components/ProjectMedia'));
const cdnUrl = import.meta.env.VITE_CDN_URL;

export default function Project({projects}: PageProps) {
    const [projectList, setProjects] = useState<iProject[]>(projects.data ?? []);
    const [selectedProject, setSelectedProject] = useState<iProject | null>(null);
    const [pagination, setPagination] = useState(projects.current_page);
    const [loading, setLoading] = useState(false);
    const {toast} = useToast();

    const handleProjectClick = useCallback(async (project: iProject) => {
        if (project.id === selectedProject?.id) return; // Prevent duplicate fetch

        setLoading(true);
        try {
            const {data} = await window.axios.get<iProject>(`/project/${project.slug}`);
            setSelectedProject(data);
        } catch (error) {
            console.error('Error fetching project data:', error);
            toast({
                variant: "destructive",
                title: "Uh oh! Something went wrong.",
                description: "There was a problem with your request.",
            });
        } finally {
            setLoading(false);
        }
    }, [selectedProject, toast]);

    const handleLoadMore = useCallback(() => {
        if (pagination >= projects.last_page) return;

        setLoading(true);
        router.get('/project', {page: pagination + 1}, {
            preserveState: true,
            replace: true,
            onSuccess: (pageProps) => {
                setProjects(prev => [...prev, ...pageProps.props.projects.data]);
                setPagination(pageProps.props.projects.current_page);
            },
            onError: (error) => {
                console.error('Error fetching project data:', error);
                toast({
                    variant: "destructive",
                    title: "Uh oh! Something went wrong.",
                    description: "There was a problem with your request.",
                });
                setLoading(false);
            },
            onFinish() {
                setLoading(false);
            }
        });
    }, [projectList, pagination, toast]);

    return (
        <MainLayout>
            <Head>
                <title>Projects</title>
                <meta name="project" content="Project page"/>
            </Head>
            <MailView>
                <ProjectList
                    projects={projectList}
                    selectedProject={selectedProject}
                    onProjectClick={handleProjectClick}
                    loadMore={handleLoadMore}
                    hasMore={projects.current_page < projects.last_page}
                    isLoading={loading}
                />
                <ProjectView selectedProject={selectedProject} loading={loading}/>
            </MailView>
        </MainLayout>
    );
}

interface iProjectListProps {
    projects: iProject[];
    selectedProject: iProject | null;
    onProjectClick: (project: iProject) => void;
    loadMore: () => void;
    hasMore: boolean;
    isLoading: boolean;
}

function ProjectList({projects, selectedProject, onProjectClick, loadMore, hasMore, isLoading}: iProjectListProps) {
    return (
        <ListView
            title="Project"
            listItems={projects}
            selectedItem={selectedProject}
            onItemClick={onProjectClick}
            loadMore={loadMore}
            hasMore={hasMore}
            isLoading={isLoading}
            renderItem={(project, selected, onClick) => (
                <ProjectListItem key={project.id} project={project} selected={selected} onClick={onClick}/>
            )}>

        </ListView>
    );
}

interface iProjectListItemProps {
    project: iProject;
    selected: boolean;
    onClick: (project: iProject) => void;
}

function ProjectListItem({project, selected, onClick}: iProjectListItemProps) {
    return (
        <div
            className={`px-3 py-2 rounded-md cursor-pointer transition-colors ${selected ? 'bg-muted' : 'hover:bg-muted'}`}
            onClick={() => onClick(project)}
        >
            <div className="font-medium truncate">{project.title}</div>
            <div className="text-sm text-muted-foreground truncate">{project.category_names}</div>
        </div>
    );
}

interface iProjectViewProps {
    selectedProject: iProject | null;
    loading: boolean;
}

function ProjectView({selectedProject, loading}: iProjectViewProps) {
    return (
        <div className="flex-1 h-full p-6 overflow-y-auto">
            {loading ? (
                <LoadingState/>
            ) : selectedProject ? (
                <>
                    <ProjectDetails project={selectedProject}/>
                    <Suspense fallback={<div>Loading...</div>}>
                        <LazyProjectMedia projectMedias={selectedProject.project_medias}/>
                        <LazyTechStack techStacks={selectedProject.tech_stacks}/>
                    </Suspense>
                </>
            ) : (
                <NoProjectSelected/>
            )}
        </div>
    );
}

function LoadingState() {
    return (
        <div className="flex items-center justify-center h-full">
            <h2 className="text-lg font-bold">Loading...</h2>
        </div>
    );
}

const renderUrl = (url: string | undefined): JSX.Element | string => {
    return url ? (
        <a href={url} target="_blank" rel="noopener noreferrer">
            {url}
        </a>
    ) : "-";
};

function ProjectDetails({project}: { project: iProject }) {
    return (
        <>
            <div className="flex items-center mb-4">
                <h1 className="text-5xl font-bold w-3/4 min-w-min">{project.title}</h1>
                <div className="flex ml-auto">
                    <Button variant="ghost" size="icon">
                        <Reply className="w-5 h-5"/>
                    </Button>
                    <Button variant="ghost" size="icon">
                        <Forward className="w-5 h-5"/>
                    </Button>
                </div>
            </div>
            <div className="mb-4">
                <div className="font-medium">{project.category_names}</div>
                <div className="text-sm text-muted-foreground">Project Url : {renderUrl(project.project_url)}</div>
                <div className="text-sm text-muted-foreground">Project Repo Url : {renderUrl(project.repo_url)}</div>
            </div>
            <div className="max-w-none whitespace-pre-wrap">
                <SeoContent content={project.description}/>
            </div>
        </>
    );
}

function NoProjectSelected() {
    return (
        <div className="flex flex-col items-center justify-center h-full">
            <Inbox className="w-16 h-16 mb-4"/>
            <h2 className="text-lg font-bold mb-2">No project selected</h2>
            <p className="text-muted-foreground">Click on a project in the sidebar to view its details.</p>
        </div>
    );
}
