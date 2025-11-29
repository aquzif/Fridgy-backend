import RequestUtils from "@/Utils/RequestUtils";

export default class RecipeVariantsAPI {
    static async getAll(recipeId) {
        return await RequestUtils.apiGet(`/api/recipe/${recipeId}/variant`);
    }

    static async create(recipeId, data) {
        return await RequestUtils.apiPost(`/api/recipe/${recipeId}/variant`, data);
    }

    static async update(recipeId, variantId, data) {
        return await RequestUtils.apiPut(`/api/recipe/${recipeId}/variant/${variantId}`, data);
    }

    static async delete(recipeId, variantId) {
        return await RequestUtils.apiDelete(`/api/recipe/${recipeId}/variant/${variantId}`);
    }
}
