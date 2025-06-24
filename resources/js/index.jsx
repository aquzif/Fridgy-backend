import ReactDOM from "react-dom/client";
import React from "react";
import ShoppingListView from "./components/ShoppingListView";

const sampleLists = [
    { id: 1, name: 'My list', is_default: true },
    { id: 2, name: 'Work list', is_default: false },
];

const handleSelectDefault = (id) => {
    console.log('Set default', id);
    // TODO call API
};

if (document.getElementById('root')) {
    const Index = ReactDOM.createRoot(document.getElementById("root"));

    Index.render(
        <React.StrictMode>
            <ShoppingListView lists={sampleLists} onSelectDefault={handleSelectDefault} />
        </React.StrictMode>
    );
}
