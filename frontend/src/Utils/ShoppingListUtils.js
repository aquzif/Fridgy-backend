import {ray} from "node-ray/web";
import ArrayUtils from "@/Utils/ArrayUtils";
import store from "@/Store/store";

export default class ShoppingListUtils {

    static sortAndPrepareShoppingList(shoppingList) {

        let shoppingListToReturn = [];

        if(!shoppingList) return [];

        let {type, entries, sort} = shoppingList;
        if(type === 'default'){
            console.log('ENT',entries);
            let unchecked = entries.filter(entry => !entry.checked).map(entry => ({
                type: 'entry',
                ...entry
            }));
            let checkedEntries = entries.filter(entry => entry.checked).map(entry => ({
                type: 'entry',
                ...entry
            }));
            if(sort){
                unchecked = unchecked.sort((a, b) => a.product_name.localeCompare(b.product_name));
                checkedEntries = checkedEntries.sort((a, b) => a.product_name.localeCompare(b.product_name));
            }
            shoppingListToReturn = [
                ...unchecked,
                ...checkedEntries
            ];
        } else if(type === 'grouped'){

            let categories = entries.map(entry =>
                entry.product_category && {
                id: entry.product_category.id,
                name: entry.product_category.name
                    }
            );

            categories = [...new Set(categories.filter(category => category))];

            Object.entries(ArrayUtils.
            groupBy(entries
                .filter(entry => !entry.checked),'category_id'))
                .map(([key,entries]) => {
                    let category = 'undefined'
                    if(key != 'null') {
                        console.log('KEY:',key);
                        category = categories.find(category => category.id == parseInt(key)).name;
                    }


                    let items = entries.map(entry => ({
                        type: 'entry',
                        ...entry
                    }));
                    if(sort)
                        items = items.sort((a,b) => a.product_name.localeCompare(b.product_name));

                    shoppingListToReturn = [...shoppingListToReturn,
                        {
                            type: 'category',
                            category: category
                        },
                        ...items
                    ];
                });
            if(entries.filter(entry => entry.checked).length > 0){
                shoppingListToReturn = [...shoppingListToReturn,
                    {
                        type: 'category',
                        category: 'Zaznaczone'
                    },
                    ...(sort ?
                        entries.filter(entry => entry.checked)
                            .map(entry => ({type:'entry',...entry}))
                            .sort((a,b)=>a.product_name.localeCompare(b.product_name))
                        :
                        entries.filter(entry => entry.checked)
                            .map(entry => ({type:'entry',...entry}))
                    )
                ];
            }
        }

        return shoppingListToReturn;

    }

}