// src/store/index.js

import { createStore } from "vuex";
import state from './state';
import * as actions from './actions';
import * as mutations from './mutations';
import inventoryModule from '../store/inventory'; // Import the inventory module

const store = createStore({
  state,
  getters: {},
  actions,
  mutations,
  modules: {
    // Register the inventory module here
    inventory: inventoryModule
  }
});

export default store;