import RequestUtils from "@/Utils/RequestUtils";
import store from "@/Store/store";
import UserUtils from "@/Utils/UserUtils";

export default class AuthAPI {


    static async login(email, password) {
        return await RequestUtils.post('/api/login', {
            email: email,
            password: password
        });

    }

    static async getUser() {
        return await RequestUtils.get('/api/user', {}, {
            'Authorization': 'Bearer ' + UserUtils.getUserToken()
        });

    }

    static async logout() {

        return await RequestUtils.post('/api/logout', {}, {
            'Authorization': 'Bearer ' + UserUtils.getUserToken()
        });

    }

}
