import RequestUtils from "@/Utils/RequestUtils";

export default class RecipeIngredientsAPI {
    static async getAll(recipeId, variantId) {
        return await RequestUtils.apiGet(`/api/recipe/${recipeId}/variant/${variantId}/ingredient`);
    }


    static async get(recipeId, variantId, id) {
        return await RequestUtils.apiGet('/api/recipe/' + recipeId + '/variant/' + variantId + '/ingredient/' + id);
    }

    static async create(recipeId, variantId, data) {
        const result = await RequestUtils.apiPost('/api/recipe/'+recipeId+'/variant/'+variantId+'/ingredient', data);

        if(result.status !== 201){
            throw new Error('RecipeIngredientsAPI.create() failed, status: ' + result.status);
        }

        return result;
    }

    static async update(recipeId, variantId, id, data) {
        return await RequestUtils.apiPut('/api/recipe/' + recipeId + '/variant/' + variantId + '/ingredient/'+id, data);
    }

    static async delete(recipeId, variantId, id) {
        return await RequestUtils.apiDelete('/api/recipe/' + recipeId+ '/variant/' + variantId + '/ingredient/' + id);
    }

}