import { Storage } from "./../../../helpers/storage";
export default {
    LOGIN(state, admin) {
        Storage.set("admin", admin);
        state.admin = admin;
    },
    LOGOUT(state) {
        Storage.remove("admin");
        state.admin = null;
    },
    INITIALIZE(state, data){
        state.languages = data.languages;
        state.faqCats = data.faqCats;
        state.venueDataCategories = data.venueDataCategories;
        state.retreatDataCategories = data.retreatDataCategories;
        state.propertyTypes = data.propertyTypes;
        state.accommodationTypes = data.accommodationTypes
    },
    UPDATE_ADMIN(state, data){
        state.admin.user = data;
        //  updating the admin in LocalStorage as well
        const lsadmin = Storage.get("admin");
        let newlsadmin = JSON.parse(JSON.stringify(lsadmin));
        newlsadmin.user = data;
        Storage.set("admin", newlsadmin);
    },
    UPDATE_LANGUAGE(state, data){
        const index = state.languages.findIndex(lang => lang.id == data.id)
        state.languages[index] = data
    },
    ADD_FAQ_CAT(state, data){
        state.faqCats.push(data);
    },
    DELETE_FAQ_CAT(state, faqCatID){
        const index = state.faqCats.findIndex(cat => cat.id == faqCatID);
        //Delete Children first
        const children = state.faqCats.filter(cat => cat.parent_id == faqCatID);
        children.forEach(child => {
            const index = state.faqCats.findIndex(cat => cat.id == child.id);
            state.faqCats.splice(index, 1);
        });

        // Now delete self
		state.faqCats.splice(index, 1);
    },

    // VENUES
    UPDATE_VENUE_DATA_CATEGORY(state, data){
        const index = state.venueDataCategories.findIndex(cat => cat.id == data.id);
        console.log(data.title);
        state.venueDataCategories[index].title = data.title;
        console.log(data.image);
        state.venueDataCategories[index].image = data.image;
    },
    DELETE_VENUE_DATA(state, {venueDataCategoryID, venueDataId}){
        const index = state.venueDataCategories.findIndex(cat => cat.id == venueDataCategoryID);
        const i = state.venueDataCategories[index].venue_data.findIndex(data => data.id == venueDataId);
        state.venueDataCategories[index].venue_data.splice(i, 1);
    },
    ADD_VENUE_DATA(state, data){
        const index = state.venueDataCategories.findIndex(cat => cat.id == data.venue_data_category_id);
        state.venueDataCategories[index].venue_data.push(data);

    },
    UPDATE_VENUE_DATA(state, data){
        const index = state.venueDataCategories.findIndex(cat => cat.id == data.venue_data_category_id);
        const i = state.venueDataCategories[index].venue_data.findIndex(vd => vd.id == data.id);
        state.venueDataCategories[index].venue_data[i] = data;
    },
 

    // Property types
    ADD_PROPERTY_TYPE(state, data){
        state.propertyTypes.push(data)
    },
    UPDATE_PROPERTY_TYPE(state, data){
        const index = state.propertyTypes.findIndex(propertyType => propertyType.id == data.id)
        state.propertyTypes[index] = data
    },

    // Accommodation Types
    ADD_ACCOMMODATION_TYPE(state, data){
        state.accommodationTypes.push(data)
    },
    UPDATE_ACCOMMODATION_TYPE(state, data){
        const index = state.accommodationTypes.findIndex(accommodationType => accommodationType.id == data.id)
        state.accommodationTypes[index] = data
    }

};
