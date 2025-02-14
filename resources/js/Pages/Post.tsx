import MainLayout from "@/Layouts/MainLayout";
import {Head} from "@inertiajs/react";

export default function Post() {
    return (
        <MainLayout>
            <Head>
                <title>Post</title>
                <meta name="post" content="Post page"/>
            </Head>

        </MainLayout>
    );
}