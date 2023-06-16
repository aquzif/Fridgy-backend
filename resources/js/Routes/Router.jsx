import {BrowserRouter, Route, Routes} from "react-router-dom";
import NotFoundView from "../Views/Error/NotFoundView";
import LoginView from "@/Views/LoginView";
import {useSelector} from "react-redux";
import {useEffect, useState} from "react";
import DashboardView from "@/Views/DashboardView";
import AuthAPI from "@/API/AuthAPI";

const Router = () => {

    const authReducer = useSelector(state => state.authReducer);
    const [loggedIn,setLoggedIn] = useState(authReducer.token !== null && authReducer.token !== undefined && authReducer.token !== '');

    useEffect(() => {
        setLoggedIn(authReducer.token !== null && authReducer.token !== undefined && authReducer.token !== '');

        const result = AuthAPI.getUser();

    }, [authReducer.token]);



    return (
        <BrowserRouter>
            <Routes>
                {
                    loggedIn ? (
                        <>
                            <Route path="/" element={<DashboardView />} />
                            <Route path="*" element={<NotFoundView />} />
                        </>
                    ):(
                        <Route path="*" element={<LoginView />} />
                    )
                }
            </Routes>
        </BrowserRouter>
    )
}

export default Router;
