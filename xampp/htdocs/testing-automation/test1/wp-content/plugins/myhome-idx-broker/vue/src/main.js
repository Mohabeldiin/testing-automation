import Vue from 'vue'
import VueResource from 'vue-resource'
import IdxBrokerImport from './IdxBrokerImport.vue'

Vue.use(VueResource);

new Vue({
  el        : '#myhome-idx-broker-import',
  components: {IdxBrokerImport}
});
