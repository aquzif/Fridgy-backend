import React, { useEffect, useState } from 'react';
import { Icon } from '@iconify/react';

/**
 * Display the user's shopping list. When the component mounts it will
 * automatically show the list marked as default. If no list is marked as
 * default the first list in the array is used instead.
 */
const ShoppingListView = ({ lists, onSelectDefault }) => {
    const [current, setCurrent] = useState(null);

    useEffect(() => {
        if (lists && lists.length) {
            const def = lists.find(l => l.is_default);
            setCurrent(def || lists[0]);
        }
    }, [lists]);

    if (!current) {
        return null;
    }

    return (
        <ul>
            <li key={current.id} style={{display:'flex', alignItems:'center', gap:'8px'}}>
                <button
                    onClick={() => onSelectDefault(current.id)}
                    style={{background:'none', border:'none', cursor:'pointer'}}
                >
                    <Icon icon={current.is_default ? 'ic:star' : 'ic:star-border'} />
                </button>
                <span>{current.name}</span>
            </li>
        </ul>
    );
};

export default ShoppingListView;
