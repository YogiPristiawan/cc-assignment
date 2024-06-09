import { createBrowserRouter } from "react-router-dom";
import Home from "@/pages/Home"
import Deposit from "@/pages/Deposit"
import Withdraw from "@/pages/Withdraw"

export const routes = createBrowserRouter([
  {
    path: "/",
    element: <Home />
  },
  {
    path: "/deposit",
    element: <Deposit />
  },
  {
    path: "/withdraw",
    element: <Withdraw />
  }
])
