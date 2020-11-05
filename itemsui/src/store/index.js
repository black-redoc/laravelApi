import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);
const URL = 'http://127.0.0.1:8000/api/items';

export default new Vuex.Store({
    state: {
        allItems: [],
    },
    mutations: {
        setCurrentItem(state, payload) {
            console.log(payload)
            state.allItems.push(payload);
        },
        setAllItems(state, payload) {
            state.allItems = payload;
        },
        updateCurrentItem(state, payload) {
            state.allItems = state.allItems.map((el, idx) => {
                if (idx === payload.id) el = payload;
            });
        },
        deleteCurrentItem(state, payload) {
            state.allItems = state.allItems.filter(el => payload.id !== el.id);
        }
    },
    actions: {
        async updateItem(state, payload) {
            try {
                console.log({...payload})
                const form = new FormData();
                form.append('id', payload.id);
                form.append('title', payload.title);
                form.append('description', payload.description);
                if (payload.photo !== null)
                    form.append('photo', payload.photo, payload.photo.name);

                console.log(form)
                const res = await axios({
                    url: `${URL}`,
                    method: 'PUT',
                    data: form,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                });

                const {id, title, description, photo} = await res.data;

                state.commit("updateCurrentItem", {id, title, description, photo});
            } catch (err) {
                console.log(err);
            }
        },
        async deleteItem(state, payload) {
            try {
                const res = await axios({
                    url: `${URL}/${payload.id}`,
                    method: 'DELETE'
                })

                await res.data;
                state.commit("deleteCurrentItem", {id: payload.id});
            } catch(err) {
                console.log(err);
            }
        },
        async setAllItems(state) {
            try {
                const items = await axios({
                    url: URL,
                    method: 'GET'
                });
                const itemsJson = await items.data;
                state.commit('setAllItems', [...itemsJson]);
            } catch (err) {
                console.log(err);
            }
        },
        async setCurrentItem(state, payload) {
            try {
                const form = new FormData();
                form.append('title', payload.title);
                form.append('description', payload.description);
                if (payload.photo != null)
                    form.append('photo', payload.photo);

                const res = await axios({
                    url: URL,
                    method: 'POST',
                    data: form,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                payload.photo = await res.data.photo;
                state.commit('setCurrentItem', {...payload});
            } catch (err) {
                console.log(err)
            }
        }
    },
    modules: {},
    getters: {
        getAllItems: state => state.allItems,
    }
})
