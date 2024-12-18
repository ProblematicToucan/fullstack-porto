export interface iUser {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

interface iProjectDescription {
    type: string;
    data: {
        text?: string;
        image?: string;
    };
}

interface iTechStack {
    name: string;
    logo: string;
    description: string;
}

interface iProjectMedia {
    media_type: string;
    media_url: string;
    media_description: string;
}

interface iProject {
    id: number;
    title: string;
    slug: string;
    category_names: string;
    description: iProjectDescription[];
    project_url: string;
    repo_url: string;
    image: string;
    is_featured: boolean;
    tech_stacks: iTechStack[];
    project_medias: iProjectMedia[];
}

interface iPaginate<T> {
    current_page: number,
    data: T[],
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    next_page_url: ?string,
    path: string,
    per_page: number,
    prev_page_url: ?string,
    to: number,
    total: number,
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: iUser;
    };
    projects: iPaginate<iProject>;
};
