<template>
<div>
    <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="https://bulma.io">
        <img src="@/assets/items.png" width="112" height="28">
        </a>

        <a @click="toggleBurgerMenu()" role="button" class="navbar-burger burger" v-bind:class="{'is-active': burgerActivated}" aria-label="menu" aria-expanded="false" data-target="navMenu">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navMenu" class="navbar-menu" v-bind:class="{'is-active': burgerActivated}">
        <div class="navbar-start">
        </div>

        <div class="navbar-end">
        <div class="navbar-item">

            <div class="buttons">
                <a class="button is-primary" @click="toggleModal()">
                    Add Item
                </a>
            </div>
        </div>
        </div>
    </div>
    </nav>
    <div class="modal" v-bind:class="{'is-active': modalActived}">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        Add New Item
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <form @submit="createItem($event)">
                            
                            <div class="field">
                                <label class="label">Tttle</label>
                                <div class="control has-icons-left has-icons-right">
                                    <input v-model="title" class="input" type="text" placeholder="Title">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-file-alt"></i>
                                    </span>
                                    <span class="icon is-small is-right">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Description</label>
                                <div class="control has-icons-left has-icons-right">
                                    <input v-model="description" class="input" type="text" placeholder="Description">
                                    <span class="icon is-small is-left">
                                    <i class="fas fa-envelope"></i>
                                    </span>
                                    <span class="icon is-small is-right">
                                    <i class="fas fa-check"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Photo</label>
                                <div class="control">
                                    <div class="file">
                                        <label class="file-label">
                                            <input class="file-input" type="file" @change="filesChanges($event.target.name, $event.target.files); fileCount = $event.target.files.length">
                                            <span class="file-cta">
                                            <span class="file-icon">
                                                <i class="fas fa-upload"></i>
                                            </span>
                                            <span class="file-label">
                                                Choose a file…
                                            </span>
                                            </span>
                                            <span aria-hidden="true"></span>
                                            <span aria-hidden="true"></span>
                                            <span aria-hidden="true"></span>
                                            <div>
                                            <p class="mt-3 ml-2 has-text-success heading is-centered">{{ photoMessage }}</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <footer class="card-footer">
                                <button type="sybmit" ref="#" class="button card-footer-item is-primary">Save</button>
                                <a href="#" class="button card-footer-item is-danger" @click="toggleModal" aria-label="close">Cancel</a>
                            </footer>
                        </form>
                    </div>
                </div>
                </div>
            </div>
            <!-- <button class="modal-close is-large" aria-label="close" @click="toggleModal()">x</button> -->
    </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Title',
    data() {
        return {
            photoMessage: '',
            burgerActivated: false,
            modalActived: false,
            title: '',
            photo: null,
            description: '',
            fileCount: 0,
        };
    },
    methods: {
        toggleModal() { 
            this.modalActived = !this.modalActived;
        },
        async createItem(event) {
            event.preventDefault();     
            console.log({title: this.title, photo: this.photo, description: this.description});

            const form = new FormData();
            form.append('title', this.title);
            form.append('description', this.description);
            form.append('photo', this.photo);


            try {
                const res = await axios({
                    url: 'http://127.0.0.1:8000/api/items',
                    method: 'POST',
                    data: form,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                console.log(res)
            } catch(err) {
                console.log(err)
            }
        },
        filesChanges(name, file) {
            this.photo = file[0];
            this.photoMessage = `File ${name} has been loaded!`;
        },
        toggleBurgerMenu() {
            this.burgerActivated = !this.burgerActivated;
        }
    }
}
</script>

<style>

</style>