import {BrowserRouter, Route, Routes} from "react-router-dom";
import LoginView from "../Views/LoginView";
import NotFoundView from "../Views/Error/NotFoundView";

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
