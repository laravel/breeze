import { DropdownProvider } from "@/Contexts/DropdownContext";

export default function DropdownRoot ({children}) {
return (
    <DropdownProvider>
        <div className="relative">{children}</div>
    </DropdownProvider>
)
}
