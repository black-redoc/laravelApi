<template>
  <div class="container">
    <table class="table full-width">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,idx) in items" :key="idx"> 
          <td>
            <figure class="image is-128x128">
              <img v-bind:src="'http://127.0.0.1:8000' + item.photo" alt="">
            </figure>
          </td>
          <td>{{ item.title }}</td>
          <td>{{ item.description }}</td>
          <td>
            <button class="button is-primary" @click="toggleModal(item.id, item.title, item.description)">Update</button>
          </td>
          <td>
            <button class="button is-danger" @click="toggleDeleteModal(item.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>


    <div class="modal" v-bind:class="{'is-active': modalDeleteActived}">
      <div class="modal-background"></div>
      <div class="modal-content">
        <div class="card">
          <div class="card-header">
            <p class="card-header-title">
              Delete Item {{id}}
            </p>
          </div>
          <div class="card-content">
            <form @submit="deleteItem($event)">
              <div class="field">
                <label class="label">Are you sure you want to delete Item {{ id }}?</label>
              </div>

              <footer class="card-footer">
                <button type="sybmit" ref="#" class="button card-footer-item is-danger">Delete</button>
                <a href="#" class="button card-footer-item is-primary" @click="toggleDeleteModal" aria-label="close">Cancel</a>
              </footer>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" v-bind:class="{'is-active': modalActived}">
      <div class="modal-background"></div>
      <div class="modal-content">
        <div class="card">
          <header class="card-header">
              <p class="card-header-title">
                  Update Item {{id}}
              </p>
          </header>
          <div class="card-content">
              <div class="content">
                  <form @submit="updateItem($event)">
                      
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
    </div>
    
  </div>
</template>

<script>
export default {
  name: 'ShowList',
  data() { 
    return {
      //items: [],
      title: '',
      photo: null,
      description: '',
      modalActived: false,
      modalDeleteActived: false,
      photoMessage: '',
      id: 0,
    }
  },
  computed: {
    items() {
      return [...this.$store.getters.getAllItems]
    }
  },
  methods: {
    toggleModal(idForm, titleForm, descriptionForm) { 
      this.id = idForm;
      this.title = titleForm;
      this.description = descriptionForm;
      this.modalActived = !this.modalActived;
    },
    toggleDeleteModal(idForm) {
      this.id = idForm;
      this.modalDeleteActived = !this.modalDeleteActived;
    },
    async deleteItem(event) {
      event.preventDefault();
      this.$store.dispatch("deleteItem", {id: this.id})
      this.toggleDeleteModal(0);
    },
    async updateItem(event) {
      event.preventDefault();     
      this.$store.dispatch("updateItem", {
        id: this.id,
        title: this.title,
        description: this.description,
        photo: this.photo,
      })
      this.toggleModal();
    },
    filesChanges(name, file) {
      this.photo = file[0];
      this.photoMessage = `File ${name} has been loaded!`;
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.full-width {
  width: 100%;
  margin: 2rem;
}
</style>
