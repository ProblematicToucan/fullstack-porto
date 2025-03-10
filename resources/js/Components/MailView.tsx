import {ReactNode} from "react";
import {ResizableHandle, ResizablePanel, ResizablePanelGroup} from "@/Components/ui/resizable";

type MailViewProps = {
    children: [ReactNode, ReactNode]; // Expecting exactly two children
};

export default function MailView({children}: MailViewProps) {
    const [childrenList, childrenView] = children;

    return (
        <ResizablePanelGroup
            direction="horizontal"
            className="min-h-[500px] md:min-h-[600px] lg:min-h-[800px] max-h-[800px] rounded-lg shadow-md border md:min-w-[450px] mb-10"
        >
            <ResizablePanel defaultSize={25} maxSize={50}>
                {childrenList}
            </ResizablePanel>
            <ResizableHandle withHandle/>
            <ResizablePanel defaultSize={75}>
                {childrenView}
            </ResizablePanel>
        </ResizablePanelGroup>
    );
}
