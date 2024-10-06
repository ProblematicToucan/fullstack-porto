import { iProjectMedia } from "@/types";
import { Card } from "./ui/card";
import { lazy, Suspense } from "react";

const LazyPhotoProvider = lazy(() => import('react-photo-view').then(module => ({ default: module.PhotoProvider })));
const LazyPhotoView = lazy(() => import('react-photo-view').then(module => ({ default: module.PhotoView })));

export default function ProjectMedia({ projectMedias }: { projectMedias: iProjectMedia[] }) {
    // Render nothing if projectMedias is null or an empty array
    if (!Array.isArray(projectMedias) || projectMedias.length === 0) {
        return null;
    }

    return (
        <section className="py-12">
            <div className="container mx-auto px-4">
                <h2 className="text-3xl font-bold mb-8 text-center">Project Image</h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
                    style={{ gridTemplateColumns: "repeat(auto-fill, minmax(250px, 1fr))" }}>
                    <Suspense>
                        <LazyPhotoProvider>
                            {projectMedias.map((media, index) => (
                                <Card
                                    key={index}
                                    className="min-w-[250px] max-w-fullshadow-lg transition-transform duration-300 hover:scale-105"
                                >
                                    <div className="relative">
                                        <LazyPhotoView key={index} src={media.media_url}>
                                            <img
                                                src={media.media_url}
                                                className="transition-opacity duration-300 group-hover:opacity-75"
                                            />
                                        </LazyPhotoView>
                                    </div>
                                    <div className="p-6">
                                        <p className="text-sm">{media.media_description}</p>
                                    </div>
                                </Card>
                            ))}
                        </LazyPhotoProvider>
                    </Suspense>
                </div>
            </div>
        </section>
    )
}
