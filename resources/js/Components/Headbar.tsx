import CommandMenu from './CommandMenu'

export default function Headbar() {
    return (
        <div className="absolute top-2 flex justify-center w-full z-50">
            <div className="w-auto">
                <CommandMenu />
            </div>
        </div>
    )
}
