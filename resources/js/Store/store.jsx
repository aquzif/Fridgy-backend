import { configureStore } from '@reduxjs/toolkit'
import authReducer from "./Reducers/AuthReducer";

const store = configureStore({
    reducer: {
        authReducer
    }
});



export default store;

