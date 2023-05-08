import request from '@/utils/request'

export function login(data) {
  return request({
    url: 'http://127.0.0.1:8000/api/user/login',
    //url: '/vue-admin-template/user/login',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: 'http://127.0.0.1:8000/api/user/info',
    //url: '/vue-admin-template/user/info',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/vue-admin-template/user/logout',
    method: 'post'
  })
}

export function validUsername(data) {
  return request({
    url: 'http://127.0.0.1:8000/api/user/valid/name',
    method: 'post',
    data
  })
}
