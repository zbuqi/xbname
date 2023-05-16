import request from '../utils/request';

export function query_names(data){
  return request({
    url: '/api/names/list?page=' + data.page,
    data,
    method: 'POST'
  })
}
export function editName(data){
  return request({
    url: '/api/name/' + data.id + '/edit',
    data,
    method: 'POST'
  })
}
export function addNames(data){
  return request({
    url: '/api/name/create',
    data,
    method: 'POST'
  })
}
export function addNamesExcle(data){
  return request({
    url: '/api/name/create/excel',
    data,
    method: 'POST'
  })
}
export function tmpNames(data){
  return request({
    url: '/api/name/beian/tmp_names',
    data,
    method: 'POST'
  })
}
