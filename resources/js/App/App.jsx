import Router from "../Routes/Router";
import {Toaster} from "react-hot-toast";
import {Provider} from "react-redux";
import store from "@/Store/store";


const App = () => {
    return (
        <>
            <Provider store={store}>
                <Toaster />
                <Router />
            </Provider>

        </>
    );
}

export default App;
