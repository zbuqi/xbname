import request from '../utils/request';

export function query_names(data){
  return request({
    url: '/names/list?page=' + data.page,
    data,
    method: 'POST'
  })
}
export function editName(data){
  return request({
    url: '/name/' + data.id + '/edit',
    data,
    method: 'POST'
  })
}
export function addNames(data){
  return request({
    url: '/name/create',
    data,
    method: 'POST'
  })
}
export function addBeianName(data){
  return request({
    url: '/name/create/beian/names',
    data,
    method: 'POST'
  })
}


/**临时查询备案域名**/
export function addNamesExcle(data){
  return request({
    url: '/name/create/excel',
    data,
    method: 'POST'
  })
}
export function addTmpNames(data){
  return request({
    url: '/tmp_name/create',
    data,
    method: 'POST'
  })
}
export function tmpNames(data){
  return request({
    url: '/tmp_name/beian/names',
    data,
    method: 'POST'
  })
}
