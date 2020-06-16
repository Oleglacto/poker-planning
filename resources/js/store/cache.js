const minutes = 5;
const cacheTime = 60000 * minutes;

class Cache {
    constructor() {
        this.store = {

        }
    }

    generateKey(url, data) {
        let result = url;
        for (const item in data) {
            result += `${item}=${data[item]}`
        }
        return result
    }

    getItem(key) {
        let result = null;
        key = this.stringToHash(key);
        if (key in this.store) {
            result = this._copyObject(this.store[key]);
            if (!this.validate(result)) {
                result = null;
                this.store[key] = null;
            }
        }
        return result;
    }

    setItem(key, value) {
        return this.store[this.stringToHash(key)] = this._createNode(value);
    }

    validate(data) {
        const currentTime = Date.now();
        const { timestamp } = data.meta;
        return ((currentTime - timestamp) < cacheTime);
    }

    _createNode(data) {
        return this._copyObject({
            data,
            meta: {
                timestamp: Date.now(),
            },
        });
    }

    /**
     * For debug
     */
    printStore() {
        console.log({cacheStore: this.store});
    }

    _copyObject(object) {
        return JSON.parse(
            JSON.stringify(object),
        );
    }

    // Convert to 32bit integer
    stringToHash(string) {
        let hash = 0;
        if (string.length === 0) return hash;
        for (let i = 0; i < string.length; i++) {
            let char = string.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash;
        }

        return hash;
    }
}

export default new Cache();
