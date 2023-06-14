const login = (state, action) => {
    let payload = action.payload;



    state.logoutReason = "";
    state.user = payload;
    state.token = payload.token;


}

export default login;
