import { Storage } from "./../../../../helpers/storage";

const user = Storage.get("user");
const activeLocale = Storage.get("activeLocale");

export default {
    user: user || null,
    about: {},
    languages: [],
    activeLocale: activeLocale || "en",
    currencies: [],
    propertyTypes: [],
    venueDataCategories: [],
    venueUnderSave: {},
    bookingRequest: {},
    venueBooking: {},
    dateTimeKey: 0, // this key is used accross the app to remount the elements that use $DateTime to change their locale
};
