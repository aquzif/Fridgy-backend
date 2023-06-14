import {createSlice} from "@reduxjs/toolkit";
import AuthLoginAction from "@/Store/Actions/AuthReducer/AuthLoginAction";
import AuthLogoutAction from "@/Store/Actions/AuthReducer/AuthLogoutAction";
import AuthExpireAction from "@/Store/Actions/AuthReducer/AuthExpireAction";
import AuthClearLogoutReasonAction from "@/Store/Actions/AuthReducer/AuthClearLogoutReasonAction";


const initialState ={
    user: {},
    token: null,
    logoutReason: "",
};

export const authSlice = createSlice({
    name: 'auth',
    initialState,
    reducers: {
        login: AuthLoginAction,
        logout: AuthLogoutAction,
        expire: AuthExpireAction,
        clearLogoutReason: AuthClearLogoutReasonAction,
    }
})


export const {login, logout,expire,clearLogoutReason} = authSlice.actions;
export default authSlice.reducer;
