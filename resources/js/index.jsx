import ReactDOM from "react-dom/client";
import React from "react";
import App from "./App/App";


if (document.getElementById('root')) {
    const Index = ReactDOM.createRoot(document.getElementById("root"));

    Index.render(
        <React.StrictMode>
            <h2>hello world</h2>
        </React.StrictMode>
    )
}
