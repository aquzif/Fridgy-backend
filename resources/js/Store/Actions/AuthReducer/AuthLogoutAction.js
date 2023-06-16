import toast from "react-hot-toast";

const logout = (state, action) => {

    toast.success('Wylogowano pomyślnie');

    return {
        ...state,
        user: {},
        token: null,
        logoutReason: "USER_LOGGED_OUT"
    }

}

export default logout;
