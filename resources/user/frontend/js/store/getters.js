export default {
    user(state) {
        return state.user;
    },
    languages(state) {
        return state.languages;
    },
    notifications(state) {
        return state.notifications
    },
    activeLocale(state) {
        return state.activeLocale
    },
    about(state) {
        return state.about;
    },
    currencies(state) {
        return state.currencies;
    },
    propertyTypes(state) {
        return state.propertyTypes;
    },
    venueDataCategories(state) {
        return state.venueDataCategories
    },
    venueData: (state) => (venueDataCategoryId) => {
        const index = state.venueDataCategories.findIndex(venueDataCategory => venueDataCategory.id == venueDataCategoryId)

        return state.venueDataCategories[index] ? state.venueDataCategories[index].user_venue_data : []
    },
    venueUnderSave(state) {
        if (!state.venueUnderSave.id) {
            return {}
        }
        return state.venueUnderSave;
    },
    bookingRequest(state) {
        return state.bookingRequest
    },
    venueBooking(state) {
        return state.venueBooking
    },
    dateTimeKey(state) {
        return state.dateTimeKey
    }
};
