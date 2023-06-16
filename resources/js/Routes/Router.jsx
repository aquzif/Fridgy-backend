import {BrowserRouter, Route, Routes} from "react-router-dom";
import NotFoundView from "../Views/Error/NotFoundView";
import LoginView from "@/Views/LoginView";

const Router = () => {
    return (
        <BrowserRouter>
            <Routes >
                <Route path="/" element={<LoginView />} />
                <Route path="*" element={<NotFoundView />} />
            </Routes>
        </BrowserRouter>
    )
}

export default Router;
