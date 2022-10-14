import {
    Storage
} from "./../../../helpers/storage";

const admin = Storage.get("admin");

export default {
    admin: admin || null,
    languages: [],
    faqCats: [],
    venueDataCategories: [], 
    propertyTypes: [],
};
