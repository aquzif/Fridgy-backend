import RequestUtils from "@/Utils/RequestUtils";

export default class ShelfEntriesAPI {
    static async getAll() {
        return await RequestUtils.apiGet('/api/shelf');
    }

    static async get(id) {
        return await RequestUtils.apiGet('/api/shelf/' + id);
    }

    static async create(data) {
        const result = await RequestUtils.apiPost('/api/shelf', data);
        if(result.status >= 300) {
            throw new Error('ShelfEntriesAPI.create() failed, status: ' + result.status);
        }
        return result;
    }

    static async update(id, data) {
        return await RequestUtils.apiPut('/api/shelf/' + id, data);
    }

    static async delete(id) {
        return await RequestUtils.apiDelete('/api/shelf/' + id);
    }
}
