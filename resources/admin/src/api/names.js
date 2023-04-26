import request from '../utils/request';

export function query_names(data){
  return request({
    url: 'http://127.0.0.1:8000/api/names/list?page=' + data.page,
    data,
    method: 'POST'
  })
}
export function addNames(data){
  return request({
    url: 'http://127.0.0.1:8000/api/name/create',
    data,
    method: 'POST'
  })
}
export function addNamesExcle(data){
  return request({
    url: 'http://127.0.0.1:8000/api/name/create/excel',
    data,
    method: 'POST'
  })
}
