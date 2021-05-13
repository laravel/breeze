import React, { useState, useContext } from "react";
import { InertiaLink } from "@inertiajs/inertia-react";

const DropDownContext = React.createContext();

const Dropdown = ({ children }) => {

    const [open, setOpen] = useState(false);

    const toggleOpen = () => {
        setOpen((prevState) => !prevState);
    };


    return (
        <DropDownContext.Provider value={{ toggleOpen, open, setOpen }}>
            <div className="relative">
                {children}
            </div>
        </DropDownContext.Provider>
    );
}

const Content = ({ children }) => {

    const { open, setOpen } = useContext(DropDownContext)


    return (
        <>
            {open && (
                <>
                    <div className="fixed inset-0 z-40" onClick={() => setOpen(false)}></div>

                    <div className="absolute z-50 mt-2 rounded-md shadow-lg origin-top-left right-0 w-48"
                        onClick={() => setOpen(false)}
                    >
                        <div className="rounded-md bg-white ring-1 ring-black ring-opacity-5">
                            {children}
                        </div>
                    </div>
                </>
            )}
        </>
    )

}

const Trigger = ({ children }) => {
    const { toggleOpen } = useContext(DropDownContext)

    return (
        <div onClick={toggleOpen}>
            {children}
        </div>
    )
}

const Link = ({ href, children }) => {
    return <InertiaLink
        href={href}
        method="post"
        as="button"
        className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
    >
        {children}
    </InertiaLink>
}

Dropdown.Trigger = Trigger;
Dropdown.Content = Content;
Dropdown.Link = Link;

export default Dropdown;
