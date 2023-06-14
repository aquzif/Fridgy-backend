
export default class RequestUtils{

    static #expired_checked = false;

    static async get(url, data = {}, headers = {}){
        return await this.request(url, 'GET', data , headers);
    }

    static async post(url, data = {}, headers = {}){
        return await this.request(url, 'POST', data , headers);
    }

    static async put(url, data = {}, headers = {}){
        return await this.request(url, 'PUT', data, headers);
    }

    static async delete(url, data = {}, headers = {}){
        return await this.request(url, 'DELETE', data, headers);
    }

    static async request(url, method, data, headers){

        let toReturn = {};

        let response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...headers
            },
            ...method !== 'GET' ? {body: JSON.stringify(data)} : {}
        }).catch((error) => {
            console.log(error);
            toReturn = {
                data: null,
                status: 500,
                error: "server connection error"
            }
        });

        if(url[0] === '/' && response.status === 401){

            //TODO expire session here
            if(!this.#expired_checked){
                this.#expired_checked = true;
                setTimeout(() => this.#expired_checked = false, 1000);
                //TODO send message here
            }



        }

        if(response){
            toReturn = {
                data: await response.json(),
                status: response.status
            }
        }

        return toReturn;
    }

}
