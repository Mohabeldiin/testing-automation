import Vue from 'vue'
import DemoImporter from './DemoImporter.vue'

if (document.querySelector('#myhome-importer')) {
    new Vue({
        el: '#myhome-importer',
        components: {DemoImporter}
    });
}