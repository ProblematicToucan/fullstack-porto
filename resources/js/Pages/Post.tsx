import MainLayout from "@/Layouts/MainLayout";
import {Head, router} from "@inertiajs/react";
import {iListItemProps, iListProps, iPost, iViewProps, PageProps} from "@/types";
import {Calendar, Forward, Inbox, Reply} from "lucide-react";
import {useCallback, useState} from "react";
import {useToast} from "@/hooks/use-toast";
import MailView from "@/Components/MailView";
import ListView from "@/Components/ListView";
import {Button} from "@/Components/ui/button";
import SeoContent from "@/Components/SeoContent";

export default function Post({posts}: PageProps) {
    const [postList, setPostList] = useState<iPost[]>(posts.data ?? []);
    const [selectedPost, setSelectedPost] = useState<iPost | null>(null);
    const [pagination, setPagination] = useState(posts.current_page);
    const [loading, setLoading] = useState(false);
    const {toast} = useToast();

    const handlePostClick = useCallback(async (post: iPost) => {
        if (post.id === selectedPost?.id) return;

        setLoading(true);
        try {
            const {data} = await window.axios.get<iPost>(`/post/${post.slug}`);
            setSelectedPost(data);
        } catch (error) {
            console.error('Error fetching post data:', error);
            toast({
                variant: "destructive",
                title: "Uh oh! Something went wrong.",
                description: "There was a problem with your request.",
            });
        } finally {
            setLoading(false);
        }
    }, [selectedPost, toast]);

    const handleLoadMore = useCallback(() => {
        if (pagination >= posts.last_page) return;

        setLoading(true);
        router.get('/project', {page: pagination + 1}, {
            preserveState: true,
            replace: true,
            onSuccess: (pageProps) => {
                setPostList(prev => [...prev, ...pageProps.props.posts.data]);
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
    }, [postList, pagination, toast]);

    return (
        <MainLayout>
            <Head>
                <title>Post</title>
                <meta name="post" content="Post page"/>
            </Head>
            <MailView>
                <PostList
                    listItems={postList}
                    selectedItem={selectedPost}
                    onItemClick={handlePostClick}
                    loadMore={handleLoadMore}
                    hasMore={posts.current_page < posts.last_page}
                    isLoading={loading}
                />
                <PostView selected={selectedPost} loading={loading}/>
            </MailView>
        </MainLayout>
    );
}

function PostList({listItems, selectedItem, onItemClick, loadMore, hasMore, isLoading}: iListProps<iPost>) {
    return (
        <ListView
            title="Posts"
            listItems={listItems}
            selectedItem={selectedItem}
            onItemClick={onItemClick}
            loadMore={loadMore}
            hasMore={hasMore}
            isLoading={isLoading}
            renderItem={(post, selected, onClick) => (
                <PostListItem key={post.id} item={post} selected={selected} onClick={onClick}/>
            )}>

        </ListView>
    );
}

function PostListItem({item, selected, onClick}: iListItemProps<iPost>) {
    return (
        <div
            className={`px-3 py-2 rounded-md cursor-pointer transition-colors ${selected ? 'bg-muted' : 'hover:bg-muted'}`}
            onClick={() => onClick(item)}
        >
            <div className="font-medium truncate">{item.title}</div>
            <div className="text-sm text-muted-foreground truncate">{item.updated_at}</div>
        </div>
    );
}

function PostView({selected, loading}: iViewProps<iPost>) {
    return (
        <div className="flex-1 h-full p-6 overflow-y-auto">
            {loading ? (
                <LoadingState/>
            ) : selected ? (
                <PostDetails post={selected}/>
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

function PostDetails({post}: { post: iPost }) {
    return (
        <>
            <div className="flex items-center mb-4">
                <h1 className="text-5xl font-bold w-3/4 min-w-min">{post.title}</h1>
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
                <div className="font-medium flex items-center gap-2">
                    <Calendar className="w-5 h-5"/>
                    <span>{post.updated_at}</span>
                </div>
            </div>
            <div className="max-w-none whitespace-pre-wrap">
                <SeoContent content={post.content}/>
            </div>
        </>
    );
}

function NoProjectSelected() {
    return (
        <div className="flex flex-col items-center justify-center h-full">
            <Inbox className="w-16 h-16 mb-4"/>
            <h2 className="text-lg font-bold mb-2">No post selected</h2>
            <p className="text-muted-foreground">Click on a post in the sidebar to view its details.</p>
        </div>
    );
}
