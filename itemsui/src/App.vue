<template>
  <div id="app">
    <Menu />
    <ShowList/>
  </div>
</template>

<script>
import ShowList from './components/ShowList.vue';
import Menu from './components/Menu.vue';

export default {
  name: 'App',
  components: {
    ShowList, Menu
  },
  mounted() {
    this.$store.dispatch('setAllItems')
  }
}
</script>

<style>
</style>
