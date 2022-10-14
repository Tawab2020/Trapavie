class StorageClass {
    constructor() {
        this.key =
            "XOO6qvq8YbomRhyKbhZo5tvpAHfTJvv9N1gia3Qs7pPFS0yaykwExrPKFEdBUKSa";
    }

    get(name) {
        return JSON.parse(localStorage.getItem(this.key + name));
    }

    set(name, item) {
        localStorage.setItem(this.key + name, JSON.stringify(item));
    }

    remove(name) {
        localStorage.removeItem(this.key + name);
    }
}

const Storage = new StorageClass();

export {
    Storage
};
