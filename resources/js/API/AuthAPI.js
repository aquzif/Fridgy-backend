import RequestUtils from "@/Utils/RequestUtils";

export default class AuthAPI {

    /**
     *
     * function returns request promise from server
     *
     * @param email - user email
     * @param password - user password
     * @returns {Promise<*>}
     */
    static async login(email, password) {

        return RequestUtils.post('/api/login', {
            email: email,
            password: password
        });

    }

    static async logout() {

    }

}
