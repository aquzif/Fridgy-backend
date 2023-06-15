import { configureStore } from '@reduxjs/toolkit'
import authReducer from "./Reducers/AuthReducer";

const store = configureStore({
    reducer: {
        authReducer
    }
});

store.subscribe(LocalStorageUtils.saveState);


export default store;

