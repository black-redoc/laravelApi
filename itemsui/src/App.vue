<template>
  <div id="app">
    <Menu />
    <ShowList msg="Welcome to Your Vue.js App"/>
  </div>
</template>

<script>
import ShowList from './components/ShowList.vue'
import Menu from './components/Menu.vue'

export default {
  name: 'App',
  components: {
    ShowList, Menu
  }
}
</script>

<style>
</style>
