import { createBrowserRouter } from "react-router-dom";
import Home from "@/pages/Home"
import { fetchTransactions } from "./store/home";

export const routes = createBrowserRouter([
  {
    path: "/",
    loader: fetchTransactions,
    element: <Home />
  }
])
