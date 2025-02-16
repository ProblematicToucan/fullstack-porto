import MainLayout from "@/Layouts/MainLayout";
import {Head} from "@inertiajs/react";
import {iListItemProps, iListProps, iPost, iViewProps, PageProps} from "@/types";
import {Inbox} from "lucide-react";
import {useState} from "react";
import {useToast} from "@/hooks/use-toast";
import MailView from "@/Components/MailView";
import ListView from "@/Components/ListView";

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
