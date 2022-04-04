import React, { useState, useContext, ReactNode, PropsWithChildren, MouseEventHandler } from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';
import { Transition } from '@headlessui/react';


interface Context {
    open: boolean,
    setOpen: React.Dispatch<React.SetStateAction<boolean>>,
    toggleOpen: MouseEventHandler<HTMLDivElement>,
}

const DropDownContext = React.createContext<Context>({} as Context);

const Dropdown:React.FC = ({children}) => {
    const [open, setOpen] = useState(false);

    const toggleOpen = () => {
        setOpen((previousState) => !previousState);
    };

    return (
        <DropDownContext.Provider value={{ open, setOpen, toggleOpen }}>
            <div className="relative">{children}</div>
        </DropDownContext.Provider>
    );
};

const Trigger: React.FC = ({children}) => {
    const { open, setOpen, toggleOpen } = useContext(DropDownContext);

    return (
        <>
            <div onClick={toggleOpen}>{children}</div>

            {open && <div className="fixed inset-0 z-40" onClick={() => setOpen(false)}></div>}
        </>
    );
};

type ContentProps = {
    align?: string;
    width?: string;
    contentClasses?: string;
}

const Content: React.FC<ContentProps> = ({ align = 'right', width = '48', contentClasses = 'py-1 bg-white', children }) => {
    const { open, setOpen } = useContext(DropDownContext);

    let alignmentClasses = 'origin-top';

    if (align === 'left') {
        alignmentClasses = 'origin-top-left left-0';
    } else if (align === 'right') {
        alignmentClasses = 'origin-top-right right-0';
    }

    let widthClasses = '';

    if (width === '48') {
        widthClasses = 'w-48';
    }

    return (
        <>
            <Transition
                show={open}
                enter="transition ease-out duration-200"
                enterFrom="transform opacity-0 scale-95"
                enterTo="transform opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="transform opacity-100 scale-100"
                leaveTo="transform opacity-0 scale-95"
            >
                {open && (
                    <div
                        className={`absolute z-50 mt-2 rounded-md shadow-lg ${alignmentClasses} ${widthClasses}`}
                        onClick={() => setOpen(false)}
                    >
                        <div className={`rounded-md ring-1 ring-black ring-opacity-5 ` + contentClasses}>
                            {children}
                        </div>
                    </div>
                )}
            </Transition>
        </>
    );
};

type LinkProps = {
    href?: string;
    method?: string;
    as?: string;
}

const Link: React.FC<LinkProps> = ({ href = '', method = 'post', as = 'a', children }) => {
    return (
        <InertiaLink
            href={href}
            method={method}
            as={as}
            className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
        >
            {children}
        </InertiaLink>
    );
};

export default Object.assign(Dropdown, {
    Trigger,
    Content,
    Link
})
