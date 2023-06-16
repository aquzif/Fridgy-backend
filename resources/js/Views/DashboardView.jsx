import AuthAPI from "@/API/AuthAPI";
import store from "@/Store/store";
import {logout} from "@/Store/Reducers/AuthReducer";


const DashboardView = () => {

    const handleLogout = async () => {
        await AuthAPI.logout();
        store.dispatch(logout());
    }

    return <>
        <h2>Dashboard</h2>
        <button onClick={handleLogout} >Wyloguj się</button>
    </>

}

export default DashboardView;
