import { iSeoContent } from "@/types";
import DOMPurify from "dompurify";
import { lazy, Suspense } from "react";

const LazyPhotoProvider = lazy(() => import('react-photo-view').then(module => ({ default: module.PhotoProvider })));
const LazyPhotoView = lazy(() => import('react-photo-view').then(module => ({ default: module.PhotoView })));
const cdnUrl = import.meta.env.VITE_CDN_URL || 'https://cdn.garamm.dev';

export default function SeoContent({ content }: { content: iSeoContent[] }) {
    return (
        <article className='prose lg:prose-xl dark:prose-invert max-w-full'>
            {content.map((item, index) => {
                switch (item.type) {
                    case "Paragraph":
                        return (
                            <div
                                key={index}
                                dangerouslySetInnerHTML={{
                                    __html: DOMPurify.sanitize(item.data.text ?? ""),
                                }}
                            />
                        );

                    case "image":
                        return (
                            <div key={index} className="flex justify-center my-4">
                                <Suspense>
                                    <LazyPhotoProvider>
                                        <LazyPhotoView src={`${cdnUrl}/${item.data.image}`}>
                                            <img
                                                src={`${cdnUrl}/${item.data.image}`}
                                                alt={`Image ${index}`}
                                                className='h-full w-[650px]'
                                            />
                                        </LazyPhotoView>
                                    </LazyPhotoProvider>
                                </Suspense>
                            </div>
                        );

                    default:
                        return null;
                }
            })}
        </article>
    );
}
