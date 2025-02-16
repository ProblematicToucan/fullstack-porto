import {Button} from "@/Components/ui/button";
import {RefreshCw} from "lucide-react";
import {ReactNode} from "react";

type ListViewProps<T> = {
    title: string;
    listItems: T[];
    selectedItem?: T | null;
    onItemClick: (item: T) => void;
    loadMore: () => void;
    hasMore: boolean;
    isLoading: boolean;
    renderItem: (item: T, selected: boolean, onClick: () => void) => ReactNode;
};

export default function ListView<T extends { id: number }>({
                                                               title,
                                                               listItems,
                                                               selectedItem,
                                                               onItemClick,
                                                               loadMore,
                                                               hasMore,
                                                               isLoading,
                                                               renderItem
                                                           }: ListViewProps<T>) {
    return (
        <div className="flex p-6 h-full flex-col">
            <div className="z-10 relative flex items-center mb-4">
                <h2 className="text-lg font-bold">{title}</h2>
                <div className="ml-auto">
                    <Button variant="ghost" size="icon">
                        <RefreshCw className="w-5 h-5"/>
                    </Button>
                </div>
            </div>
            <div className="z-10 relative flex-1 overflow-y-auto">
                {listItems.map((item) =>
                    renderItem(item, selectedItem?.id === item.id, () => onItemClick(item))
                )}
            </div>
            {listItems.length > 0 && (
                <div className="mt-4 flex justify-center">
                    <Button onClick={loadMore} disabled={isLoading || !hasMore}>
                        {isLoading ? 'Loading...' : 'Load More'}
                    </Button>
                </div>
            )}
        </div>
    );
}
