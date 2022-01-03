import Vue from 'vue'
import MyHomeUpdater from './Updater'

Vue.component('myhome-updater', MyHomeUpdater);

new Vue({
    el: '#myhome-updater-app'
});