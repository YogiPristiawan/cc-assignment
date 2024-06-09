import React from 'react'
import ReactDOM from 'react-dom/client'
import './index.css'
import { RouterProvider } from 'react-router-dom'
import { routes } from "./routes"
import { cn } from "@/lib/utils"

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <div className={cn(
      "mx-auto grid place-items-center h-screen",
      "md:w-[1200px] "
    )}>
      <RouterProvider router={routes} />
    </div>
  </React.StrictMode>,
)
