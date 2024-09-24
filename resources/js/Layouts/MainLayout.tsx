import { PropsWithChildren } from 'react'

export default function MainLayout({ children }: PropsWithChildren) {
    return (
        <div>
            <main>{children}</main>
        </div>
    )
}
