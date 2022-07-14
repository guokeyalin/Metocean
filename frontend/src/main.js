import Vue from 'vue'
import App from './App.vue'
import 'ant-design-vue/dist/antd.css';
import Antd from 'ant-design-vue'
import axios from "axios";

Vue.config.productionTip = false
Vue.use(Antd)
axios.defaults.baseURL = 'http:://metocean.api/'
new Vue({
  render: h => h(App),
}).$mount('#app')
