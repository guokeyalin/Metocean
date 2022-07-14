import request from '@/utils/request'

export function getFieldList () {
  return request({
    url: '/metocean/field',
    method: 'get'
  })
}

export function getFieldValue () {
  return request({
    url: '/metocean/value',
    method: 'get'
  })
}




