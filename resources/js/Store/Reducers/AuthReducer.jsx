import {createSlice} from "@reduxjs/toolkit";

const initialState ={
    user: {},
    token: null,
    logoutReason: "",
};

export const authSlice = createSlice({
    name: 'auth',
    initialState,
    reducers: {
        //login: AuthLoginAction,
        //logout: AuthLogoutAction,
        //expire: AuthExpireAction,
        //clearLogoutReason: AuthClearLogoutReasonAction,
        //updateSettings: AuthUpdateSettingsAction
    }
})


//export const {login, logout,expire,clearLogoutReason,updateSettings} = authSlice.actions;
export default authSlice.reducer;
