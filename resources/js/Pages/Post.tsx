import MainLayout from "@/Layouts/MainLayout";
import {Head} from "@inertiajs/react";
import {iListItemProps, iListProps, iPost, iViewProps, PageProps} from "@/types";
import {Button} from "@/Components/ui/button";
import {Inbox, RefreshCw} from "lucide-react";
import {useState} from "react";
import {useToast} from "@/hooks/use-toast";
import MailView from "@/Components/MailView";

export default function Post({posts}: PageProps) {
    const [postList, setPostList] = useState<iPost[]>(posts.data ?? []);
    const [selectedPost, setSelectedPost] = useState<iPost | null>(null);
    const [pagination, setPagination] = useState(posts.current_page);
    const [loading, setLoading] = useState(false);
    const {toast} = useToast();

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
                    onItemClick={console.log}
                    loadMore={console.log}
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
        <div className="flex p-6 h-full flex-col">
            <div className="z-10 relative flex items-center mb-4">
                <h2 className="text-lg font-bold">Posts</h2>
                <div className="ml-auto">
                    <Button variant="ghost" size="icon">
                        <RefreshCw className="w-5 h-5"/>
                    </Button>
                </div>
            </div>
            <div className="z-10 relative flex-1 overflow-y-auto">
                {listItems.map((post) => (
                    <PostListItem
                        key={post.id}
                        item={post}
                        selected={selectedItem?.id === post.id}
                        onClick={onItemClick}
                    />
                ))}
            </div>
            {listItems && (
                <div className="mt-4 flex justify-center">
                    <Button onClick={loadMore} disabled={isLoading || !hasMore}>
                        {isLoading ? 'Loading...' : 'Load More'}
                    </Button>
                </div>
            )}
        </div>
    );
}

function PostListItem({item, selected, onClick}: iListItemProps<iPost>) {
    return (
        <div
            className={`px-3 py-2 rounded-md cursor-pointer transition-colors ${selected ? 'bg-muted' : 'hover:bg-muted'}`}
            onClick={() => onClick(item)}
        >
            <div className="font-medium truncate">{item.title}</div>
        </div>
    );
}

function PostView({selected, loading}: iViewProps<iPost>) {
    return (
        <div className="flex-1 h-full p-6 overflow-y-auto">
            {loading ? (
                <LoadingState/>
            ) : selected ? (
                <>
                    selected
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

function NoProjectSelected() {
    return (
        <div className="flex flex-col items-center justify-center h-full">
            <Inbox className="w-16 h-16 mb-4"/>
            <h2 className="text-lg font-bold mb-2">No post selected</h2>
            <p className="text-muted-foreground">Click on a post in the sidebar to view its details.</p>
        </div>
    );
}
