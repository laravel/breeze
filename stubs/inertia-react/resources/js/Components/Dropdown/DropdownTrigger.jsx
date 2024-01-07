import { useDropdownContext } from "./DropdownContext";

export default function DropdownTrigger({ children }) {
    const { isOpen, setIsOpen, handleToggleOpen } = useDropdownContext();

    return (
        <>
            <div onClick={handleToggleOpen}>{children}</div>
            {isOpen && (
                <div
                    className="fixed inset-0 z-40"
                    onClick={() => setIsOpen(false)}
                ></div>
            )}
        </>
    );
}
