import axios from 'axios'
import _ from 'lodash'
// 创建axios实例
const service = axios.create({
  baseURL: 'http://metocean.api/api', // api的base_url
  timeout: 30000 // 请求超时时间
})

// request拦截器
service.interceptors.request.use(
  async config => {
    if (config.params && config.params.constructor === String) {
      config.url += '?' + config.params
      config.params = null
    }
    return config
  },
  error => {
    // Do something with request error
    Promise.reject(error)
  }
)

// respone拦截器
service.interceptors.response.use(
  response => {

    const res = response.data
    if (res.success === false) {
      this.$message.success(
        res.message,
        10,
      );

      // if (
      //   response.code === 401 ||
      //   response.code === 50012 ||
      //   response.code === 50014 ||
      //   response.code === 404
      // ) { }
      return Promise.reject(new Error(res.message))
    } else {
      return response.data
    }
  },
  error => {
    console.log('sadfghyutrewqerty21345')
    let message = null // _.get(error, 'response.data.message')
    const response = _.get(error, 'response')
    // console.log(response)
    if (response && response.status === 500) {
      message = 'Server error'
    } else {
      message = response.data.message || response.data.error.message || error.message
    }

    return Promise.reject(message)
  }
)

export default service
