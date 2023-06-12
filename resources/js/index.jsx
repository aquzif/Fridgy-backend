import ReactDOM from "react-dom/client";
import React from "react";
import App from "./App/App";


if (document.getElementById('example')) {
    const Index = ReactDOM.createRoot(document.getElementById("example"));

    Index.render(
        <React.StrictMode>
            <App />
        </React.StrictMode>
    )
}
