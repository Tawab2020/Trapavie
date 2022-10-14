import { createStore } from "vuex";

import actions from "./actions";
import mutations from "./mutations";
import getters from "./getters";
import state from "./state";

const store = createStore({
    actions,
    mutations,
    getters,
    state,
});

export default store;
