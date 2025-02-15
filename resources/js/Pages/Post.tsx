import MainLayout from "@/Layouts/MainLayout";
import {Head} from "@inertiajs/react";
import {ResizableHandle, ResizablePanel, ResizablePanelGroup} from "@/Components/ui/resizable";

export default function Post() {
    return (
        <MainLayout>
            <Head>
                <title>Post</title>
                <meta name="post" content="Post page"/>
            </Head>
            <ResizablePanelGroup
                direction="horizontal"
                className="min-h-[500px] md:min-h-[600px] lg:min-h-[800px] max-h-[800px] rounded-lg shadow-md border md:min-w-[450px] mb-10"
            >
                <ResizablePanel defaultSize={25} maxSize={50}>

                </ResizablePanel>
                <ResizableHandle withHandle/>
                <ResizablePanel defaultSize={75}>

                </ResizablePanel>
            </ResizablePanelGroup>
        </MainLayout>
    );
}