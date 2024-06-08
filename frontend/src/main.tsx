import React from 'react'
import ReactDOM from 'react-dom/client'
import './index.css'
import { RouterProvider } from 'react-router-dom'
import { routes } from "./routes"

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <div className="w-[1200px] mx-auto grid place-items-center h-screen">
      <RouterProvider router={routes} />
    </div>
  </React.StrictMode>,
)
