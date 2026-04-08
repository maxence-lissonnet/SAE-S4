/**
 * The base URL for the legacy admin API.
 * @constant {string}
 */
const SERVER_API_URL = '../../../index.php?page=api_';

/**
 * If true, the fetch requests are logged in the console.
 * @constant {boolean}
*/
const DEBUG_FETCHS = isDebug();

async function request(endpoint, method = 'GET', data = null, headers = {}) {
    const url = SERVER_API_URL + endpoint;

    try {
        const options = {
            method,
            headers: {
                ...headers
            }
        };

        if (data instanceof File || data instanceof Blob) {
            options.headers['Content-Type'] = data.type;
            options.body = data;
        } else if (data instanceof FormData) {
            options.body = data;
        } else if (data) {
            options.headers['Content-Type'] = 'application/json; charset=utf-8';
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);
        const text = await response.text();
        if (DEBUG_FETCHS) {
            console.log(`%c(${response.status}) %c${method} %c${endpoint}%c${text.startsWith('\n') ? '' : '\n'}${text}`, 'color: red', 'color: peachpuff; font-weight: bold;', 'color: peachpuff;', 'color: powderblue;');
        }

        let json = null;
        try {
            json = JSON.parse(text);
        } catch (error) {
            throw new Error('The API returned a error : ' + error.message);
        }

        if (!response.ok) {
            if (json && json.error) {
                if (json.error === 'Unauthorized' || json.error === 'Forbidden') {
                    window.location.href = 'index.php?page=admin_panel&panel=unauthorized';
                    return;
                }
                throw new Error(json.error);
            }

            if (json && json.message) {
                throw new Error(json.message);
            }

            throw new Error(`Erreur: ${response.status} ${response.statusText}`);
        }

        return json;
    } catch (error) {
        throw error;
    }
}

function requestGET(endpoint) {
    return request(endpoint, 'GET');
}

function requestPOST(endpoint, data) {
    return request(endpoint, 'POST', data);
}

function requestPUT(endpoint, data) {
    return request(endpoint, 'PUT', data);
}

function requestPATCH(endpoint, data) {
    return request(endpoint, 'PATCH', data);
}

function requestDELETE(endpoint) {
    return request(endpoint, 'DELETE');
}

export { request, requestGET, requestPOST, requestPUT, requestPATCH, requestDELETE };

function isDebug() {
    const url = window.location.href;
    return url.includes('?debug');
}
