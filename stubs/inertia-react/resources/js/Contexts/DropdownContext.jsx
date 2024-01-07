import { createContext, useContext, useState } from 'react'

const DropdownContext = createContext()

// ====================================
export default function DropdownProvider({ children }) {
  const [isOpen, setIsOpen] = useState(false)

  function handleToggleOpen() {
    setIsOpen((prevState) => !prevState)
  }

  return (
    <DropdownContext.Provider value={{ isOpen, setIsOpen, handleToggleOpen }}>
      {children}
    </DropdownContext.Provider>
  )
}

export function useDropdownContext() {
  const context = useContext(DropdownContext)

  if (!context) {
    throw new Error('useDropdownContext must be used within a DropdownProvider')
  }

  return context
}
