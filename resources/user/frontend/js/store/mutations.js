import { Storage } from "./../../../../helpers/storage";
export default {
    INITIALIZE(state, data){
        state.languages = data.languages;
        state.about = data.about;
        state.currencies = data.currencies
        state.propertyTypes = data.propertyTypes
        if (data.venueDataCategories) {
            state.venueDataCategories = data.venueDataCategories
        }
    },
    UPDATE_ACTIVE_LOCALE(state, locale){
        Storage.set("activeLocale", locale)
        state.activeLocale = locale
    },
    LOGIN(state, user) {
        Storage.set("user", user);
        state.user = user;
    },
    LOGOUT(state) {
        Storage.remove("user");
        state.user = null;
    },
    UPDATE_PROFILE(state, data){
        state.user.image = data.image
        // Now update profile image in local storage
        const lsuser = Storage.get("user");
        let newlsuser = JSON.parse(JSON.stringify(lsuser));
        newlsuser.user = data;
        Storage.set("user", newlsuser);
    },
    UPDATE_VENUE_UNDER_SAVE(state, data){
        Object.assign(state.venueUnderSave, data)
        // state.venueUnderSave = data
    },
    UPDATE_USER_TYPE(state, type){
        state.user.user.type = type
        // Now update profile image in local storage
        const lsuser = Storage.get("user");
        let newlsuser = JSON.parse(JSON.stringify(lsuser));
        newlsuser.user.type = type;
        Storage.set("user", newlsuser);
    },
    CHANGE_VENUE_UNDER_SAVE(state, data){
        // Object.assign(state.venueUnderSave, data)
        state.venueUnderSave = data
    },
    UPDATE_BOOKING_REQUEST(state, data){
        state.bookingRequest = data
    },
    UPDATE_VENUE_BOOKING(state, data){
        state.venueBooking = data
    },
    UPDATE_DATETIME_KEY(state){
        state.dateTimeKey++
    }

};
