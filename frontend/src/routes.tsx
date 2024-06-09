import { createBrowserRouter } from "react-router-dom";
import Home from "@/pages/Home"
import Deposit from "@/pages/Deposit"
import Withdraw from "@/pages/Withdraw"
import { fetchTransactions } from "./store/home";

export const routes = createBrowserRouter([
  {
    path: "/",
    loader: fetchTransactions,
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
